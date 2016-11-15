<?php

namespace Foxytouch\User\Models;

use Foxytouch\User\Traits\HasPermissions;

use Illuminate\Database\Eloquent\Model;

/**
 * <p>
 * Model representing a single role in the system.
 * Role has its <i>unique</i> name and optional <i>description</i>.
 * Role could have zero or more {@link \Foxytouch\User\Models\Permission permissions}.
 * </p>
 *
 * @see \Foxytouch\User\Traits\HasPermission
 * @see \Foxytouch\User\Models\User
 * @see \Foxytouch\User\Models\Permission
 * @package \Foxytouch\User\Models
 * @author Ivo Hradek <ivohradek@gmail.com>
 */
class Role extends Model
{
    use HasPermissions;

    /**
     * Associated table.
     *
     * @var string
     */
    protected $table = 'role'; /* TODO: change name; possibly to config */

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'description'];

    /**
     * Get the table associated with the model.
     *
     * @return string
     */
    public function getTable()
    {
        return config('users.table.name.role', 'role');
    }
}
