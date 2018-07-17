<?php

namespace App\Forum\Base\Repositories\Contracts;

interface BaseRepositoryInterface
{
    public function __get($key);

    public function __set($key, $value);

    public function create(array $attributes);

    public function all($columns = array('*'), string $orderBy = 'id', string $sortBy = 'asc');

    public function findOrfail(int $id);

    public function delete();
}
