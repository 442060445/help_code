<?php

namespace App\Http\Controllers;

use App\Http\Business\v1\CodeBusiness;
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
        (new CodeBusiness())->test();
    }
    //
}
