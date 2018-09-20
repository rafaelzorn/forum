<?php

namespace Tests\Feature\Manager\Categories;

use App\Forum\Category\Models\Category;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CategoryTest extends TestCase
{
    use RefreshDatabase;

    protected function categoryIndexGetRoute()
    {
        return route('manager.categories.index');
    }

    protected function categoryCreateGetRoute()
    {
        return route('manager.categories.create');
    }

    protected function categoryStoreRoute()
    {
        return route('manager.categories.store');
    }

    protected function categoryEditGetRoute($id)
    {
        return route('manager.categories.edit', $id);
    }

    protected function categoryUpdateRoute($id)
    {
        return route('manager.categories.update', $id);
    }

    protected function categoryDeleteRoute($id)
    {
        return route('manager.categories.destroy', $id);
    }

    /** @test */
    public function it_admin_can_view_categories_pages()
    {
        $category = factory(Category::class)->create();

        $pages = [
            ['route' => route('manager.categories.index'), 'view' => 'manager.categories.index'],
            ['route' => route('manager.categories.create'), 'view' => 'manager.categories.form'],
            ['route' => route('manager.categories.edit', $category->id), 'view' => 'manager.categories.form'],
        ];

        foreach ($pages as $page) {
            $response = $this->actingAs($this->admin)->get($page['route']);
            $response->assertSuccessful();
            $response->assertViewIs($page['view']);
        }
    }

    /** @test */
    public function it_user_cannot_view_category_pages()
    {
        $category = factory(Category::class)->create();

        $routes = [
            route('manager.categories.index'),
            route('manager.categories.create'),
            route('manager.categories.edit', $category->id),
        ];

        foreach ($routes as $route) {
            $response = $this->actingAs($this->user)->get($route);
            $response->assertStatus(302);
            $response->assertSessionHas('message', [
                'type' => 'warning',
                'message' => 'You must be an administrator to see this page.'
            ]);

        }
    }

    /** @test */
    public function it_admin_can_view_categories_list()
    {
        $categories = factory(Category::class, 3)->make();

        $response = $this->actingAs($this->admin)->get($this->categoryIndexGetRoute());
        $response->assertSuccessful();
        $response->assertViewHas(['currentPage', 'categories']);

        $categories->each(function($category) use ($response) {
            $response->assertSee($category->title);
            $response->assertSee($category->active);
        });
    }

    /** @test */
    public function it_admin_can_view_a_create_form()
    {
        $response = $this->actingAs($this->admin)->get($this->categoryCreateGetRoute());
        $response->assertSuccessful();
        $response->assertViewHas(['currentPage', 'edit', 'category']);
        $response->assertViewIs('manager.categories.form');
    }

    /** @test */
    public function it_admin_can_create_a_category()
    {
        $response = $this->actingAs($this->admin)->post($this->categoryStoreRoute(), [
            'name' => 'Category One',
            'active' => 1,
        ]);

        $this->assertCount(1, $categories = Category::all());

        $category = $categories->first();

        $this->assertEquals('Category One', $category->name);
        $this->assertEquals(1, $category->active);

        $response->assertRedirect($this->categoryIndexGetRoute());
        $response->assertSessionHas('message', [
            'type' => 'success',
            'message' => 'Category successfully registered.',
        ]);
    }

    /** @test */
    public function it_admin_cannot_create_a_category_without_name()
    {
        $response = $this->actingAs($this->admin)->from($this->categoryCreateGetRoute())->post($this->categoryStoreRoute(), [
            'name' => '',
            'active' => 1,
        ]);

        $this->assertCount(0, Category::all());
        $response->assertRedirect($this->categoryCreateGetRoute());
        $response->assertSessionHasErrors(['name' => 'The name field is required.']);
        $this->assertTrue(session()->hasOldInput('active'));
    }

    /** @test */
    public function it_admin_cannot_create_a_category_without_situation()
    {
        $response = $this->actingAs($this->admin)->from($this->categoryCreateGetRoute())->post($this->categoryStoreRoute(), [
            'name' => 'Category One',
            'active' => '',
        ]);

        $this->assertCount(0, Category::all());
        $response->assertRedirect($this->categoryCreateGetRoute());
        $response->assertSessionHasErrors(['active' => 'The situation field is required.']);
        $this->assertTrue(session()->hasOldInput('name'));
    }

    /** @test */
    public function it_admin_can_view_a_edit_form()
    {
        $category = factory(Category::class)->create();

        $response = $this->actingAs($this->admin)->get($this->categoryEditGetRoute($category->id));
        $response->assertSuccessful();
        $response->assertViewHas(['currentPage', 'edit', 'category']);
        $response->assertViewIs('manager.categories.form');
    }

    /** @test */
    public function it_admin_can_edit_the_category()
    {
        $category = factory(Category::class)->create([
            'name' => 'Category One',
            'active' => 1,
        ]);

        $response = $this->actingAs($this->admin)->put($this->categoryUpdateRoute($category->id), [
            'name' => 'Category Two',
            'active' => 1,
        ]);

        $this->assertCount(1, $categories = Category::all());

        $category = $categories->first();

        $this->assertEquals('Category Two', $category->name);
        $this->assertEquals(1, $category->active);

        $response->assertRedirect($this->categoryIndexGetRoute());
        $response->assertSessionHas('message', [
            'type' => 'success',
            'message' => 'Category successfully updated.',
        ]);
    }

    /** @test */
    public function it_admin_cannot_update_the_category_without_name()
    {
        $category = factory(Category::class)->create([
            'name' => 'Category One',
            'active' => 1,
        ]);

        $response = $this->actingAs($this->admin)->from($this->categoryEditGetRoute($category->id))->put($this->categoryUpdateRoute($category->id), [
            'name' => '',
            'active' => 1,
        ]);

        $this->assertCount(1, $categories = Category::all());

        $category = $categories->first();

        $this->assertEquals('Category One', $category->name);
        $this->assertEquals(1, $category->active);

        $response->assertRedirect($this->categoryEditGetRoute($category->id));
        $response->assertSessionHasErrors(['name' => 'The name field is required.']);
        $this->assertTrue(session()->hasOldInput('active'));
    }

    /** @test */
    public function it_admin_cannot_update_the_category_without_situation()
    {
        $category = factory(Category::class)->create([
            'name' => 'Category One',
            'active' => 1,
        ]);

        $response = $this->actingAs($this->admin)->from($this->categoryEditGetRoute($category->id))->put($this->categoryUpdateRoute($category->id), [
            'name' => 'Category One',
            'active' => '',
        ]);

        $this->assertCount(1, $categories = Category::all());

        $category = $categories->first();

        $this->assertEquals('Category One', $category->name);
        $this->assertEquals(1, $category->active);

        $response->assertRedirect($this->categoryEditGetRoute($category->id));
        $response->assertSessionHasErrors(['active' => 'The situation field is required.']);
        $this->assertTrue(session()->hasOldInput('name'));
    }

    /** @test */
    public function it_admin_can_delete_a_category()
    {
        $category = factory(Category::class)->create();

        $response = $this->actingAs($this->admin)->from($this->categoryIndexGetRoute())->delete($this->categoryDeleteRoute($category->id));

        $this->assertCount(0, Category::all());
        $response->assertRedirect($this->categoryIndexGetRoute());
        $response->assertSessionHas('message', [
            'type' => 'success',
            'message' => 'Category deleted successfully.',
        ]);
    }
}
