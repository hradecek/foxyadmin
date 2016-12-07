<?php

namespace Foxytouch\User\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * <p>Model representing a permission in the system.</p>
 *
 * @package Foxytouch\User\Models
 * @author Ivo Hradek <ivohradek@gmail.com>
 */
class Permission extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'description']; /* TODO: Constants */

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * Permissions can belong to many users.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function users()
    {
        return $this->belongsToMany(User::class);
    }

    /**
     * Get the table associated with the model.
     *
     * @return string
     */
    public function getTable()
    {
        return config('users.table.name.permission', 'permission');
    }
}
