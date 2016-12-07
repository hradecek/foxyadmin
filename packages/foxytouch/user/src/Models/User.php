<?php

namespace Foxytouch\User\Models;

use Foxytouch\User\Traits\HasPermissions;

use Foxytouch\User\Traits\HasRoles;
use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;

/**
 * <p>
 * Model representing a single user of a system.
 * </p>
 *
 * @package Foxytouch\User\Models
 * @author Ivo Hradek <ivohradek@gmail.com>
 */
class User extends Model implements AuthenticatableContract
{
    use Authenticatable, HasPermissions, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'username',
        'email',
        'profile_picture_uri',
        'online',
        'attempt',
        'ip_address',
        'sign_in_count'
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['password', 'remember_token'];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['last_sign_in'];

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = true;

    /**
     * Get the table associated with the model.
     *
     * @return string
     */
    public function getTable()
    {
        return config('users.table.name.user', 'user');
    }

    /**
     * Set the user's email. Normalize with lowercase.
     *
     * @param string $email
     * @return string
     */
    public function setEmailAttribute($email)
    {
        $this->attributes['email'] = strtolower($email);
    }

    /**
     * Set the user's password, hashed immediately.
     *
     * @param  string $password
     * @return string
     */
    public function setPasswordAttribute($password)
    {
        $this->attributes['password'] = bcrypt($password);
    }
}
