<?php

namespace Foxytouch\User\Traits;

use Foxytouch\User\Models\Permission;

/**
 * Trait for Permission.
 *
 * @package \Foxytouch\User\Traits
 * @author Ivo Hradek <ivohradek@gmail.com>
 */
trait HasPermissions
{
    /**
     * Class have many {@link \Foxytouch\Models\Permission permissions}.
     *
     * @see \Foxytouch\Models\Permission
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function permissions()
    {
        return $this->belongsToMany(Permission::class)->withPivot('allow', 'deny');
    }

    /**
     * Attach {@link \Foxytouch\Models\Permission permission} to class.
     *
     * @see \Foxytouch\Models\Permission
     * @param $id permission's id
     */
    public function attachPermission($id)
    {
        $this->permissions()->attach($id);
    }

    /**
     * Detach {@link \Foxytouch\Models\Permission permission} from class.
     *
     * @see \Foxytouch\Models\Permission
     * @param $id permission's id
     */
    public function detachPermission($id)
    {
        $this->permissions()->detach($id);
    }
}
