<?php

namespace Tests\Unit\Category;

use App\Forum\Category\Models\Category;
use App\Forum\Category\Repositories\CategoryRepository;
use App\Forum\Category\Services\CategoryService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Tests\TestCase;

class CategoryUnitTest extends TestCase
{
    protected $categoryRepository;
    protected $categoryService;

    public function setUp()
    {
        parent::setUp();

        $this->categoryRepository = new CategoryRepository(new Category);
        $this->categoryService = new CategoryService($this->categoryRepository);
    }

    public function test_service_store_categories_successful()
    {
        $data = [
            'name'   => 'Video Game',
            'slug'   => 'video-game',
            'active' => 1
        ];

        $request = $this->categoryService->store($data);

        $this->assertEquals('success', $request['type']);
        $this->assertEquals('Category successfully registered.', $request['message']);
    }

    public function test_service_store_categories_error()
    {
        $request = $this->categoryService->store([]);

        $this->assertEquals('error', $request['type']);
        $this->assertEquals('Category error registered.', $request['message']);
    }

    public function test_service_update_categories_successful()
    {
        $category = factory(Category::class)->create();

        $data = [
            'name'   => 'Video Game',
            'slug'   => 'video-game',
            'active' => 1
        ];

        $request = $this->categoryService->update($data, $category->id);

        $this->assertEquals('success', $request['type']);
        $this->assertEquals('Category successfully updated.', $request['message']);
    }

    public function test_service_update_categories_error()
    {
        $request = $this->categoryService->update([], 999);

        $this->assertEquals('error', $request['type']);
        $this->assertEquals('Category error updated.', $request['message']);
    }

    public function test_service_destroy_categories_successful()
    {
        $category = factory(Category::class)->create();

        $request = $this->categoryService->destroy($category->id);

        $this->assertEquals('success', $request['type']);
        $this->assertEquals('Category deleted successfully.', $request['message']);
    }

    public function test_service_destroy_categories_error()
    {
        $request = $this->categoryService->destroy(999);

        $this->assertEquals('error', $request['type']);
        $this->assertEquals('Category deleted error.', $request['message']);
    }
}
