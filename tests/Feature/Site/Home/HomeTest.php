<?php

namespace Tests\Feature\Site\Home;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class HomeTest extends TestCase
{
    use RefreshDatabase;

    private function homeIndexGetRoute()
    {
        return route('home');
    }

    /** @test */
    public function it_user_can_view_home()
    {
        $response = $this->get($this->homeIndexGetRoute());

        $response->assertSuccessful();
        $response->assertViewIs('site.home.index');
    }
}
