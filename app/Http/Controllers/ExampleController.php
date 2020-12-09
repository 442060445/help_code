<?php

namespace App\Http\Controllers;

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
        echo 1;
        Redis::set("test", '123');

    }
    //
}
