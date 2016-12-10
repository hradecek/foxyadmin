<?php

namespace Foxytouch\Article\Repositories\Contracts;

use Foxytouch\Repositories\Contracts\BaseRepository;

/**
 * Article repository contract.
 *
 * @see Foxytouch\Article\Models\Article
 * @package Foxytouch\User\Repositories\Contracts
 * @author Ivo Hradek <ivohradek@gmail.com>
 */
interface ArticleRepository extends BaseRepository
{
    function findAllWithoutCategory();
}
