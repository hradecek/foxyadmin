<?php

namespace Foxytouch\User\Http\Backend\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * TODO:
 *
 * @package \Foxytouch\User\Http\Backend\Requests
 * @author Ivo Hradek <ivohradek@gmail.com>
 */
class CreatePermissionRequest extends FormRequest
{
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

        return [
            'name'        => "required|max:255|unique:$permissionTable",
            'description' => 'max:255',
        ];
    }
}
