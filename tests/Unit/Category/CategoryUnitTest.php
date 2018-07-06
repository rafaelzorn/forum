<?php

namespace Tests\Unit\Category;

use App\Forum\Category\Models\Category;
use App\Forum\Category\Repositories\CategoryRepository;
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
}
