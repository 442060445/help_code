<?php

namespace App\Http\Common;

use App\Component\ipLocation\IpLocation;
use App\Http\Common\Helper;
use Closure;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Redis;

class IpLimit
{
    public static function check($type)
    {
        //定义前缀
        $prefix = 'lock_ip-';
        //获取那个人的IP
        $ip = $_SERVER['REMOTE_ADDR'];
        //key命名
        $cacheKey = $prefix.$type."-".$ip;
        $time = Cache::get($cacheKey);
        if(empty($time)){
            Cache::put($cacheKey,1,env('TYPE_MAX_IP_LIMIT_TIME',600));
        }else {
            Cache::increment($cacheKey,1);
        }
        //超过禁止往下执行了
        if(Cache::get($cacheKey) > env('TYPE_MAX_IP_LIMIT',5)){
            return false;
        }
        return true;
    }
}
