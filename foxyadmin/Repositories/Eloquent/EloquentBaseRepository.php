<?php

namespace Foxytouch\Repositories\Eloquent;

use Foxytouch\Repositories\Contracts\BaseRepository;

/**
 * <p>Eloquent base repository implementation.</p>
 * 
 * @see \Foxytouch\Repositories\Contracts\BaseRepository
 * @package Foxytouch\Repositories\Eloquent
 * @author Ivo Hradek <ivohradek@gmail.com>
 */
abstract class EloquentBaseRepository implements BaseRepository
{
    /**
     * @var \Illuminate\Database\Eloquent\Model An instance of the Eloquent Model
     */
    protected $model;

    /**
     * @param \Illuminate\Database\Eloquent\Model $model
     */
    public function __construct($model)
    {
        $this->model = $model;
    }

    public function all($columns = ['*'])
    {
        return $this->model->all($columns);
    }

    public function paginate($perPage = 15, $columns = ['*'])
    {
        return $this->model->paginate($perPage, $columns);
    }

    public function allSorted($column = 'created_at', $desc = true)
    {
        return $this->model->orderBy($column, $desc ? 'desc' : 'asc')->get();
    }

    public function create(array $data)
    {
        return $this->model->create($data);
    }

    public function update($model, array $data)
    {
        $model->update($data);

        return $model;
    }

    public function destroy($model)
    {
        return $model->delete();
    }

    public function find($id, $columns = ['*'])
    {
        return $this->model->find($id, $columns);
    }

    public function findBy($attribute, $value, $columns = ['*'])
    {
        return $this->model->where($attribute, '=', $value)->first($columns);
    }

    public function findAllValues($column)
    {
        return $this->model->lists($column)->toArray();
    }
}
