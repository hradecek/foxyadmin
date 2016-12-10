<?php

function articleThumb($article)
{
    return ($article->thumb_uri) ?: config()->get('article.default_thumb');
}
