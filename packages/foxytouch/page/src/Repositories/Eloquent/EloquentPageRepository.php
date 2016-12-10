<?php

namespace Foxytouch\Page\Repositories\Eloquent;


use Illuminate\Support\Facades\Auth;
use Foxytouch\Page\Repositories\Contracts\PageRepository;
use Foxytouch\Repositories\Eloquent\EloquentBaseRepository;

/**
 * Eloquent implementation of Page Repository.
 *
 * @see Foxytouch\Page\Repositories\Contracts\PageRepository
 * @package Foxytouch\User\Repositories\Eloquent
 * @author Ivo Hradek <ivohradek@gmail.com>
 */
class EloquentPageRepository extends EloquentBaseRepository implements PageRepository
{
    public function create(array $data)
    {
        $this->model->fill($data);
        $this->model->user()->associate(Auth::user());
        $this->model->save();
        
        return $this->model;
    }
}
