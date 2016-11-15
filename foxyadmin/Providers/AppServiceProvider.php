<?php

namespace Foxytouch\Providers;


use Illuminate\Support\Facades\Log;
use Illuminate\Support\ServiceProvider;

/**
 * Class AppServiceProvider
 * 
 * @author Ivo Hradek <ivohradek@gmail.com>
 * @package Foxytouch\Providers
 */
class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    { }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        // $this->app->registerDeferredProvider(TestServiceProvider::class);
        $this->registerPackageProviders();
    }
    
    private function registerPackageProviders() {
        /* TODO: Call register only on change */
        /* TODO: Check success or errors */
        Log::info('[AppServiceProvider] Registering packages');
        $packageProviders = config()->get('packages');
        foreach ($packageProviders as $provider) {
             /* TODO: provider is now package */
             $composer = json_decode(file_get_contents($provider['base_path'] . '/composer.json'), true);
             /* TODO: Load PSR-4 */
             foreach ($composer['autoload']['psr-4'] as $namespace => $src) {
                 $this->app->loader->setPsr4($namespace, $provider['base_path'] . '/' .$src);
             }
             // $this->app->loader->setPsr4(, )
             // $loader->setPsr4("Plugins\\", __DIR__ . "/../plugins");
             
            $this->app->registerDeferredProvider($provider['service_provider']);
            Log::info($provider['service_provider'] . ' was registered');
        }
    }
}
