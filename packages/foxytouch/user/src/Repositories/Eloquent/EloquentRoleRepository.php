<?php

namespace Foxytouch\User\Repositories\Eloquent;

use Foxytouch\User\Repositories\Contracts\RoleRepository;
use Foxytouch\Repositories\Eloquent\EloquentBaseRepository;

use Illuminate\Database\Eloquent\Collection;

/**
 * Eloquent implementation of Role Repository.
 *
 * @see Foxytouch\User\Repositories\Contracts\RoleRepository
 * @package Foxytouch\User\Repositories\Eloquent
 * @author Ivo Hradek <ivohradek@gmail.com>
 */
class EloquentRoleRepository extends EloquentBaseRepository implements RoleRepository
{
    public function findByName($name)
    {
        return $this->model->byName($name)->first();
    }
    
    public function findAllByName(array $names)
    {
        $items = [];
        foreach ($names as $name) {
            array_push($items, $this->model->byName($name)->first());
        }
        return (!$items[0]) ? null : Collection::make($items);
    }

    public function findAllModelsByNames($names)
    {
        if (empty($names)) {
            return [];
        }
        return array_map(function ($r) { return $this->findByName($r); }, $names);
    }
}
