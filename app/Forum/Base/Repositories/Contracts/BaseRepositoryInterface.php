<?php

namespace App\Forum\Base\Repositories\Contracts;

interface BaseRepositoryInterface
{
    public function __get($key);

    public function __set($key, $value);

    public function all($columns = array('*'), string $orderBy = 'id', string $sortBy = 'asc');

    public function findOrFail($id, $columns = ['*']);

    public function findOrFailBy(array $data);

    public function create(array $attributes);

    public function update(array $values);

    public function delete();
}
