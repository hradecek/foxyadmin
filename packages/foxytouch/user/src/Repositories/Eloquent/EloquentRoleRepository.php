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
    public function create(array $data)
    {
        $model = $this->model->create($data);
        $this->sync($model, $data);

        return $model;
    }

    public function update($model, array $data)
    {
        $model->update($data);
        $this->sync($model, $data);
        
        return $model;
    }

    private function sync($model, array $data)
    {
        if (array_key_exists('permission', $data) && is_array($data['permission'])) {
            $model->permissions()->sync($data['permission']);
        }
    }
}
