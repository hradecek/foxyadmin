<?php

namespace Foxytouch\User\Http\Backend\Controllers;

use Foxytouch\Http\Backend\Controllers\Controller;

use Foxytouch\User\Http\Backend\Requests\CreateUserRequest;
use Foxytouch\User\Http\Backend\Requests\UpdateUserRequest;

use Foxytouch\User\Repositories\Contracts\RoleRepository;
use Foxytouch\User\Repositories\Contracts\UserRepository;
use Foxytouch\User\Repositories\Contracts\PermissionRepository;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Database\Eloquent\Collection;

/**
 * {@link \Foxytouch\User\Models\User User} controller.
 *
 * @author Ivo Hradek <ivohradek@gmail.com>
 * @package Foxytouch\User\Http\Backend\Controllers
 */
class UserController extends Controller
{
    /*
     * Path to default article thumb.
     */
    private $defaultProfilePicture;
    
    /**
     * @var UserRepository
     */
    private $user;

    /**
     * @var RoleRepository
     */
    private $role;

    /**
     * @var PermissionRepository
     */
    private $permission;

    /**
     * UserController constructor.
     *
     * @param UserRepository $user
     * @param RoleRepository $role
     * @param PermissionRepository $permission
     */
    public function __construct(UserRepository $user, RoleRepository $role, PermissionRepository $permission)
    {
        $this->user = $user;
        $this->role = $role;
        $this->permission = $permission;
        $this->defaultProfilePicture = config()->get('user.default_picture');
    }

    /**
     * List and summary information of all users.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $users = $this->user->all();
        
        return view('users::backend.users.index', compact('users'));
    }

    /**
     * Show {@link \Foxytouch\User\Models\User user's} information.
     *
     * @param $username
     * @return \Illuminate\View\View
     * @internal param User $user - user to show
     */
    public function show($username)
    {
        authorize('user', 0b0100);

        $user = $this->user->findByUsername($username);
        return view('users::backend.users.show', compact('user'));
    }
    
    /**
     * Show the form for creating a new {@link \Foxytouch\User\Models\User user}.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $map = function ($i) { return $i['name']; };
        $roles = $this->role->all(['id', 'name'])->keyBy('id')->map($map)->toArray();
        $permissions = $this->permission->all(['id', 'name'])->keyBy('id')->map($map)->toArray();

        return view('users::backend.users.create', compact('roles', 'permissions'));
    }

    /**
     * <p>
     * Store newly created {@link \Foxytouch\User\Models\User user}.
     * </p>
     *
     * <p>
     * If storing was successful, user will be redirected to list
     * of all {@link \Foxytouch\User\Models\User users}.
     * </p>
     *
     * @param \Foxytouch\User\Http\Backend\Requests\CreateUserRequest $request - form request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateUserRequest $request)
    {
        /* TODO: Do somethings about uploaded file, 
         *       - Repository is dependent on UploadedFile 
         *       - Work-around: pass a filename
         * */

        Log::info('New user: ' . $request->username . ' has been created');
        return Redirect::route('auth.user.index')
                       ->with('success', trans('general.success_create'));
    }

    /**
     * <p>
     * Delete specific {@link \Foxytouch\User\Models\User user}.
     * </p>
     * <p>
     * <i>Note:</i> If there is only one admin left,
     * user can't be deleted.
     * </p>
     *
     * @param $username
     * @return \Illuminate\Http\Response
     * @internal param User $user - user to delete
     */
    public function destroy($username)
    {
        authorize('user', 0b0001);

        $admins = $this->group->findByName('admin')->users;
        $user = $this->user->findByUsername($username);

        // Ensure there is at least one admin in the system
        if ($user->hasGroup(['admin']) && $admins->count() == 1) {
            return 'Last man standing';
        }
        $this->user->destroy($user);

        Log::info("User: {$user->username} has been removed");
        return Redirect::route('auth.user.index')
                       ->with('success', trans('users::user.success_delete'));
    }

    /**
     * Show edit form for specific {@link \Foxytouch\User\Models\User user}.
     *
     * @param $username
     * @return \Illuminate\View\View
     * @internal param User $user - user to edit.
     */
    public function edit($username)
    {
        $user = $this->user->findByUsername($username);

        authorizeWithOwnership($user->id, 'user', 0b0010);

        // Not only user groups, but all groups also
        $groupNames = $user->groups->map(function ($item) {
            return $item->name;
        })->toArray();
        $permissions = $this->permission->all();
        $groups = array_combine($groupNames, $groupNames);

        return view('users::backend.users.edit', compact('groups', 'permissions', 'user'));
    }

    /**
     * Update information of specific {@link \Foxytouch\User\Models\User user}.
     *
     * @param $username
     * @param UpdateUserRequest $request - form request
     * @return \Illuminate\Http\Response
     * @internal param User $user - user to update.
     */
    public function update($username, UpdateUserRequest $request)
    {
        $user = $this->user->findByUsername($username);

        authorizeWithOwnership($user->id, 'user', 0b0010);

        $groups = array_map(function ($r) { return $this->group->findByName($r); }, $request->group);
        $this->user->updateWithGroups($user, $request->all(), $groups);

        Log::info("User: {$user->username} has been updated");
        return Redirect::route('auth.user.index')
                       ->with('success', trans('users::user.success_update'));
    }

    /**
     * Group
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function groupsPermissions(Request $request)
    {
        $groupNames = explode(',', $request->groups);
        $user = $this->user->findByUsername($request->username);
        $groups = $this->group->findAllByName($groupNames);
        $result = $user ? $this->unionPermissions($groups, $user->permissions) : $this->unionPermissions($groups);

        return view('users::backend.users.forms.permissions', ['permissions' => $result]);
    }

    private function unionPermissions($groups, $userPermissions = null)
    {
        $result = new Collection;
        $permissions = $this->permission->all();
        dd($permissions);
        foreach ($permissions as $permission) {
            $id = $permission->id;
            $allows = ($userPermissions) ? $userPermissions->find($id)->pivot->allow : 0b0000;
            $denies = ($userPermissions) ? $userPermissions->find($id)->pivot->deny : 0b0000; 
            if ($groups) {
                foreach ($groups as $group) {
                    if ($group->permissions->find($id)) {
                        $allows |= $group->permissions->find($id)->pivot->allow;
                        $denies |= $group->permissions->find($id)->pivot->deny;
                    }
                }
            }
            $result->put($permission->name, ['name' => $permission->name, 'allow' => $allows, 'deny' => $denies]);
        }

        return $result;
    }

    /**
     */
    public function destroyPicture()
    {
        $id = Input::get('id');
        if ($id != 'undefined') {
            $user = $this->user->find($id);
            $this->user->destroyUserImage($user);
        };
        echo asset($this->defaultProfilePicture);
    }
}
