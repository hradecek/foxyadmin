<?php

namespace Foxytouch\Article\Repositories\Contracts;

use Foxytouch\Repositories\Contracts\BaseRepository;

/**
 * Article repository contract.
 *
 * @see Foxytouch\Article\Models\Article
 * @author Ivo Hradek <ivohradek@gmail.com>
 * @package Foxytouch\User\Repositories
 */
interface ArticleRepository extends BaseRepository
{
    function findAllWithoutCategory();

    function findPublished($published);
}
