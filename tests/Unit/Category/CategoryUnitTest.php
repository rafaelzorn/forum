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

    public function test_can_create_a_category()
    {
        $data = [
            'name'   => 'Video Game',
            'slug'   => 'video-game',
            'active' => 1
        ];

        $category = $this->categoryRepository->create($data);

        $this->assertInstanceOf(Category::class, $category);
        $this->assertEquals($data['name'], $category->name);
        $this->assertEquals($data['slug'], $category->slug);
        $this->assertEquals($data['active'], $category->active);
    }

    public function test_find_or_fail_category()
    {
        $category = factory(Category::class)->create();

        $category = $this->categoryRepository->findOrFail($category->id);

        $this->assertInstanceOf(Category::class, $category);
    }

    public function test_fail_find_or_fail_category()
    {
        $this->expectException(ModelNotFoundException::class);

        $this->categoryRepository->findOrFail(999);
    }

    public function test_can_list_all_the_categories()
    {
        factory(Category::class)->create();

        $this->assertCount(1, $this->categoryRepository->all());
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

    public function test_can_delete_a_category()
    {
        $category = factory(Category::class)->create();

        $categoryRepository = new CategoryRepository($category);
        $delete = $categoryRepository->delete();

        $this->assertTrue(true, $delete);

        $this->assertDatabaseHas('categories', $category->toArray());
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
        $this->assertEquals('Category error deleted.', $request['message']);
    }
}
