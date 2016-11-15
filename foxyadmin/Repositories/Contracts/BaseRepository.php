<?php

namespace Foxytouch\Repositories\Contracts;

/**
 * <p>Base repository provides basic DB functions.</p>
 * 
 * @package Foxytouch\Repositories\Contracts
 * @author Ivo Hradek <ivohradek@gmail.com>
 */
interface BaseRepository
{
    /**
     * Return all.
     * 
     * @param array $columns
     * @return mixed
     */
    function all($columns = ['*']);

    /**
     * Return paginated items.
     *
     * @param int $perPage
     * @param array $columns
     * @return mixed
     */
    function paginate($perPage = 15, $columns = ['*']);

    /**
     * Return sorted all.
     *
     * @param string $column
     * @param bool $desc
     * @return mixed
     */
    function allSorted($column, $desc);
    
    /**
     * Create.
     *
     * @param $data
     * @return mixed
     */
    function create(array $data);

    /**
     * Update.
     *
     * @param $model
     * @param array $data
     * @return mixed
     */
    function update($model, array $data);

    /**
     * Destroy.
     *
     * @param $model
     * @return mixed
     */
    function destroy($model);

    /**
     * Find by ID.
     *
     * @param int $id
     * @param array $columns
     * @return  $model
     */
    function find($id, $columns = ['*']);

    /**
     * Find by attribute.
     *
     * @param $attribute
     * @param $value
     * @param array $columns
     * @return mixed
     */
    function findBy($attribute, $value, $columns = ['*']);

    /**
     * Find all values from specific column.
     * 
     * @param $column
     * @return mixed
     */
    function findAllValues($column);
}