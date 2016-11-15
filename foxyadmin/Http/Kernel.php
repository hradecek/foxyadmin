<?php

namespace Foxytouch\Http;

use Foxytouch\Http\Backend\Middleware\Authenticate;
use Foxytouch\Http\Backend\Middleware\RedirectIfAuthenticated;

use Illuminate\Foundation\Http\Kernel as HttpKernel;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class Kernel extends HttpKernel
{
    /**
     * The application's global HTTP middleware stack.
     *
     * @var array
     */
    protected $middleware = [
        StartSession::class,
        ShareErrorsFromSession::class,
    ];

    /**
     * The application's route middleware.
     *
     * @var array
     */
    protected $routeMiddleware = [
        'auth'       => Authenticate::class,
        'guest'      => RedirectIfAuthenticated::class,
    ];
}
