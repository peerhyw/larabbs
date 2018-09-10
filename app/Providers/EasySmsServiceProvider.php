<?php

namespace App\Providers;

use Overtrue\EasySms\EasySms;
use Illuminate\Support\ServiceProvider;

class EasySmsServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(EasySms::class,function($app){
            return new EasySms(config('easysms'));
        });

        //alias:别名 通过EasySms::class 或 easysms 进行app()注入都可以
        $this->app->alias(EasySms::class,'easysms');
    }
}
