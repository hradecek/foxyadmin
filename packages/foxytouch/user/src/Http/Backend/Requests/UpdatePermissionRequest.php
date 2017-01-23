<?php

namespace Foxytouch\User\Http\Backend\Requests;

use Foxytouch\User\Repositories\Contracts\PermissionRepository;
use Illuminate\Foundation\Http\FormRequest;

/**
 *
 * @package \Foxytouch\User\Http\Backend\Requests
 * @author Ivo Hradek <ivohradek@gmail.com>
 */
class UpdatePermissionRequest extends FormRequest
{
    /**
     * @var PermissionRepository
     */
    private $permission;

    /**
     * UpdatePermissionRequest constructor.
     *
     * @param PermissionRepository $permission
     */
    public function __construct(PermissionRepository $permission)
    {
        parent::__construct();
        $this->permission = $permission;
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $permissionTable = config('users.table.name.permission', 'permission');
        $permissionId = $this->getPermissionIdFromRouteParameter();

        return [
            'name'        => "required|max:255|unique:$permissionTable,name,$permissionId",
            'description' => 'max:255',
        ];
    }

    /** Todo: put to abstract request  */
    private function getPermissionIdFromRouteParameter()
    {
        $permissionName = $this->route()->getParameter('permission');
        $permission = $this->permission->findBy('name', $permissionName);

        return $permission->id;
    }
}
