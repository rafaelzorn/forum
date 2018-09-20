<?php

namespace Tests\Feature\Manager\Dashboard;

use Tests\TestCase;

class DashboardTest extends TestCase
{
    protected function dashboardGetRoute()
    {
        return route('manager.dashboard');
    }

    /** @test */
    public function it_user_can_view_dashboard()
    {
        $response = $this->actingAs($this->user)->get($this->dashboardGetRoute());

        $response->assertSuccessful();
        $response->assertViewIs('manager.dashboard.index');
    }
}
