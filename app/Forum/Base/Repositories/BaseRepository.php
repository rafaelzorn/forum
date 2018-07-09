<?php

namespace App\Forum\Base\Repositories;

use App\Forum\Base\Repositories\Contracts\BaseRepositoryInterface;

abstract class BaseRepository implements BaseRepositoryInterface
{
    protected $model;

    public function __get($key)
    {
        return $this->model->getAttribute($key);
    }

    public function __set($key, $value)
    {
        $this->model->setAttribute($key, $value);
    }

    public function create(array $attributes)
    {
        return $this->model->create($attributes);
    }

    public function all($columns = array('*'), string $orderBy = 'id', string $sortBy = 'asc')
    {
        return $this->model->orderBy($orderBy, $sortBy)->get($columns);
    }
}
