<?php

namespace Foxytouch\User\Http\Backend\Controllers;

use Foxytouch\Http\Backend\Controllers\Controller;
use Foxytouch\User\Repositories\Contracts\UserRepository;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;

/**
 * Controller handling <b>authentication</b>.
 *
 * @see \Foxytouch\User\Models\User
 * @package \Foxytouch\User\Http\Backend\Controllers
 * @author Ivo Hradek <ivohradek@gmail.com>
 */
class AuthController extends Controller
{
    use AuthenticatesAndRegistersUsers;

    /**
     * @var UserRepository
     */
    private $user;

    /**
     * @var string - authenticated property
     */
    protected $username = 'username';

    /**
     * Where to redirect users after login / registration.
     *
     * @var string
     */
    // protected $redirectPath = 'auth.article.index'; /* TODO: Dashboard, should not be tied to article module */

    /**
     * AuthController constructor.
     *
     * @param UserRepository $user
     */
    public function __construct(UserRepository $user)
    {
        $this->user = $user;
    }

    /**
     * Send the response after the user was authenticated.
     *
     * @return \Illuminate\Http\Response
     */
    protected function authenticated()
    {
        $this->user->updateAfterLogin();

        Log::info('[AuthController] User ' . Auth::user()->username . ' has been logged in');
        return Redirect::route($this->redirectPath())
                       ->with('success', trans('users::auth.success_login'));
    }

    /**
     * Log the user out of the application.
     *
     * @return \Illuminate\Http\Response
     */
    public function getLogout()
    {
        $username = Auth::user()->username;

        Auth::logout();
        $this->user->updateAfterLogout();

        Log::info('[AuhtController] User ' . $username . ' has been logged out');
        return Redirect::route('auth.login')
                       ->with('success', trans('users::auth.success_logout'));
    }

    /**
     * Log the user in of the application.
     *
     * @return \Illuminate\Http\Response
     */
    public function getLogin()
    {
        return view('users::backend.auth.login');
    }
}
