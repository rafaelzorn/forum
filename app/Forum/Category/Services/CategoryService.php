<?php

namespace App\Forum\Category\Services;

use App\Forum\Category\Repositories\CategoryRepository;
use Exception;

class CategoryService
{
    private $categoryRepository;

    public function __construct(CategoryRepository $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }

    public function store($data)
    {
        try {
            $category = $this->categoryRepository->create($data);

            return [
                'type' => 'success',
                'message' => 'Category successfully registered.'
            ];
        } catch (Exception $e) {
            return [
                'type' => 'error',
                'message' => 'Category error registered.'
            ];
        }
    }
}
