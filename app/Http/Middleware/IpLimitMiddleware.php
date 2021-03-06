<?php

namespace App\Http\Middleware;

use App\Http\Common\Helper;
use Closure;
use Illuminate\Support\Facades\Cache;

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
        $cacheKey = $prefix.$ip;
        $time = Cache::get($cacheKey);
        if(empty($time)){
            Cache::put($cacheKey,1,env('MAX_IP_LIMIT_TIME',3600));
        }else {
            Cache::increment($cacheKey,1);
        }
        //超过禁止往下执行了
        if(Cache::get($cacheKey) > env('MAX_IP_LIMIT',25)){
            return Helper::returnFromat(400,trans('message.frequently'),[]);
        }

        return $next($request);
    }
}
