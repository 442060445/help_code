<?php
namespace App\Http\Business;

use App\Http\Common\Helper;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

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
            $page = 1;
            Cache::put($type."_page",$page,86400);
        }
        if(empty(Cache::get($type."_".$page."_time")) || Cache::get($type."_".$page."_time") >= env('MAX-OUTPUT-TIME',25)){
            $page ++;
            // @TODO 输出数据库
            $_GET['page'] = $page;
            $result = $model->whereNull("delete_time")->paginate(5);
            var_dump($result->toArray());exit;
            // @ TODO 做缓存
            Cache::put($type."_".$page,$result,864000);
            // @TODO 改页数缓存 要+1
            Cache::increment($type."_page");
            // @TODO 次数 输出为1
            Cache::increment($type."_".$page."_time");
        }else{
            // @TODO 拉出数据
            $result = Cache::get($type."_".$page);
            // @TODO 更新数据次数 +1
            Cache::increment($type."_".$page."_time");
        }
        return Helper::returnFromat(200,trans('message.read-success'),$result);
    }

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
                    Cache::forget($type."_".$i.'_time');
                }
            }
            Cache::forget($type."_page");
        }
    }
}
