<?php

namespace Foxytouch\Providers;

use Illuminate\Support\ServiceProvider;

/**
 * Class PackageServiceProvider
 * 
 * @author Ivo Hradek <ivohradek@gmail.com>
 * @package Foxytouch\Providers
 */
abstract class PackageServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = true;
    
    /**
     * Path to views, relative to the resource path.
     * 
     * @var string
     */
    protected $viewsPath = 'resources/views';

    /**
     * Path to translations, relative to the resource path.
     * 
     * @var string
     */
    protected $translationsPath = 'resources/lang';

    /**
     * Path to configs, relative to the resource path.
     *
     * @var string
     */
    protected $configsPath = 'config';

    /**
     * Path to publics, relative to the resource path.
     *
     * @var string
     */
    protected $publicPath = 'public';
    
    /**
     * Path to seeds, relative to the resource path.
     * 
     * @var string
     */
    protected $seedsPath = 'database/seeds';

    /**
     * Path to migrations, relative to the resource path.
     *
     * @var string
     */
    protected $migrationsPath = 'database/migrations';

    /**
     * Register vendor's namespaces and resources.
     *
     * @param $path
     * @param $namespace
     */
    public function package($path, $namespace = null)
    {
        $configPath = $path . DIRECTORY_SEPARATOR . $this->configsPath;
        $this->loadConfigs($configPath, $namespace);
        
        $viewsPath = $path . DIRECTORY_SEPARATOR . $this->viewsPath;
        $this->loadAndPublishViewsFrom($viewsPath, $namespace);
        
        $translationPath = $path . DIRECTORY_SEPARATOR . $this->translationsPath;
        $this->loadTranslationsFrom($translationPath, $namespace);
        
        $publicPath = $path . DIRECTORY_SEPARATOR . $this->publicPath;
        $this->publishesPublic($publicPath, $namespace);
        
        $this->publishesMigrations();

        // $this->loadFrontendViewsFrom($namespace)
    }

    /**
     * Load frontend views.
     * 
     * @param $namespace
     */
    protected function loadFrontendViewsFrom($namespace)
    {
        $this->loadViewsFrom($this->app->basePath() . '/web/', $namespace);
    }

    /**
     * Load and publish package's views.
     *
     * @param string $path
     * @param string $namespace
     */
    protected function loadAndPublishViewsFrom($path, $namespace)
    {
        if ($this->app['files']->isDirectory($path)) {
            $this->loadViewsFrom($path, $namespace);
        }
    }

    /**
     * Load configs from local package.
     *
     * @param $namespace
     * @param $path
     */
    protected function loadConfigs($path, $namespace)
    {
        $configsToPublish = [];
        $files = $this->app['files']->files(realpath($path));

        foreach ($files as $file) {
            $this->mergeConfigFrom($file, $namespace);
            $configsToPublish = array_merge($configsToPublish, [$file => config_path($namespace . '.php')]);
        }
    }

    /**
     * Publish migrations.
     */
    protected function publishesMigrations()
    {
        if (is_dir($this->migrationsPath)) {
            $this->publishes([$this->migrationsPath => $this->app->databasePath() . '/migrations'], 'migrations');
        }
    }

    /**
     * Publish publics.
     */
    protected function publishesPublic($path, $namespace)
    {
        if (is_dir($this->publicPath)) {
            $this->publishes([$path => public_path() . DIRECTORY_SEPARATOR . $namespace], 'public');
        }
    }

    /**
     * Publish seeds.
     */
    protected function publishesSeeds()
    {
        if (is_dir($this->seedsPath)) {
            $this->publishes([$this->seedsPath => $this->app->databasePath() . '/seeds'], 'seeds');
        }
    }
    
    protected function makeClasses(array $classes)
    {
        foreach ($classes as $class) {
            $this->app->make($class);
        }
    }
}
