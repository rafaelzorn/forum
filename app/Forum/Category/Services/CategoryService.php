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
            $this->categoryRepository->create($data);

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

    public function destroy($id)
    {
        try {
            $this->categoryRepository->findOrFail($id);
            $this->categoryRepository->delete();

            return [
                'type' => 'success',
                'message' => 'Category deleted successfully.'
            ];
        } catch (Exception $e) {
            return [
                'type' => 'error',
                'message' => 'Category error deleted.'
            ];
        }
    }
}
