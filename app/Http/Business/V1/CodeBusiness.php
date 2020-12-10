<?php
/**
 * 读取Mysql + redis  顺序版本
 */
namespace App\Http\Business\V1;

use App\Http\Common\Helper;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redis;

class CodeBusiness
{
    public function create($type,$code){
        //初始化model

        $typeArray = config('typeToModel');
        if(!isset($typeArray[$type])){
            return Helper::returnFromat(400,trans('message.unknown-type'),[]);
        }else{
            $model = app()->get($typeArray[$type]);
        }
        //检查是否存在
        if($model->where('code',$code)->count()){
            //存在，返回
            return Helper::returnFromat(400,trans('message.exist'),[]);
        }else {
            //不存在，创建，返回
            $model->where('code',$code)->create(['code'=>$code]);
            return Helper::returnFromat(200,trans('message.add-success'),[]);
        }
    }

    public function read($type,$rankCount){
        //随机数量机制(解释：获取顺序：传进来的要求几个 > 京东配置 的最大助力个数 > 10个)
        $rankCount = empty($rankCount)? isset(config('typeToQuantity')[$type])?config('typeToQuantity')[$type] : 10 : $rankCount;
        $typeModelArray = config('typeToModel');
        $typeArray = array_keys($typeModelArray);
        if(!in_array($type,$typeArray)){
            return Helper::returnFromat(400,trans('message.unknown-type'),[]);
        }
        /**
         * 定义3个值
         * $type_page => 用来存页面（每夜重置到1） 定义为$page
         * $type_$page => 存档缓存数据 （每次清零时使用！）
         * $type_$page_time => 存放每页出来的次数。上限定义在ENV文件。
         */
        //初始化
        $model = app()->get($typeModelArray[$type]);
        //检查这个值
        $page = Cache::get($type."_page");
        //如果为空，重新定义值为第一页，存放到缓存
        if(empty($page)){
            $page = 0;
            Cache::put($type."_page",$page,86400);
        }
        //获取数据
        if(empty(Cache::get($type."_".$page."_time")) || Cache::get($type."_".$page."_time") >= env('MAX_OUTPUT_TIME',25)){
            $page ++;
            $result = $this->generateCodeList($type,$page,$model);
            // @TODO 改页数缓存 要+1
            Cache::increment($type."_page");
        }else{
            // @TODO 拉出数据
            $result = Cache::get($type."_".$page);
            // @TODO 更新数据次数 +1
            Cache::increment($type."_".$page."_time");
            // @TODO 数据低于应出数量，需要重载
            if(count($result) < config('typeToQuantity')[$type]){
                $result = $this->generateCodeList($type,$page,$model);
            }
        }
        //如果随机数 不符合 要求
        if(count($result)!= $rankCount){
            shuffle($result); //调用现成的数组随机排列函数
            $result = array_slice($result, 0, $rankCount); //截取前$limit个
        }
        return Helper::returnFromat(200,trans('message.read-success'),$result);
    }

    public function count($type) {
        $typeModelArray = config('typeToModel');
        $typeArray = array_keys($typeModelArray);
        if(!in_array($type,$typeArray)){
            return Helper::returnFromat(400,trans('message.unknown-type'),[]);
        }

        //初始化
        $model = app()->get($typeModelArray[$type]);
        $count = $model->whereNull("delete_time")->count();
        return Helper::returnFromat(200,trans('message.count').$count,[]);

    }

    /**
     * @function-remark: 加载数据库列。
     * @param $type
     * @param $page
     * @param $model
     * @return mixed
     * @author Lin ShiXuan
     * @date: 2020/12/10 10:20
     */
    private function generateCodeList($type,$page,$model){
        $quantity = isset(config('typeToQuantity')[$type])?config('typeToQuantity')[$type] : 10;
        $offset = ($page-1) * $quantity;
        // @TODO 输出数据库
        $result = $model->whereNull("delete_time")->offset($offset)->limit($quantity)->pluck('code')->toArray();
        // @ TODO 做缓存
        Cache::put($type."_".$page,$result,864000);
        // @TODO 次数 输出为1
        Cache::put($type."_".$page."_time",1);
        return $result;
    }

    /**
     * @function-remark:日常置零次数。
     * @author Lin ShiXuan
     * @date: 2020/12/10 9:11
     */
    public function ResetDaily(){
        /**
         * $type_page => 用来存页面（每夜重置到1） 定义为$page
         * $type_$page_time => 都要置零或者。
         */
        $typeArray = config('typeToModel');
        $typeArray = array_keys($typeArray);
        foreach ($typeArray as $type){
            if(!empty($page = Cache::get($type."_page"))){
                for ($i = 1;$i<=$page ;$i++){
                    Cache::put($type."_".$i.'_time',0);
                    if(empty(Cache::get($type."_".$i))) {
                        Cache::forget($type."_".$i);
                    }
                }
            }
            Cache::put($type."_page",1);
        }
    }


    /**
     * @function-remark:周期置零次数。
     * @author Lin ShiXuan
     * @date: 2020/12/10 9:11
     */
    public function ResetWeekly(){
        /**
         * $type_page => 用来存页面（每夜重置到1） 定义为$page
         * $type_$page_time => 都要置零或者。
         */
        $typeArrayModel = config('typeToModel');
        $typeArray = array_keys($typeArrayModel);
        foreach ($typeArray as $type){
            //清理缓存区
            if(!empty($page = Cache::get($type."_page"))){
                for ($i = 1;$i<=$page ;$i++){
                    Cache::forget($type."_".$i.'_time');
                    Cache::forget($type."_".$i);
                }
            }
            Cache::put($type."_page",1);
            //清理数据库
            $model = app()->get($typeArrayModel[$type]);
            $model->where('id', '>', 1)->delete();

        }
    }

    public function test(){
        $redis = app()->get('redis');
        $redis->sAdd('my_test','11','12','13','14','15','16','17','18','19','20');
        var_dump($redis->sRandMember('my_test',3));
    }
}
