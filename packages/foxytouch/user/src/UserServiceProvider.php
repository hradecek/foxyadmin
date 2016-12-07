<?php

namespace Foxytouch\User;

use Foxytouch\User\Models\User;
use Foxytouch\User\Models\Role;
use Foxytouch\User\Models\Permission;
use Foxytouch\User\Http\Backend\Controllers\AuthController;
use Foxytouch\User\Http\Backend\Controllers\UserController;
use Foxytouch\User\Http\Backend\Controllers\PermissionController;

use Foxytouch\User\Repositories\Contracts\RoleRepository;
use Foxytouch\User\Repositories\Contracts\UserRepository;
use Foxytouch\User\Repositories\Contracts\PermissionRepository;

use Foxytouch\User\Repositories\Eloquent\EloquentUserRepository;
use Foxytouch\User\Repositories\Eloquent\EloquentRoleRepository;
use Foxytouch\User\Repositories\Eloquent\EloquentPermissionRepository;

use Foxytouch\Providers\PackageServiceProvider as ServiceProvider;

use Illuminate\Support\Facades\Log;
use Illuminate\Foundation\AliasLoader;

/**
 * User Service Provider.
 *
 * @author Ivo Hradek <ivohradek@gmail.com>
 */
class UserServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        parent::boot();
        
        Log::info('[UserServiceProvider] Booting');
        
        $this->package(__DIR__, 'users');
        $aliases = [
            'Form' => 'Collective\Html\FormFacade',
            'Html' => 'Collective\Html\HtmlFacade',
            'Image' => 'Intervention\Image\Facades\Image'
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
        Log::info('[UserServiceProvider] Registering');
        $this->app->register('Intervention\Image\ImageServiceProvider');
        Log::info('[UserServiceProvider] Binding');
        $this->app->bind(UserRepository::class, function() {
            return new EloquentUserRepository(new User);
        });
        $this->app->bind(RoleRepository::class, function() {
            return new EloquentRoleRepository(new Role);
        });
        $this->app->bind(PermissionRepository::class, function() {
            return new EloquentPermissionRepository(new Permission);
        });
        
        Log::info('[UserServiceProvider] Setting up routes');
        include __DIR__ . '/Http/Backend/routes.php';
        
        $classes = [
            Role::class, 
            User::class, 
            Permission::class,
            AuthController::class, 
            UserController::class,
            PermissionController::class
        ];
        $this->makeClasses($classes);
    }
}

