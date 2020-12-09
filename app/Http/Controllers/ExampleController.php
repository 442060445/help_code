<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Redis;

class ExampleController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function test() {
        app()->get('JdBeanModel')->create(['code'=>'test']);
        app()->get('JdBeanModel')->where('id','1')->delete();
        Cache::put('123','123',86400);
        Cache::get('123');
    }
    //
}
