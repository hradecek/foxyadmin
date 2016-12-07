<?php

namespace Foxytouch\Article\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * <p>
 * Category represents category of single {@link \Foxytouch\Article\Models\Article article}.
 * </p>
 *
 * <p>
 * Category has <i>mandatory</i> and <i>unique</i> name and <i>optional</i> description.
 * </p>
 *
 * @see \Foxytouch\Article\Models\Article
 * @package \Foxytouch\Article\Models
 * @author Ivo Hradek <ivohradek@gmail.com>
 */
class Category extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'description'
    ];

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * Category have many articles.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function articles()
    {
        return $this->hasMany(Article::class);
    }
    
    /**
     * Get the table associated with the model.
     *
     * @return string
     */
    public function getTable()
    {
        return config('articles.table.name.category', 'category');
    }
}
