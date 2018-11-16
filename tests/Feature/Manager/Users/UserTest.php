<?php

namespace Tests\Feature\Manager\Users;

use App\Forum\User\Models\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    private function userIndexGetRoute()
    {
        return route('manager.users.index');
    }

    private function userCreateGetRoute()
    {
        return route('manager.users.create');
    }

    private function userStoreRoute()
    {
        return route('manager.users.store');
    }

    private function userEditGetRoute($id)
    {
        return route('manager.users.edit', $id);
    }

    private function userUpdateRoute($id)
    {
        return route('manager.users.update', $id);
    }

    private function userDeleteRoute($id)
    {
        return route('manager.users.destroy', $id);
    }

    /** @test */
    public function it_basic_test()
    {
        $this->assertTrue(true);
    }
}
