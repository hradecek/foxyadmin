<?php

namespace Foxytouch\User\Repositories\Eloquent;

use Carbon\Carbon;
use Illuminate\Support\Facades\File;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Request;

use Foxytouch\Repositories\Eloquent\EloquentBaseRepository;
use Foxytouch\User\Repositories\Contracts\UserRepository;

/**
 * Eloquent implementation of User Repository.
 *
 * @see Foxytouch\User\Repositories\Contracts\UserRepository
 * @package Foxytouch\User\Repositories\Eloquent
 * @author Ivo Hradek <ivohradek@gmail.com>
 */
class EloquentUserRepository extends EloquentBaseRepository implements UserRepository
{
    public function create(array $data)
    {
        if (array_key_exists('profile_picture', $data)) {
            $this->model->profile_picture_uri = $this->createUserImage($data['profile_picture']);
        }
        if (array_key_exists('permission', $data) && is_array($data['permission'])) {
            $this->model->permissions()->sync($data['permission']);
        }
        $this->model->password = $data['password'];
        $this->model->fill($data);
        $this->model->save();
        
        return $this->model;
    }

    private function createUserImage($realFilePath, $width = 200, $height = 200)
    {
        $fileExtension = pathinfo($realFilePath, PATHINFO_EXTENSION);
        $fileName = sha1($this->model->username) . '.' . $fileExtension;
        $saveFilePath = config()->get('users.profile_pictures_path') . '/' . $fileName;
        
        $image = Image::make($realFilePath)
                      ->resize($width, $height)
                      ->save($saveFilePath);
        $image->destroy();

        return $saveFilePath;
    }

    public function destroyUserImage($model)
    {
        File::delete($model->profile_picture_uri);
        $model->profile_picture_uri = null;
        $model->save();
    }

    public function createWithGroups(array $data, array $groups)
    {
        $this->create($data);
        // if ($this->model->roles->isEmpty()) {
        //     $this->model->roles()->attach(array_column($groups, 'id'));
        // }
        // // $groups = array_map(function ($g) { return $g->toArray(); }, $groups);
        // $groups = $this->modelsToArrays($groups);
        // $this->model->groups()->sync(array_column($groups, 'id'), false);
    }

    public function updateWithGroups($model, array $data, array $groups)
    {
        if ($data['password']) {
            $model->password = $data['password'];
        }
        // $groups = array_map(function ($g) { return $g->toArray(); }, $groups);
        $groups = $this->modelsToArrays($groups);
        $model->groups()->sync(array_column($groups, 'id'));
        $this->update($model, $data)->update();
    }
    
    private function modelsToArrays($models) {
        return array_map(function ($m) { return $m->toArray(); }, $models);
    }

    public function destroy($model)
    {
        if ($model->profile_picture_uri) {
            File::delete($model->profile_picture_uri);
        }
        return $model->delete();
    }

    public function findByUsername($username)
    {
        return $this->model->byUsername($username)->first();
    }

    public function updateAfterLogin()
    {
        ++$this->model->sign_in_count;
        $this->model->online = true;
        $this->model->ip_address = Request::ip();
        $this->model->last_sign_in = Carbon::now();
        $this->model->update();
    }

    public function updateAfterLogout()
    {
        $this->model->online = false;
        $this->model->update();
    }
}
