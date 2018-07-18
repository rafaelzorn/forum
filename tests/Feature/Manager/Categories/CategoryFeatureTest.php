<?php

namespace Tests\Feature\Manager\Categories;

use App\Forum\Category\Models\Category;
use Tests\TestCase;

class CategoryFeatureTest extends TestCase
{
    public function test_show_the_categories_to_admin()
    {
        $this->actingAs($this->admin, 'web')
            ->get(route('manager.categories.index'))
            ->assertStatus(200);
    }

    public function test_show_the_index_category_page()
    {
        $this->actingAs($this->admin, 'web')
            ->get(route('manager.categories.index'))
            ->assertStatus(200)
            ->assertViewHas(['currentPage', 'categories']);
    }

    public function test_list_all_the_categories()
    {
        $category = factory(Category::class)->create();

        $this->actingAs($this->admin, 'web')
            ->get(route('manager.categories.index'))
            ->assertStatus(200)
            ->assertSee($category->name)
            ->assertSee($category->active);
    }

    public function test_should_not_show_categories_to_user()
    {
        $this->actingAs($this->user, 'web')
            ->get(route('manager.categories.index'))
            ->assertStatus(302)
            ->assertSessionHas('message', [
                'type' => 'warning',
                'message' => 'You must be an administrator to see this page.'
            ]);
    }

    public function test_show_the_create_category_page()
    {
        $this->actingAs($this->admin, 'web')
            ->get(route('manager.categories.create'))
            ->assertStatus(200)
            ->assertViewHas(['currentPage', 'edit', 'category']);
    }

    public function test_show_the_categories_form()
    {
        $this->actingAs($this->admin, 'web')
            ->get(route('manager.categories.create'))
            ->assertStatus(200)
            ->assertSee('Name')
            ->assertSee('Active')
            ->assertSee('Save')
            ->assertSee('Return');
    }

    public function test_if_create_category_successful()
    {
        $data = [
            'name'   => 'Video Game',
            'slug'   => 'video-game',
            'active' => 1
        ];

        $this->actingAs($this->admin, 'web')
            ->post(route('manager.categories.store'), $data)
            ->assertStatus(302)
            ->assertRedirect(route('manager.categories.index'))
            ->assertSessionHas('message', [
                'type' => 'success',
                'message' => 'Category successfully registered.'
            ]);
    }

    public function test_errors_category_without_completed_fields()
    {
        $this->actingAs($this->admin, 'web')
            ->post(route('manager.categories.store'), [])
            ->assertSessionHasErrors([
                'name'  => 'The name field is required.',
                'active' => 'The situation field is required.'
            ]);
    }

    public function test_show_the_update_category_page()
    {
        $category = factory(Category::class)->create();

        $this->actingAs($this->admin, 'web')
            ->get(route('manager.categories.edit', $category->id))
            ->assertStatus(200)
            ->assertViewHas(['currentPage', 'edit', 'category'])
            ->assertSee($category->name);
    }

    public function test_if_update_category_successful()
    {
        $category = factory(Category::class)->create();

        $data = [
            'name'   => 'Video Game',
            'slug'   => 'video-game',
            'active' => 1
        ];

        $this->actingAs($this->admin, 'web')
            ->put(route('manager.categories.update', $category->id), $data)
            ->assertStatus(302)
            ->assertRedirect(route('manager.categories.index'))
            ->assertSessionHas('message', [
                'type' => 'success',
                'message' => 'Category successfully updated.'
            ]);
    }

    public function test_if_destroy_category_successful()
    {
        $category = factory(Category::class)->create();

        $this->actingAs($this->admin, 'web')
            ->delete(route('manager.categories.destroy', $category->id))
            ->assertStatus(302)
            ->assertRedirect(route('manager.categories.index'))
            ->assertSessionHas('message', [
                'type' => 'success',
                'message' => 'Category deleted successfully.'
            ]);
    }

    public function test_if_destroy_category_error()
    {
        $this->actingAs($this->admin, 'web')
            ->delete(route('manager.categories.destroy', 999))
            ->assertStatus(302)
            ->assertRedirect(route('manager.categories.index'))
            ->assertSessionHas('message', [
                'type' => 'error',
                'message' => 'Category error deleted.'
            ]);
    }
}
