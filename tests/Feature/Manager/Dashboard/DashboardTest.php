<?php

namespace Tests\Feature\Manager\Dashboard;

use App\Forum\User\Models\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DashboardTest extends TestCase
{
    protected function dashboardGetRoute()
    {
        return route('manager.dashboard');
    }

    public function test_user_can_view_dashboard()
    {
        $user = factory(User::class)->make();

        $response = $this->actingAs($user)->get($this->dashboardGetRoute());

        $response->assertSuccessful();
        $response->assertViewIs('manager.dashboard.index');
    }
}
