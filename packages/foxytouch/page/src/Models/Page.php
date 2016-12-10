<?php

namespace Foxytouch\Page\Models;

use Foxytouch\User\Models\User;

use Illuminate\Database\Eloquent\Model;

/**
 * <p>Model representing a single page</p>
 *
 * @see \Foxytouch\Page\Models\User
 * @package \Foxytouch\Page\Models
 * @author Ivo Hradek <ivohradek@gmail.com>
 */
class Page extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'slug',
        'title',
        'content',
        'keywords',
        'description'
    ];

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = true;
    
    /**
     * Get the table associated with the model.
     *
     * @return string
     */
    public function getTable()
    {
        return config('pages.table.name.page', 'page');
    }

    /**
     * Article's {@link \Foxytouch\Page\Models\User author}.
     *
     * @see \Foxytouch\User\Models\User
     * @return \Foxytouch\User\Models\User
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    /**
     * See slug function for more information.
     * 
     * @param $slug
     */
    public function setSlugAttribute($slug)
    {
        $this->attributes['slug'] = slugify($slug);
    }
}
