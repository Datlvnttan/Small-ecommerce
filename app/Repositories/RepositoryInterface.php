<?php

namespace App\Repositories;

interface RepositoryInterface
{
    /**
     * Get all
     * @param array $columns
     * @return mixed
     */
    public function all($columns = ['*'],$perPage = null);

    /**
     * Get one
     * @param $id
     * @param array $columns
     * @return mixed
     */
    public function find($id, $columns = ['*']);

    /**
     * Create
     * @param array $data
     * @return mixed
     */
    public function create(array $data);

    /**
     * Update
     * @param $id
     * @param array $data
     * @return mixed
     */
    public function update($id, array $data);

    /**
     * Delete
     * @param $id     
     * @return mixed
     */
    public function delete($id);

     /**
     * Deletes
     * @param array $list_id
     * @return mixed
     */
    public function deletes($list_id);

    /**
     * findBy
     * @param $field
     * @param $value
     * @param array $columns
     * @return mixed
     */
    public function findBy($field, $value, $columns = ['*']);

    /**
     * getPaginated
     * @param mixed $perPage
     * @param array $columns
     * @return mixed
     */
    public function getPaginated(?int $perPage, mixed $columns = ['*']);
    public function inserts($data);
    public function whereInBy(string $filed, array $value);
}
