<?php
/**
 * 主redis随机读取 + mysql 辅助备份 版本
 */
namespace App\Http\Business\V2;

use App\Http\Common\Helper;

class CodeBusiness
{
    public function create($type,$code){
        //初始化model
        $typeArray = config('typeToModel');
        //Redis 名称自动过滤掉后面的Model
        $typeRedisArray = array_map(function ($v){
            return str_replace('Model',"",$v);
        },$typeArray);
        if(!isset($typeArray[$type])){
            return Helper::returnFromat(400,trans('message.unknown-type'),[]);
        }
        //redis 初始化
        $redis = app()->get('redis');
        //直接添加，因为redis 的set不会重复。如果已经存在就会直接返回0就是进了else
        if($redis->sAdd(env('CACHE_PREFIX','').$typeRedisArray[$type] , $code)){
            if(env('MYSQL_USE',false)){
                $model = app()->get($typeArray[$type]);
                $model->where('code',$code)->create(['code'=>$code]);
            }
            return Helper::returnFromat(200,trans('message.add-success'),[]);
        }else{
            return Helper::returnFromat(400,trans('message.exist'),[]);
        }
    }

    public function read($type,$rankCount){
        //随机数量机制(解释：获取顺序：传进来的要求几个 > 京东配置 的最大助力个数 > 10个)
        $rankCount = empty($rankCount)? isset(config('typeToQuantity')[$type])?config('typeToQuantity')[$type] : 10 : $rankCount;
        $typeModelArray = config('typeToModel');
        //Redis 名称自动过滤掉后面的Model
        $typeRedisArray = array_map(function ($v){
             return str_replace('Model',"",$v);
        },$typeModelArray);
        $typeArray = array_keys($typeModelArray);
        if(!in_array($type,$typeArray)){
            return Helper::returnFromat(400,trans('message.unknown-type'),[]);
        }
        //redis 初始化
        $redis = app()->get('redis');
        $result = $redis->sRandMember(env('CACHE_PREFIX','').$typeRedisArray[$type] , $rankCount);
        return Helper::returnFromat(200,trans('message.read-success'),$result);
    }

    public function count($type) {
        $typeModelArray = config('typeToModel');
        $typeArray = array_keys($typeModelArray);
        if(!in_array($type,$typeArray)){
            return Helper::returnFromat(400,trans('message.unknown-type'),[]);
        }
        //Redis 名称自动过滤掉后面的Model
        $typeRedisArray = array_map(function ($v){
            return str_replace('Model',"",$v);
        },$typeModelArray);
        //初始化SCARD

        //redis 初始化
        $redis = app()->get('redis');
        $count = $redis->sCard(env('CACHE_PREFIX','').$typeRedisArray[$type]);
        return Helper::returnFromat(200,trans('message.count').$count,[]);
    }

    /**
     * @function-remark:日常置零次数。
     * @author Lin ShiXuan
     * @date: 2020/12/10 9:11
     */
    public function ResetDaily(){
        echo "无日清程序";
        return;
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
        //Redis 名称自动过滤掉后面的Model
        $typeRedisArray = array_map(function ($v){
            return str_replace('Model',"",$v);
        },$typeArrayModel);
        $typeArray = array_keys($typeArrayModel);

        //redis 初始化
        $redis = app()->get('redis');
        foreach ($typeArray as $type){
            //清理缓存区
            $redis->del(env('CACHE_PREFIX','').$typeRedisArray[$type]);
            //清理数据库
            if(env('MYSQL_USE',false)) {
                $model = app()->get($typeArrayModel[$type]);
                $model->where('id', '>', 1)->delete();
            }
        }
    }

}
