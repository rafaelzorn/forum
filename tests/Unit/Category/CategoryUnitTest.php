<?php

namespace Tests\Unit\Category;

use App\Forum\Category\Models\Category;
use App\Forum\Category\Repositories\CategoryRepository;
use App\Forum\Category\Services\CategoryService;
use Exception;
use Tests\TestCase;

class CategoryUnitTest extends TestCase
{
    public function test_can_create_a_category()
    {
        $data = [
            'name'   => 'Video Game',
            'slug'   => 'video-game',
            'active' => 1
        ];

        $categoryRepository = new CategoryRepository(new Category);
        $category = $categoryRepository->create($data);

        $this->assertInstanceOf(Category::class, $category);
        $this->assertEquals($data['name'], $category->name);
        $this->assertEquals($data['slug'], $category->slug);
        $this->assertEquals($data['active'], $category->active);
    }

    public function test_store_categories_successful()
    {
        $data = [
            'name'   => 'Video Game',
            'slug'   => 'video-game',
            'active' => 1
        ];

        $categoryService = new CategoryService(new CategoryRepository(new Category));
        $request = $categoryService->store($data);

        $this->assertEquals('success', $request['type']);
        $this->assertEquals('Category successfully registered.', $request['message']);
    }

    /* TODO: FAZER TESTE CAIR NO CATCH
    public function test_store_categories_error()
    {
        //$this->expectException(Exception::class);

        $data = [
            'name'   => 'Video Game',
            'slug'   => 'video-game',
            'active' => 1
        ];

        $categoryService = new CategoryService(new CategoryRepository(new Category));
        $request = $categoryService->store($data);

        dd($request);

        $this->assertEquals('error', $request['type']);
        $this->assertEquals('Category error registered.', $request['message']);
    }
    */
}
