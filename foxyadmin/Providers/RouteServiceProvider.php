<?php

namespace Foxytouch\Providers;

use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Log;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{
    
    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @param \Illuminate\Routing\Router $router
     * @return void
     */
    public function boot(Router $router)
    {
        parent::boot($router);
        Log::info('[RouteServiceProvider] Booting.');
    }

    /**
     * Define the routes for the application.
     *
     * @param  \Illuminate\Routing\Router  $router
     * @return void
     */
    public function map(Router $router)
    {
        Log::info('[RouteServiceProvider] Registering main routes.');
        require app_path('Http/Frontend/routes.php');
    }
    
    public function register()
    {
        Log::info('[RouteServiceProvider] Registering.');
    }
}
