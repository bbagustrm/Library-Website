<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;



class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // $this->app['router']->aliasMiddleware('admin', \App\Http\Middleware\AdminMiddleware::class);
        // $this->app['router']->aliasMiddleware('staff', \App\Http\Middleware\StaffMiddleware::class);
        // $this->app['router']->aliasMiddleware('user', \App\Http\Middleware\UserMiddleware::class);
    }
}
