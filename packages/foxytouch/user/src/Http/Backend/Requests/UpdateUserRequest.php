<?php

namespace Foxytouch\User\Http\Backend\Requests;

use Foxytouch\User\Repositories\Contracts\UserRepository;

use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Http\FormRequest;

/**
 * <p>
 * Update {@link \Foxytouch\User\Models\User user} form request. Only user with
 * {@link \Foxytouch\User\Models\Permission permissions} can update user.
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
class UpdateUserRequest extends FormRequest
{
    /**
     * @var UserRepository
     */
    private $user;

    /**
     * UpdateUserRequest constructor.
     *
     * @param UserRepository $user
     */
    public function __construct(UserRepository $user)
    {
        parent::__construct();
        $this->user = $user;
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
        $userTable = config('users.table.name.user', 'user');
        $roleTable = config('users.table.name.role', 'role');
        $userId = $this->getUserIdFromRouteParameter();

        return [
            'username'        => "required|max:60|unique:$userTable,username,$userId",
            'email'           => "required|email|max:255|unique:$userTable,email,$userId",
            'password'        => 'sometimes|confirmed|max:255',
            'role'            => "exists:$roleTable,name",
            'profile_picture' => 'image'
        ];
    }

    private function getUserIdFromRouteParameter()
    {
        $userName = $this->route()->getParameter('user');
        $user = $this->user->findBy('username', $userName);

        return $user->id;
    }
}
