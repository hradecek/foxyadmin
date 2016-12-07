<?php

namespace Foxytouch\User\Traits;

use Foxytouch\User\Models\Role;

/**
 * Trait for {@link \Foxytouch\User\Models\Role Role}.
 *
 * @package Foxytouch\User\Traits
 * @author Ivo Hradek <ivohradek@gmail.com>
 */
trait HasRoles
{
    /**
     * Class belongs to many {@link \Foxytouch\User\Models\Role roles}.
     *
     * @see \Foxytouch\User\Models\Role
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }

    /**
     * Attach {@link \Foxytouch\User\Models\Role roles} to a class.
     *
     * @param array $rolesIds
     */
    public function attachRoles(array $rolesIds)
    {
        $this->roles()->sync($rolesIds, false);
    }

    /**
     * Detach {@link \Foxytouch\User\Models\Role roles} from class.
     *
     * @see \Foxytouch\User\Models\role
     * @param array $rolesIds
     */
    public function detachRoles(array $rolesIds)
    {
        $this->roles()->sync($rolesIds, true);
    }
}
