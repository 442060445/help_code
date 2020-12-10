<?php
namespace App\Http\Business;

use App\Http\Common\Helper;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

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

    public function read($type){
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
        if(empty(Cache::get($type."_".$page."_time")) || Cache::get($type."_".$page."_time") >= env('MAX-OUTPUT-TIME',25)){
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
        $typeArray = config('typeToModel');
        $typeArray = array_keys($typeArray);
        foreach ($typeArray as $type){
            if(!empty($page = Cache::get($type."_page"))){
                for ($i = 1;$i<=$page ;$i++){
                    Cache::forget($type."_".$i.'_time');
                    Cache::forget($type."_".$i);
                }
            }
            Cache::put($type."_page",1);
        }
    }
}
