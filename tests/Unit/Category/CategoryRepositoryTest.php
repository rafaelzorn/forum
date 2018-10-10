<?php

namespace Tests\Unit\Category;

use App\Forum\Category\Models\Category;
use App\Forum\Topic\Models\Topic;
use App\Forum\Category\Repositories\CategoryRepository;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class TopicRepositoryTest extends TestCase
{
    use DatabaseMigrations;

    private $categoryRepository;

    public function setUp()
    {
        parent::setUp();

        $this->categoryRepository = new CategoryRepository(new Category);
    }

    /** @test */
    public function it_user_can_view_only_active_categories()
    {
        factory(Category::class)->create([
            'active' => true,
        ]);

        factory(Category::class)->create([
            'active' => false,
        ]);

        $categories = $this->categoryRepository->getActives();

        $this->assertCount(1, $categories);
    }

    /** @test */
    public function products_associated_with_a_category()
    {
        $category = factory(Category::class)->create();

        $topic = factory(Topic::class)->create([
            'category_id' => $category->id
        ]);

        $this->assertEquals($category->topics[0]->id, $topic->id);
    }
}
