<?php

namespace Foxytouch\Page;

use Foxytouch\Page\Models\Page;
use Foxytouch\Page\Repositories\Contracts\PageRepository;
use Foxytouch\Page\Repositories\Eloquent\EloquentPageRepository;
use Foxytouch\Page\Http\Backend\Controllers\PageController as BackendPageController;
use Foxytouch\Page\Http\Frontend\Controllers\PageController as FrontendPageController;

use Foxytouch\Providers\PackageServiceProvider;

use Illuminate\Support\Facades\Log;

/**
 * Page Service Provider
 * 
 * @package Foxytouch\Page
 * @author Ivo Hradek, <ivohradek@gmail.com>
 */
class PageServiceProvider extends PackageServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        parent::boot();

        Log::info('[PageServiceProvider] Booting');

        $this->package(__DIR__, 'pages');
        $aliases = [
            'Form' => 'Collective\Html\FormFacade',
            'Html' => 'Collective\Html\HtmlFacade',
        ];
        $this->loadAliases($aliases);
        // $this->loadViewsFrom(__DIR__ . '/resources/views', 'pages');
        // $this->loadFrontendViewsFrom('pages');
        // $this->loadTranslationsFrom(__DIR__ . '/resources/lang', 'pages');
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        Log::info('[PageServiceProvider] Registering');
        Log::info('[UserServiceProvider] Binding');
        $this->app->bind(PageRepository::class, function() {
            return new EloquentPageRepository(new Page);
        });
        
        $this->loadRoutes();

        $classes = [
            Page::class,
            BackendPageController::class,
            FrontendPageController::class
        ];
        $this->makeClasses($classes);
    }

    private function loadRoutes()
    {
        include __DIR__ . '/Http/Backend/routes.php';
        include __DIR__ . '/Http/Frontend/routes.php';
    }
}

