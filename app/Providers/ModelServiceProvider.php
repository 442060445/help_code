<?php

namespace App\Providers;

use App\Models\JdBean;
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
        //京东种豆
        $this->app->bind('JdBeanModel' ,JdBean::class);
    }

    public function provides()
    {
        return [
            'JdBeanModel'
        ];
    }
}
