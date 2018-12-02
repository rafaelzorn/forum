<?php

namespace App\Forum\Category\Services;

use App\Forum\Category\Repositories\Contracts\CategoryRepositoryInterface;
use Exception;
use Lang;

class CategoryService
{
    private $categoryRepository;

    public function __construct(CategoryRepositoryInterface $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }

    public function store($data)
    {
        try {
            $this->categoryRepository->create($data);

            return [
                'type' => 'success',
                'message' => Lang::get('messages.category_successfully_registered'),
            ];
        } catch (Exception $e) {
            return [
                'type' => 'error',
                'message' => Lang::get('messages.category_error_registered'),
            ];
        }
    }

    public function update($data, $id)
    {
        try {
            $this->categoryRepository->findOrFail($id);
            $this->categoryRepository->slug = null;
            $this->categoryRepository->update($data);

            return [
                'type' => 'success',
                'message' => Lang::get('messages.category_successfully_updated'),
            ];
        } catch (Exception $e) {
            return [
                'type' => 'error',
                'message' => Lang::get('messages.category_error_updated'),
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
                'message' => Lang::get('messages.category_deleted_successfully'),
            ];
        } catch (Exception $e) {
            return [
                'type' => 'error',
                'message' => Lang::get('messages.category_deleted_error'),
            ];
        }
    }
}
