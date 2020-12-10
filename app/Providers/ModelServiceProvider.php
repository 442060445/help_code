<?php

namespace App\Providers;

use App\Models\JdBean;
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

    }

    public function provides()
    {
        return [
            'JdBeanModel','LoadIpModel'
        ];
    }
}
