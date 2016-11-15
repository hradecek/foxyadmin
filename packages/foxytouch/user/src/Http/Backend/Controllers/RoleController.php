<?php

namespace Foxytouch\User\Http\Backend\Controllers;

use Foxytouch\Http\Backend\Controllers\Controller;

use Foxytouch\User\Repositories\Contracts\RoleRepository;
use Foxytouch\User\Http\Backend\Requests\CreateRoleRequest;
use Foxytouch\User\Http\Backend\Requests\UpdateRoleRequest;

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
     * RoleController constructor.
     *
     * @param RoleRepository $role
     */
    public function __construct(RoleRepository $role)
    {
        $this->role = $role;
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
        return view('users::backend.roles.create');
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

        return view('users::backend.roles.edit', compact('role'));
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
        return !Request::ajax() ?
            Redirect::route('auth.role.index')
                ->with('success', $message) :
            response()->json([
                'id' => $id, 'success' => $message
            ]);
    }
}
