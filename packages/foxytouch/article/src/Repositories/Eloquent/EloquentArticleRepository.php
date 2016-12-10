<?php

namespace Foxytouch\Article\Repositories\Eloquent;

use Foxytouch\Repositories\Eloquent\EloquentBaseRepository;

use Foxytouch\Article\Repositories\Contracts\ArticleRepository;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Intervention\Image\Facades\Image;

/**
 * Eloquent implementation of Article Repository.
 *
 * @see Foxytouch\User\Repositories\Contracts\ArticleRepository
 * @package Foxytouch\User\Repositories\Eloquent
 * @author Ivo Hradek <ivohradek@gmail.com>
 */
class EloquentArticleRepository extends EloquentBaseRepository implements ArticleRepository
{
    public function create(array $data)
    {
        $this->upsert($this->model, $data);
        $this->model->user()->associate(Auth::user());
        $this->model->save();
        
        return $this->model;
    }

    public function update($model, array $data)
    {
        $this->upsert($model, $data);
        $model->update();
        
        return $model;
    }

    private function upsert($model, array $data)
    {
        if (array_key_exists('thumb', $data)) {
            $model->thumb_uri = $this->createArticleImage($data['thumb']);
        }
        if (!$model->slug) {
            $model->slug = slugify($model->title);
        }
        if (array_key_exists('status', $data)) {
            $model->status()->associate($data['status']);
        }
        if (array_key_exists('category', $data)) {
            $model->category()->associate($data['category']);
        }
        
        $model->fill($data);
    }

    private function createArticleImage($image, $width = 270, $height = 180 )
    {
        if ($this->model->thumb_uri) {
            File::delete($this->model->thumb_uri);
        }
        
        $fileName = config()->get('article.thumb_prefix') . uniqid() . '.' . $image->guessExtension();
        $filePath = config()->get('article.thumbs_path') . DIRECTORY_SEPARATOR . $fileName;
        
        Image::make($image)->resize($width, $height, function ($constraint) {
            $constraint->aspectRatio();
            $constraint->upsize();
        })->save($filePath);
        
        return $filePath;
    }
    
    public function destroy($model)
    {
        if ($model->thumb_uri) {
            File::delete($model->thumb_uri);
        }
        $model->delete();
    }

    public function findAllWithoutCategory()
    {
        return $this->model->whereNull('category_id')->get();
    }
}
