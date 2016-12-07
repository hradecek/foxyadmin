<?php

namespace Foxytouch\User\Http\Backend\Requests;

use Foxytouch\User\Repositories\Contracts\RoleRepository;

use Illuminate\Foundation\Http\FormRequest;

/**
 * TODO:
 * 
 * @package \Foxytouch\User\Http\Backend\Requests
 * @author Ivo Hradek <ivohradek@gmail.com>
 */
class UpdateRoleRequest extends FormRequest
{
    /**
     * @var RoleRepository
     */
    private $role;

    /**
     * UpdatePermissionRequest constructor.
     *
     * @param RoleRepository $role
     */
    public function __construct(RoleRepository $role)
    {
        parent::__construct();
        $this->role = $role;
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
        $roleTable = config('users.table.name.role', 'role');
        $roleId = $this->getRoleIdFromRouteParameter();

        return [
            'name'        => "required|max:255|unique:$roleTable,name,$roleId",
            'description' => 'max:255',
        ];
    }

    /** Todo: put to abstract request  */
    private function getRoleIdFromRouteParameter()
    {
        $roleName = $this->route()->getParameter('role');
        $role = $this->role->findBy('name', $roleName);

        return $role->id;
    }
}
