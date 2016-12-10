<?php

namespace Foxytouch\Article\Repositories\Eloquent;

use Foxytouch\Repositories\Eloquent\EloquentBaseRepository;

use Foxytouch\Article\Repositories\Contracts\CategoryRepository;

/**
 * Eloquent implementation of Category Repository.
 *
 * @see Foxytouch\User\Repositories\Contracts\CategoryRepository
 * @package Foxytouch\User\Repositories\Eloquent
 * @author Ivo Hradek <ivohradek@gmail.com>
 */
class EloquentCategoryRepository extends EloquentBaseRepository implements CategoryRepository
{ }
