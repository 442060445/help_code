<?php

namespace App\Providers;

use App\Models\JdBean;
use App\Models\JdCrazyJoy;
use App\Models\JdJdh;
use App\Models\JdJdzz;
use App\Models\LoadIP;
use Illuminate\Support\ServiceProvider;

class ModelServiceProvider extends ServiceProvider
{
    public function boot(){

    }
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //IP记录MODEL
        $this->app->bind('LoadIpModel' ,LoadIP::class);
        //京东种豆
        $this->app->bind('JdBeanModel' ,JdBean::class);
        //京东健康
        $this->app->bind('JdHealthModel' ,JdJdh::class);
        //京东赚赚
        $this->app->bind('JdZZModel' ,JdJdzz::class);
        //疯狂的京东
        $this->app->bind('JdCrazyJoyModel' ,JdCrazyJoy::class);
    }

    public function provides()
    {
        return [
            'JdBeanModel','LoadIpModel','JdZZModel','JdHealthModel','JdCrazyJoyModel'
        ];
    }
}
