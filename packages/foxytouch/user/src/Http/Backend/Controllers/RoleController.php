<?php

namespace Foxytouch\User\Http\Backend\Controllers;

use Foxytouch\Http\Backend\Controllers\Controller;

use Foxytouch\User\Http\Backend\Requests\CreateRoleRequest;
use Foxytouch\User\Http\Backend\Requests\UpdateRoleRequest;

use Foxytouch\User\Repositories\Contracts\RoleRepository;
use Foxytouch\User\Repositories\Contracts\PermissionRepository;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Redirect;

/**
 * Role controller.
 *
 * @package \Foxytouch\User\Http\Backend\Controllers
 * @author Ivo Hradek <ivohradek@gmail.com>
 */
class RoleController extends Controller
{
    /**
     * @var RoleRepository
     */
    private $role;

    /**
     * @var PermissionRepository
     */
    private $permission;

    /**
     * RoleController constructor.
     *
     * @param RoleRepository $role
     * @param PermissionRepository $permission
     */
    public function __construct(RoleRepository $role, PermissionRepository $permission)
    {
        $this->role = $role;
        $this->permission = $permission;
    }

    /**
     * Show all roles.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $roles = $this->role->all();

        return view('users::backend.roles.index', compact('roles'));
    }

    /**
     * Show specific role by its name.
     *
     * @param string $name role's name to be shown
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show($name)
    {
        $role = $this->role->findBy('name', $name);

        return view('users::backend.roles.show', compact('role'));
    }

    /**
     * Show role view for creation.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        $permissions = $this->permission->all(['id', 'name'])->keyBy('id')->map(function ($item) {
            return $item['name'];
        })->toArray();

        return view('users::backend.roles.create', compact('permissions'));
    }

    /**
     * Store created role.
     *
     * @param CreateRoleRequest $request form request
     */
    public function store(CreateRoleRequest $request)
    {
        $this->role->create($request->all());
        Log::info('New role: ' . $request->name . ' has been created.');

        return Redirect::route('auth.role.index')
            ->with('success', trans('general.success_create'));
    }

    /**
     * Show edit form for a specific role.
     *
     * @param string $name role's name
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit($name)
    {
        $role = $this->role->findBy('name', $name);
        $map = function ($p) { return $p['name']; };
        $permissions = $this->permission->all(['id', 'name'])->keyBy('id')->map($map)->toArray();
        $rolesPermissions = $this->permission->getForModel($role)->keyBy('id')->map($map)->toArray();

        return view('users::backend.roles.edit', compact('role', 'permissions', 'rolesPermissions'));
    }

    /**
     * Update role.
     *
     * @param $name
     * @param UpdateRoleRequest $request
     * @return mixed
     */
    public function update($name, UpdateRoleRequest $request)
    {
        $role = $this->role->findBy('name', $name);

        $this->role->update($role, $request->all()); /*TODO: Check success */

        Log::info("Permission: {$role->name} has been updated.");
        return Redirect::route('auth.role.index')
            ->with('success', trans('general.success_update'));
    }

    /**
     * Destroy role.
     *
     * @param string $name role's name to be deleted
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($name)
    {
        $role = $this->role->findBy('name', $name);
        $id = $role->id;
        $this->role->destroy($role); /*TODO: Check success */

        $message = trans('general.success_destroy');
        $ajaxResponse = response()->json([
            'id' => $id, 'success' => $message
        ]);
        $redirect = Redirect::route('auth.role.index')->with('success', $message);

        return Request::ajax() ? $ajaxResponse : $redirect;
    }
}
