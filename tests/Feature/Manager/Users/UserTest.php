<?php

namespace Tests\Feature\Manager\Users;

use App\Forum\User\Models\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Hash;

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
    public function it_admin_can_view_users_pages()
    {
        $user = factory(User::class)->create();

        $pages = [
            ['route' => route('manager.users.index'), 'view' => 'manager.users.index'],
            ['route' => route('manager.users.create'), 'view' => 'manager.users.form'],
            ['route' => route('manager.users.edit', $user->id), 'view' => 'manager.users.form'],
        ];

        foreach ($pages as $page) {
            $response = $this->actingAs($this->admin)->get($page['route']);
            $response->assertSuccessful();
            $response->assertViewIs($page['view']);
        }
    }

    /** @test */
    public function it_user_cannot_view_user_pages()
    {
        $user = factory(User::class)->create();

        $routes = [
            route('manager.users.index'),
            route('manager.users.create'),
            route('manager.users.edit', $user->id),
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
    public function it_admin_can_view_users_list()
    {
        $users = factory(User::class, 3)->create();

        $response = $this->actingAs($this->admin)->get($this->userIndexGetRoute());
        $response->assertSuccessful();
        $response->assertViewHas(['currentPage', 'users']);

        $users->each(function($user) use ($response) {
            $response->assertSee($user->name);
            $response->assertSee($user->email);
            $response->assertSee($user->present()->isActive);
        });
    }

    /** @test */
    public function it_admin_can_view_a_create_form()
    {
        $response = $this->actingAs($this->admin)->get($this->userCreateGetRoute());
        $response->assertSuccessful();
        $response->assertViewHas(['currentPage', 'edit', 'user']);
        $response->assertViewIs('manager.users.form');
    }

    /** @test */
    public function it_admin_can_create_a_user()
    {
        $response = $this->actingAs($this->admin)->post($this->userStoreRoute(), [
            'name'                  => 'User One',
            'email'                 => 'userone@userone.com.br',
            'password'              => '123456',
            'password_confirmation' => '123456',
            'is_admin'              => true,
            'active'                => true,
        ]);

        $this->assertCount(1, $users = User::all());

        $user = $users->first();

        $this->assertEquals('User One', $user->name);
        $this->assertEquals('userone@userone.com.br', $user->email);
        $this->assertTrue(Hash::check('123456', $user->password));
        $this->assertEquals(true, $user->is_admin);
        $this->assertEquals(true, $user->active);

        $response->assertRedirect($this->userIndexGetRoute());
        $response->assertSessionHas('message', [
            'type' => 'success',
            'message' => 'User successfully registered.',
        ]);
    }
}
