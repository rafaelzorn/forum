<?php

namespace App\Forum\Category\Repositories\Contracts;

use App\Forum\Base\Repositories\Contracts\BaseRepositoryInterface;

interface CategoryRepositoryInterface extends BaseRepositoryInterface
{
    public function getActives();
}
