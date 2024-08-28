<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Model;

abstract class EloquentCompoundPrimaryKeyRepository extends EloquentRepository
{
    public function find($ids, $columns = ['*'])
    {
        $query = $this->model;
        foreach ($ids as $key => $value) {
            $query = $query->where($key, $value);
        }
        return $query->first($columns);
    }
}
