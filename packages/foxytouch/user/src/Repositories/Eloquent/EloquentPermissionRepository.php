<?php

namespace Foxytouch\User\Repositories\Eloquent;

use Foxytouch\Repositories\Eloquent\EloquentBaseRepository;
use Foxytouch\User\Repositories\Contracts\PermissionRepository;

/**
 * Eloquent implementation of Permission Repository.
 *
 * @see Foxytouch\User\Repositories\Contracts\PermissionRepository
 * @package Foxytouch\User\Repositories\Eloquent
 * @author Ivo Hradek <ivohradek@gmail.com>
 */
class EloquentPermissionRepository extends EloquentBaseRepository implements PermissionRepository
{ }