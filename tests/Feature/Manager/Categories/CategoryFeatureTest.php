<?php

namespace Tests\Feature\Manager\Categories;

use App\Forum\Category\Models\Category;
use Tests\TestCase;

class CategoryFeatureTest extends TestCase
{
    public function test_user_can_not_access_categories()
    {
        $this->actingAs($this->user, 'web')
            ->get(route('manager.categories.index'))
            ->assertStatus(302)
            ->assertSessionHas('message', [
                'type' => 'warning',
                'message' => 'You must be an administrator to see this page.'
            ]);
    }

    public function test_list_all_categories()
    {
        $categories = factory(Category::class, 3)->create();

        $response = $this->actingAs($this->admin, 'web')
            ->get(route('manager.categories.index'))
            ->assertStatus(200);

        $categories->each(function($category) use ($response) {
            $response->assertSee($category->title);
            $response->assertSee($category->active);
        });
    }

    public function test_show_create_category_page()
    {
        $this->actingAs($this->admin, 'web')
            ->get(route('manager.categories.create'))
            ->assertStatus(200)
            ->assertViewHas(['currentPage', 'edit', 'category'])
            ->assertSee('Name')
            ->assertSee('Select the situation')
            ->assertSee('Save')
            ->assertSee('Return');
    }

    public function test_store_category_successful()
    {
        $data = [
            'name'   => $this->faker->name,
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

    public function test_show_edit_page_category()
    {
        $category = factory(Category::class)->create();

        $this->actingAs($this->admin, 'web')
            ->get(route('manager.categories.edit', $category->id))
            ->assertStatus(200)
            ->assertViewHas(['currentPage', 'edit', 'category'])
            ->assertSee($category->name);
    }

    public function test_update_category_successful()
    {
        $category = factory(Category::class)->create();

        $data = [
            'name'   => $this->faker->name,
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

    public function test_destroy_category_successful()
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
}
