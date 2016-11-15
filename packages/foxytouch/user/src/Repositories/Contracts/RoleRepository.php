<?php

namespace Foxytouch\User\Repositories\Contracts;

use Foxytouch\Repositories\Contracts\BaseRepository;

/**
 * Role repository contract.
 *
 * @see Foxytouch\User\Models\Role
 * @author Ivo Hradek <ivohradek@gmail.com>
 * @package Foxytouch\User\Repositories
 */
interface RoleRepository extends BaseRepository
{
    function findByName($name);
    
    function findAllByName(array $names);

    public function findAllModelsByNames($names);
}
