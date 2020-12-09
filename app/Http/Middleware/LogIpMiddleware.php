<?php

namespace App\Http\Middleware;

use App\Component\ipLocation\IpLocation;
use Closure;

class LogIpMiddleware
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
        $ignore = [
            "https://lumen.chiang.fun/api/online-checker",
            "https://lumen.chiang.fun/jdcookie"
        ];
        //控制不走中间件的
        if(in_array($request->fullUrl() ,$ignore )
        || $_SERVER['REMOTE_ADDR'] == env('APP_IP',"localhost")
        ){
            return $next($request);
        }
        $insertRow = [];
        $insertRow['ip'] = $_SERVER['REMOTE_ADDR'];
        $location = (new IpLocation())->getlocation($_SERVER['REMOTE_ADDR']);
        $insertRow['address'] = $location['country'].$location['area'];
        if(strpos($insertRow['address'], 'CDN') !== false){
            return response('顶你啊，抓你个屁啊',404);
        }
        $insertRow['load_site'] = $request->fullUrl();
        app('LoadIpModel')->updateOrInsert(['ip'=>$_SERVER['REMOTE_ADDR'],'date_time'=>date('Y-m-d H:i:s')],$insertRow);
        return $next($request);
    }
}
