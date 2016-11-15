<?php

namespace Foxytouch\User\Http\Backend\Controllers;

use Foxytouch\Http\Backend\Controllers\Controller;
use Foxytouch\User\Http\Backend\Requests\CreatePermissionRequest;
use Foxytouch\User\Http\Backend\Requests\UpdatePermissionRequest;
use Foxytouch\User\Repositories\Contracts\PermissionRepository;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;

/**
 * Permission controller.
 * 
 * @package \Foxytouch\User\Http\Backend\Controllers
 * @author Ivo Hradek <ivohradek@gmail.com>
 */
class PermissionController extends Controller
{
    /**
     * @var PermissionRepository
     */
    private $permission;

    /**
     * PermissionController constructor.
     * 
     * @param PermissionRepository $permission
     */
    public function __construct(PermissionRepository $permission)
    {
        $this->permission = $permission;
    }

    /**
     * Show all permissions.
     * 
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $permissions = $this->permission->all();

        return view('users::backend.permissions.index', compact('permissions'));
    }

    /**
     * Show specific permission by its name.
     * 
     * @param string $name permission name to be shown
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show($name)
    {
        $permission = $this->permission->findBy('name', $name);
        
        return view('users::backend.permissions.show', compact('permission'));
    }

    /**
     * Show permission view for creation.
     * 
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        return view('users::backend.permissions.create');
    }

    /**
     * Store created permission.
     * 
     * @param CreatePermissionRequest $request form request
     */
    public function store(CreatePermissionRequest $request)
    {
        $this->permission->create($request->all());
        Log::info('New permission: ' . $request->name . ' has been created.');
        
        return Redirect::route('auth.permission.index')
                       ->with('success', trans('general.success_create'));
    }

    /**
     * Show edit form for a specific permission.
     *
     * @param string $name permission's name
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit($name)
    {
        $permission = $this->permission->findBy('name', $name);

        return view('users::backend.permissions.edit', compact('permission'));
    }

    /**
     * Update permission.
     * 
     * @param $name
     * @param UpdatePermissionRequest $request
     * @return mixed
     */
    public function update($name, UpdatePermissionRequest $request)
    {
        $permission = $this->permission->findBy('name', $name);
        
        $this->permission->update($permission, $request->all()); /*TODO: Check success */

        Log::info("Permission: {$permission->name} has been updated.");
        return Redirect::route('auth.permission.index')
                       ->with('success', trans('general.success_update'));
    }

    /**
     * Destroy permission.
     * 
     * @param string $name permission's name to be deleted
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($name)
    {
        $permission = $this->permission->findBy('name', $name);
        $id = $permission->id;
        $this->permission->destroy($permission); /*TODO: Check success */

        $message = trans('general.success_destroy');
        return !Request::ajax() ?
            Redirect::route('auth.permission.index')
                    ->with('success', $message) :
            response()->json([
                'id' => $id, 'success' => $message
            ]);
    }

}
