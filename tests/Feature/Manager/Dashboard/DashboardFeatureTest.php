<?php

namespace Tests\Feature\Manager\Dashboard;

use Tests\TestCase;

class DashboardFeatureTest extends TestCase
{
    public function test_show_dashboard()
    {
        $this->actingAs($this->user, 'web')
            ->get(route('manager.dashboard'))
            ->assertStatus(200);
    }
}
