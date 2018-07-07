<?php

namespace Tests\Feature\Manager\Dashboard;

use App\Forum\User\Models\User;
use Tests\TestCase;

class CategoryFeatureTest extends TestCase
{
    public function test_show_the_categories_to_admin()
    {
        $this->actingAs($this->admin, 'web')
            ->get(route('manager.categories.index'))
            ->assertStatus(200);
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
}
