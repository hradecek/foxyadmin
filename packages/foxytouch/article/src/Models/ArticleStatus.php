<?php

namespace Foxytouch\Article\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * <p>
 * Status represents state of single {@link \Foxytouch\Models\Article article}.
 * </p>
 *
 * <p>
 * Status name could be something like <i>draft</i>, </i>published</i> etc.<br />
 * Its recommended to use one word only.
 * </p>
 *
 * @see \Foxytouch\Article\Models\Article
 * @package \Foxytouch\Article\Models
 * @author Ivo Hradek <ivohradek@gmail.com>
 */
class ArticleStatus extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name'];

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * Status belongs to many articles.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function articles()
    {
        return $this->belongsToMany(Article::class);
    }

    /**
     * Set the article status' name. Always lower-cased.
     *
     * @param string $name
     */
    public function setNameAttribute($name)
    {
        $this->attributes['name'] = strtolower($name);
    }

    /**
     * Get the article status' name. First character uppercased.
     *
     * @param string $name
     * @return string status' name
     */
    public function getNameAttribute($name)
    {
        return ucfirst($name);
    }
    
    /**
     * Get the table associated with the model.
     *
     * @return string
     */
    public function getTable()
    {
        return config('articles.table.name.article_status', 'article_status');
    }
}
