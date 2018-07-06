<?php

namespace App\Forum\Category\Repositories;

use App\Forum\Base\Repositories\BaseRepository;
use App\Forum\Category\Repositories\Contracts\CategoryRepositoryInterface;
use App\Forum\Category\Models\Category;

class CategoryRepository extends BaseRepository implements CategoryRepositoryInterface
{
    public function __construct(Category $category)
    {
        $this->model = $category;
    }
}
