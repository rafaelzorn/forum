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
}
