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

        $this->model->password = $data['password'];
        $this->model->fill($data);
        $this->model->save();

        if (array_key_exists('permission', $data) && is_array($data['permission'])) {
            $this->model->permissions()->sync($data['permission']);
        }
        if (array_key_exists('role', $data) && is_array($data['role'])) {
            $this->model->roles()->sync($data['role']);
        }

        return $this->model;
    }

    private function createUserImage($image, $width = 200, $height = 200)
    {
        $fileName = sha1($this->model->username) . '.' . $image->guessExtension();
        $saveFilePath =
            'users' . DIRECTORY_SEPARATOR .
            config()->get('users.profile_pictures_path') . DIRECTORY_SEPARATOR .
            $fileName;

        Image::make($image)->resize($width, $height)->save($saveFilePath);

       return $saveFilePath;
    }

    public function destroy($model)
    {
        if ($model->profile_picture_uri) {
            File::delete($model->profile_picture_uri);
        }
        return $model->delete();
    }

    public function destroyUserImage($model)
    {
        File::delete($model->profile_picture_uri);
        $model->profile_picture_uri = null;
        $model->save();
    }

    /* TODO: refactor? - see: create method */
    public function update($model, array $data)
    {
        if (array_key_exists('profile_picture', $data)) {
            /* TODO: Delete old profile pic from the server? */
            $model->profile_picture_uri = $this->createUserImage($data['profile_picture']);
        }

        if ($data['password']) {
            $model->password = $data['password'];
        }

        $model->update($data);

        if (array_key_exists('permission', $data) && is_array($data['permission'])) {
            $model->permissions()->sync($data['permission']);
        }
        if (array_key_exists('role', $data) && is_array($data['role'])) {
            $model->roles()->sync($data['role']);
        }

        return $model;
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
