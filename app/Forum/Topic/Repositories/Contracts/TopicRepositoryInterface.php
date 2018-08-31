<?php

namespace App\Forum\Topic\Repositories\Contracts;

use App\Forum\Base\Repositories\Contracts\BaseRepositoryInterface;

interface TopicRepositoryInterface extends BaseRepositoryInterface
{
    public function filter($params = [], $take = null);
}
