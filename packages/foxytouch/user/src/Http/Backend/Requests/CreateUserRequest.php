<?php

namespace Foxytouch\User\Http\Backend\Requests;

use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Http\FormRequest;

/**
 * <p>
 * Create {@link \Foxytouch\Models\User user} form request. Only user with
 * {@link \Foxytouch\Models\Permission permissions} can create user.
 * </p>
 * <p>
 * Validation criteria:
 * <ul>
 *   <li><i>username</i> is <b>required</b>,</li>
 *   <li><i>username</i> must be unique per user,</li>
 *   <li><i>username</i> maximum size is 60,</li>
 *   <li><i>email</i> is <b>required</b>,</li>
 *   <li><i>email</i> must be unique per user,</li>
 *   <li><i>email</i> must be in email format,</li>
 *   <li><i>email</i> maximum size is 255 characters,</li>
 *   <li><i>password</i> must be same as confirmation password,</li>
 *   <li><i>password</i> maximum size is 255 characters,</li>
 *   <li><i>profile_picture</i> must be in supported image format,</li>
 *   <li><i>role</i> must exists in the system.</li>
 * </ul>
 * </p>
 *
 * @see \Foxytouch\User\Models\User
 * @package \Foxytouch\User\Http\Backend\Requests
 * @author Ivo Hradek <ivohradek@gmail.com>
 */
class CreateUserRequest extends FormRequest
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
        $roleTable = config('users.table.name.role', 'role');
        $userTable = config('users.table.name.user', 'user');
        
        return [
            'username'        => "required|max:255|unique:$userTable",
            'email'           => "required|email|max:255|unique:$userTable",
            'password'        => 'required|confirmed|max:255',
            'role'            => "exists:$roleTable,name",
            'profile_picture' => 'image'
        ];
    }
}
