<?php

namespace Foxytouch\Article;

use Foxytouch\Article\Http\Backend\Controllers\ArticleController;
use Foxytouch\Article\Http\Backend\Controllers\CategoryController;

use Foxytouch\Article\Models\Article;
use Foxytouch\Article\Models\ArticleStatus;
use Foxytouch\Article\Models\Category;

use Foxytouch\Article\Repositories\Contracts\ArticleRepository;
use Foxytouch\Article\Repositories\Contracts\ArticleStatusRepository;
use Foxytouch\Article\Repositories\Contracts\CategoryRepository;
use Foxytouch\Article\Repositories\Eloquent\EloquentArticleRepository;
use Foxytouch\Article\Repositories\Eloquent\EloquentArticleStatusRepository;
use Foxytouch\Article\Repositories\Eloquent\EloquentCategoryRepository;

use Foxytouch\Providers\PackageServiceProvider as ServiceProvider;

use Illuminate\Support\Facades\Log;

/**
 * Article Service Provider.
 *
 * @author Ivo Hradek <ivohradek@gmail.com>
 */
class ArticleServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        parent::boot();

        Log::info('[ArticleServiceProvider] Booting');

        $this->package(__DIR__, 'articles');
        $aliases = [
            'Form' => 'Collective\Html\FormFacade',
            'Html' => 'Collective\Html\HtmlFacade',
        ];
        $this->loadAliases($aliases);
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        Log::info('[ArticleServiceProvider] Registering');
        Log::info('[ArticleServiceProvider] Binding');
        $this->app->bind(ArticleRepository::class, function() {
            return new EloquentArticleRepository(new Article);
        });
        $this->app->bind(CategoryRepository::class, function() {
            return new EloquentCategoryRepository(new Category);
        });
        $this->app->bind(ArticleStatusRepository::class, function() {
            return new EloquentArticleStatusRepository(new ArticleStatus);
        });

        Log::info('[ArticleServiceProvider] Setting up routes');
        $this->loadRoutes();

        $classes = [
            Article::class,
            Category::class,
            ArticleStatus::class,
            ArticleController::class,
            CategoryController::class,
        ];
        $this->makeClasses($classes);
    }

    private function loadRoutes()
    {
        require_once __DIR__ . '/Http/Backend/routes.php';
    }
}

