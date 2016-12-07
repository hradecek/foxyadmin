<?php

namespace Foxytouch\Article\Models;

use Foxytouch\User\Models\User;

use Illuminate\Database\Eloquent\Model;

/**
 * <p>
 * Model representing article or article made by an {@link \Foxytouch\user\Models\User user}.
 * Every user could have more articles, but a single article is always
 * associated with a single user.
 * </p>
 * 
 * <p>
 * Article could have one {@link \Foxytouch\Article\Models\ArticleStatus status}, which determines
 * if the article is in the state of <i>draft</i> or already <i>published</i> etc.
 * </p>
 *
 * <p>
 * Article belongs to exactly one {@link \Foxytouch\Article\Models\Category category}.
 * </p>
 *
 * @see \Foxytouch\User\Models\User
 * @see \Foxytouch\Article\Models\Category
 * @see \Foxytouch\Article\Models\ArticleStatus
 * @package \Foxytouch\Article\Models
 * @author Ivo Hradek <ivohradek@gmail.com>
 */
class Article extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'slug',
        'title',
        'content',
        'thumb_uri'
    ];

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = true;

    /**
     * Article's {@link \Foxytouch\User\Models\User author}.
     *
     * @see \Foxytouch\User\Models\User
     * @return \Foxytouch\User\Models\User
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Article has exactly one {@link \Foxytouch\Article\Models\ArticleStatus status}.
     *
     * @see \Foxytouch\Article\Models\ArticleStatus
     * @return \Foxytouch\Article\Models\ArticleStatus
     */
    public function status()
    {
        return $this->belongsTo(ArticleStatus::class);
    }

    /**
     * Article has exactly one {@link \Foxytouch\Article\Models\Category category}.
     *
     * @see \Foxytouch\Article\Models\Category
     * @return \Foxytouch\Article\Models\Category
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Set article's slug. For more information see slugify function.
     * 
     * @param $slug
     */
    public function setSlugAttribute($slug)
    {
        $this->attributes['slug'] = slugify($slug);
    }
    
    /**
     * Get the table associated with the model.
     *
     * @return string
     */
    public function getTable()
    {
        return config('articles.table.name.article', 'article');
    }
}
