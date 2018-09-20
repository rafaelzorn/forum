<?php

namespace Tests\Unit\Category;

use App\Forum\Category\Models\Category;
use App\Forum\Category\Repositories\CategoryRepository;
use App\Forum\Category\Services\CategoryService;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class CategoryServiceTest extends TestCase
{
    use DatabaseMigrations;

    private $categoryRepository;
    private $categoryService;

    public function setUp()
    {
        parent::setUp();

        $this->categoryRepository = new CategoryRepository(new Category);
        $this->categoryService = new CategoryService($this->categoryRepository);
    }

    /** @test */
    public function it_can_store()
    {
        $request = $this->categoryService->store([
            'name'   => 'Category One',
            'active' => 1
        ]);

        $this->assertCount(1, $categories = Category::all());

        $category = $categories->first();

        $this->assertEquals('Category One', $category->name);
        $this->assertEquals(1, $category->active);

        $this->assertEquals('success', $request['type']);
        $this->assertEquals('Category successfully registered.', $request['message']);
    }

    /** @test */
    public function it_errors_when_store()
    {
        $request = $this->categoryService->store([]);

        $this->assertCount(0, $categories = Category::all());

        $this->assertEquals('error', $request['type']);
        $this->assertEquals('Category error registered.', $request['message']);
    }

    /** @test */
    public function it_can_update()
    {
        $category = factory(Category::class)->create([
            'name'   => 'Category One',
            'active' => 1
        ]);

        $request = $this->categoryService->update(
            [
            'name'   => 'Category Two',
            'active' => 1
            ],
            $category->id
        );

        $this->assertCount(1, $categories = Category::all());

        $category = $categories->first();

        $this->assertEquals('Category Two', $category->name);
        $this->assertEquals(1, $category->active);

        $this->assertEquals('success', $request['type']);
        $this->assertEquals('Category successfully updated.', $request['message']);
    }

    /** @test */
    public function it_cannot_update_category_that_does_not_exist()
    {
        $category = factory(Category::class)->create([
            'name'   => 'Category One',
            'active' => 1
        ]);

        $request = $this->categoryService->update(
            [
                'name'   => 'Category Two',
                'active' => 1
            ],
            999
        );

        $this->assertCount(1, $categories = Category::all());

        $category = $categories->first();

        $this->assertEquals('Category One', $category->name);
        $this->assertEquals(1, $category->active);

        $this->assertEquals('error', $request['type']);
        $this->assertEquals('Category error updated.', $request['message']);
    }

    /** @test */
    public function it_can_destroy()
    {
        $category = factory(Category::class)->create();

        $request = $this->categoryService->destroy($category->id);

        $this->assertCount(0, $categories = Category::all());

        $this->assertEquals('success', $request['type']);
        $this->assertEquals('Category deleted successfully.', $request['message']);
    }

    /** @test */
    public function it_cannot_destroy_category_that_does_not_exist()
    {
        $category = factory(Category::class)->create();

        $request = $this->categoryService->destroy(999);

        $this->assertCount(1, $categories = Category::all());

        $this->assertEquals('error', $request['type']);
        $this->assertEquals('Category deleted error.', $request['message']);
    }
}
