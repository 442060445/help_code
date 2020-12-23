<?php

namespace App\Http\Middleware;

use App\Component\ipLocation\IpLocation;
use App\Http\Common\Helper;
use Closure;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Redis;

class IpLimitMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        //定义前缀
        $prefix = 'lock_ip-';
        //获取传过来的CODE字段
        $type = $request->get('type')?? "";
        //获取那个人的IP
        $ip = $_SERVER['REMOTE_ADDR'];
        //key命名
        $cacheKey = $prefix.$type."-".$ip;
        $time = Cache::get($cacheKey);
        if(empty($time)){
            Cache::put($cacheKey,1,3600);
        }else {
            Cache::increment($cacheKey,1);
        }
        //超过禁止往下执行了
        if(Cache::get($cacheKey) > env('MAX_IP_LIMIT',5)){
            return Helper::returnFromat(400,trans('message.frequently'),[]);
        }

        return $next($request);
    }
}
