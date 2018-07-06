<?php

namespace App\Forum\Base\Repositories;

use App\Forum\Base\Repositories\Contracts\BaseRepositoryInterface;

abstract class BaseRepository implements BaseRepositoryInterface
{
    protected $model;

    public function create(array $attributes)
    {
        return $this->model->create($attributes);
    }
}
