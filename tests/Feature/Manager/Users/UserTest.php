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

    /** @test */
    public function it_admin_cannot_create_a_user_without_name()
    {
        $response = $this->actingAs($this->admin)->from($this->userCreateGetRoute())->post($this->userStoreRoute(), [
            'name'                  => '',
            'email'                 => 'userone@userone.com.br',
            'password'              => '123456',
            'password_confirmation' => '123456',
            'is_admin'              => true,
            'active'                => true,
        ]);

        $this->assertCount(0, User::all());
        $response->assertRedirect($this->userCreateGetRoute());
        $response->assertSessionHasErrors(['name' => 'The name field is required.']);

        $this->assertTrue(session()->hasOldInput('email'));
        $this->assertTrue(session()->hasOldInput('is_admin'));
        $this->assertTrue(session()->hasOldInput('active'));
    }

    /** @test */
    public function it_admin_cannot_create_a_user_whose_name_exceeds_the_character_limit()
    {
        $response = $this->actingAs($this->admin)->from($this->userCreateGetRoute())->post($this->userStoreRoute(), [
            'name'                  => str_random(256),
            'email'                 => 'userone@userone.com.br',
            'password'              => '123456',
            'password_confirmation' => '123456',
            'is_admin'              => true,
            'active'                => true,
        ]);

        $this->assertCount(0, User::all());
        $response->assertRedirect($this->userCreateGetRoute());
        $response->assertSessionHasErrors(['name' => 'The name may not be greater than 255 characters.']);

        $this->assertTrue(session()->hasOldInput('email'));
        $this->assertTrue(session()->hasOldInput('is_admin'));
        $this->assertTrue(session()->hasOldInput('active'));
    }

    /** @test */
    public function it_admin_cannot_create_a_user_without_email()
    {
        $response = $this->actingAs($this->admin)->from($this->userCreateGetRoute())->post($this->userStoreRoute(), [
            'name'                  => 'User One',
            'email'                 => '',
            'password'              => '123456',
            'password_confirmation' => '123456',
            'is_admin'              => true,
            'active'                => true,
        ]);

        $this->assertCount(0, User::all());
        $response->assertRedirect($this->userCreateGetRoute());

        $response->assertSessionHasErrors(['email' => 'The email field is required.']);
        $this->assertTrue(session()->hasOldInput('name'));
        $this->assertTrue(session()->hasOldInput('is_admin'));
        $this->assertTrue(session()->hasOldInput('active'));
    }

    /** @test */
    public function it_admin_cannot_create_a_user_whose_email_exceeds_the_character_limit()
    {
        $response = $this->actingAs($this->admin)->from($this->userCreateGetRoute())->post($this->userStoreRoute(), [
            'name'                  => 'User One',
            'email'                 => str_random(256),
            'password'              => '123456',
            'password_confirmation' => '123456',
            'is_admin'              => true,
            'active'                => true,
        ]);

        $this->assertCount(0, User::all());
        $response->assertRedirect($this->userCreateGetRoute());
        $response->assertSessionHasErrors(['email' => 'The email may not be greater than 255 characters.']);

        $this->assertTrue(session()->hasOldInput('name'));
        $this->assertTrue(session()->hasOldInput('is_admin'));
        $this->assertTrue(session()->hasOldInput('active'));
    }

    /* @test */
    public function it_admin_can_not_create_a_user_with_an_existing_email()
    {
        $user = factory(User::class)->create([
            'email' => 'userone@userone.com.br'
        ]);

        $response = $this->actingAs($this->admin)->from($this->userCreateGetRoute())->post($this->userStoreRoute(), [
            'name'                  => 'User One',
            'email'                 => 'userone@userone.com.br',
            'password'              => '123456',
            'password_confirmation' => '123456',
            'is_admin'              => true,
            'active'                => true,
        ]);

        $this->assertCount(0, User::all());
        $response->assertRedirect($this->userCreateGetRoute());

        $response->assertSessionHasErrors(['email' => 'The email has already been taken.']);

        $this->assertTrue(session()->hasOldInput('name'));
        $this->assertTrue(session()->hasOldInput('is_admin'));
        $this->assertTrue(session()->hasOldInput('active'));
    }

    /* @test */
    public function it_admin_cannot_create_a_user_with_an_invalid_email()
    {
        $response = $this->actingAs($this->admin)->from($this->userCreateGetRoute())->post($this->userStoreRoute(), [
            'name'                  => 'User One',
            'email'                 => 'userone.com.br',
            'password'              => '123456',
            'password_confirmation' => '123456',
            'is_admin'              => true,
            'active'                => true,
        ]);

        $this->assertCount(0, User::all());
        $response->assertRedirect($this->userCreateGetRoute());

        $response->assertSessionHasErrors(['email' => 'The email must be a valid email address.']);

        $this->assertTrue(session()->hasOldInput('name'));
        $this->assertTrue(session()->hasOldInput('is_admin'));
        $this->assertTrue(session()->hasOldInput('active'));
    }

    /** @test */
    public function it_admin_cannot_create_a_user_without_password()
    {
        $response = $this->actingAs($this->admin)->from($this->userCreateGetRoute())->post($this->userStoreRoute(), [
            'name'                  => 'User One',
            'email'                 => 'userone@userone.com.br',
            'password'              => '',
            'password_confirmation' => '123456',
            'is_admin'              => true,
            'active'                => true,
        ]);

        $this->assertCount(0, User::all());
        $response->assertRedirect($this->userCreateGetRoute());

        $response->assertSessionHasErrors(['password' => 'The password field is required.']);
        $this->assertTrue(session()->hasOldInput('email'));
        $this->assertTrue(session()->hasOldInput('name'));
        $this->assertTrue(session()->hasOldInput('is_admin'));
        $this->assertTrue(session()->hasOldInput('active'));
    }

    /* @test */
    public function it_admin_cannot_create_a_user_whose_password_doesnt_have_the_minimum_of_characters()
    {
        $response = $this->actingAs($this->admin)->from($this->userCreateGetRoute())->post($this->userStoreRoute(), [
            'name'                  => 'User One',
            'email'                 => 'userone@userone.com.br',
            'password'              => '1234',
            'password_confirmation' => '1234',
            'is_admin'              => true,
            'active'                => true,
        ]);

        $this->assertCount(0, User::all());
        $response->assertRedirect($this->userCreateGetRoute());

        $response->assertSessionHasErrors(['password' => 'The password must be at least 6 characters.']);
        $this->assertTrue(session()->hasOldInput('email'));
        $this->assertTrue(session()->hasOldInput('name'));
        $this->assertTrue(session()->hasOldInput('is_admin'));
        $this->assertTrue(session()->hasOldInput('active'));
    }

    /* @test */
    public function it_admin_cannot_create_a_user_when_the_password_confirmation_doesnt_match()
    {
        $response = $this->actingAs($this->admin)->from($this->userCreateGetRoute())->post($this->userStoreRoute(), [
            'name'                  => 'User One',
            'email'                 => 'userone@userone.com.br',
            'password'              => '123456',
            'password_confirmation' => '1234567',
            'is_admin'              => true,
            'active'                => true,
        ]);

        $this->assertCount(0, User::all());
        $response->assertRedirect($this->userCreateGetRoute());

        $response->assertSessionHasErrors(['password' => 'The password confirmation does not match.']);
        $this->assertTrue(session()->hasOldInput('email'));
        $this->assertTrue(session()->hasOldInput('name'));
        $this->assertTrue(session()->hasOldInput('is_admin'));
        $this->assertTrue(session()->hasOldInput('active'));
    }

    /** @test */
    public function it_admin_can_view_a_edit_form()
    {
        $user = factory(User::class)->create();

        $response = $this->actingAs($this->admin)->get($this->userEditGetRoute($user->id));
        $response->assertSuccessful();

        $response->assertViewHas(['currentPage', 'edit', 'user']);
        $response->assertViewIs('manager.users.form');
    }

    /** @test */
    public function it_admin_can_edit_a_user()
    {
        $user = factory(User::class)->create([
            'name'                  => 'User One',
            'email'                 => 'userone@userone.com.br',
            'password'              => '123456',
            'is_admin'              => true,
            'active'                => true,
        ]);

        $response = $this->actingAs($this->admin)->put($this->userUpdateRoute($user->id), [
            'name'                  => 'User Two',
            'email'                 => 'usertwo@usertwo.com.br',
            'password'              => '1234567',
            'password_confirmation' => '1234567',
            'is_admin'              => false,
            'active'                => false,
        ]);

        $this->assertCount(1, $users = User::all());

        $user = $users->first();

        $this->assertEquals('User Two', $user->name);
        $this->assertEquals('usertwo@usertwo.com.br', $user->email);
        $this->assertTrue(Hash::check('1234567', $user->password));
        $this->assertEquals(false, $user->is_admin);
        $this->assertEquals(false, $user->active);

        $response->assertRedirect($this->userIndexGetRoute());
        $response->assertSessionHas('message', [
            'type' => 'success',
            'message' => 'User successfully updated.',
        ]);
    }

    /** @test */
    public function it_admin_cannot_edit_a_user_without_name()
    {
        $user = factory(User::class)->create([
            'name'                  => 'User One',
            'email'                 => 'userone@userone.com.br',
            'password'              => '123456',
            'is_admin'              => true,
            'active'                => true,
        ]);

        $response = $this->actingAs($this->admin)->from($this->userEditGetRoute($user->id))->put($this->userUpdateRoute($user->id), [
            'name'                  => '',
            'email'                 => 'usertwo@usertwo.com.br',
            'password'              => '1234567',
            'password_confirmation' => '1234567',
            'is_admin'              => false,
            'active'                => false,
        ]);

        $this->assertCount(1, $users = User::all());

        $user = $users->first();

        $this->assertEquals('User One', $user->name);
        $this->assertEquals('userone@userone.com.br', $user->email);
        $this->assertTrue(Hash::check('123456', Hash::make($user->password)));
        $this->assertEquals(true, $user->is_admin);
        $this->assertEquals(true, $user->active);

        $response->assertRedirect($this->userEditGetRoute($user->id));
        $response->assertSessionHasErrors(['name' => 'The name field is required.']);

        $this->assertTrue(session()->hasOldInput('email'));
        $this->assertTrue(session()->hasOldInput('is_admin'));
        $this->assertTrue(session()->hasOldInput('active'));
    }

    /** @test */
    public function it_admin_cannot_edit_a_user_whose_name_exceeds_the_character_limit()
    {
        $user = factory(User::class)->create([
            'name'                  => 'User One',
            'email'                 => 'userone@userone.com.br',
            'password'              => '123456',
            'is_admin'              => true,
            'active'                => true,
        ]);

        $response = $this->actingAs($this->admin)->from($this->userEditGetRoute($user->id))->put($this->userUpdateRoute($user->id), [
            'name'                  => str_random(256),
            'email'                 => 'userone@usertwo.com.br',
            'password'              => '1234567',
            'password_confirmation' => '1234567',
            'is_admin'              => false,
            'active'                => false,
        ]);

        $this->assertCount(1, $users = User::all());

        $user = $users->first();

        $this->assertEquals('User One', $user->name);
        $this->assertEquals('userone@userone.com.br', $user->email);
        $this->assertTrue(Hash::check('123456', Hash::make($user->password)));
        $this->assertEquals(true, $user->is_admin);
        $this->assertEquals(true, $user->active);

        $response->assertRedirect($this->userEditGetRoute($user->id));
        $response->assertSessionHasErrors(['name' => 'The name may not be greater than 255 characters.']);

        $this->assertTrue(session()->hasOldInput('email'));
        $this->assertTrue(session()->hasOldInput('is_admin'));
        $this->assertTrue(session()->hasOldInput('active'));
    }

    /** @test */
    public function it_admin_cannot_edit_a_user_without_email()
    {
        $user = factory(User::class)->create([
            'name'                  => 'User One',
            'email'                 => 'userone@userone.com.br',
            'password'              => '123456',
            'is_admin'              => true,
            'active'                => true,
        ]);

        $response = $this->actingAs($this->admin)->from($this->userEditGetRoute($user->id))->put($this->userUpdateRoute($user->id), [
            'name'                  => 'User Two',
            'email'                 => '',
            'password'              => '1234567',
            'password_confirmation' => '1234567',
            'is_admin'              => false,
            'active'                => false,
        ]);

        $this->assertCount(1, $users = User::all());

        $user = $users->first();

        $this->assertEquals('User One', $user->name);
        $this->assertEquals('userone@userone.com.br', $user->email);
        $this->assertTrue(Hash::check('123456', Hash::make($user->password)));
        $this->assertEquals(true, $user->is_admin);
        $this->assertEquals(true, $user->active);

        $response->assertRedirect($this->userEditGetRoute($user->id));
        $response->assertSessionHasErrors(['email' => 'The email field is required.']);

        $this->assertTrue(session()->hasOldInput('name'));
        $this->assertTrue(session()->hasOldInput('is_admin'));
        $this->assertTrue(session()->hasOldInput('active'));
    }

    /** @test */
    public function it_admin_cannot_edit_a_user_whose_email_exceeds_the_character_limit()
    {
        $user = factory(User::class)->create([
            'name'                  => 'User One',
            'email'                 => 'userone@userone.com.br',
            'password'              => '123456',
            'is_admin'              => true,
            'active'                => true,
        ]);

        $response = $this->actingAs($this->admin)->from($this->userEditGetRoute($user->id))->put($this->userUpdateRoute($user->id), [
            'name'                  => 'User Two',
            'email'                 => str_random(256),
            'password'              => '1234567',
            'password_confirmation' => '1234567',
            'is_admin'              => false,
            'active'                => false,
        ]);

        $this->assertCount(1, $users = User::all());

        $user = $users->first();

        $this->assertEquals('User One', $user->name);
        $this->assertEquals('userone@userone.com.br', $user->email);
        $this->assertTrue(Hash::check('123456', Hash::make($user->password)));
        $this->assertEquals(true, $user->is_admin);
        $this->assertEquals(true, $user->active);

        $response->assertRedirect($this->userEditGetRoute($user->id));
        $response->assertSessionHasErrors(['email' => 'The email may not be greater than 255 characters.']);

        $this->assertTrue(session()->hasOldInput('name'));
        $this->assertTrue(session()->hasOldInput('is_admin'));
        $this->assertTrue(session()->hasOldInput('active'));
    }

    /* @test */
    public function it_admin_can_not_edit_a_user_with_an_existing_email()
    {
        $anotherUser = factory(User::class)->create([
            'email' => 'anotheruser@anotheruser.com.br'
        ]);

        $user = factory(User::class)->create([
            'name'                  => 'User One',
            'email'                 => 'userone@userone.com.br',
            'password'              => '123456',
            'is_admin'              => true,
            'active'                => true,
        ]);

        $response = $this->actingAs($this->admin)->from($this->userEditGetRoute($user->id))->put($this->userUpdateRoute($user->id), [
            'name'                  => 'User Two',
            'email'                 => 'anotheruser@anotheruser.com.br',
            'password'              => '1234567',
            'password_confirmation' => '1234567',
            'is_admin'              => false,
            'active'                => false,
        ]);

        $this->assertCount(1, $users = User::all());

        $user = $users->first();

        $this->assertEquals('User One', $user->name);
        $this->assertEquals('userone@userone.com.br', $user->email);
        $this->assertTrue(Hash::check('123456', Hash::make($user->password)));
        $this->assertEquals(true, $user->is_admin);
        $this->assertEquals(true, $user->active);

        $response->assertRedirect($this->userEditGetRoute($user->id));
        $response->assertSessionHasErrors(['email' => 'The email has already been taken.']);

        $this->assertTrue(session()->hasOldInput('name'));
        $this->assertTrue(session()->hasOldInput('is_admin'));
        $this->assertTrue(session()->hasOldInput('active'));
    }

    /* @test */
    public function it_admin_cannot_edit_a_user_with_an_invalid_email()
    {
        $user = factory(User::class)->create([
            'name'                  => 'User One',
            'email'                 => 'userone@userone.com.br',
            'password'              => '123456',
            'is_admin'              => true,
            'active'                => true,
        ]);

        $response = $this->actingAs($this->admin)->from($this->userEditGetRoute($user->id))->put($this->userUpdateRoute($user->id), [
            'name'                  => 'User Two',
            'email'                 => 'usertwo.com.br',
            'password'              => '1234567',
            'password_confirmation' => '1234567',
            'is_admin'              => false,
            'active'                => false,
        ]);

        $this->assertCount(1, $users = User::all());

        $user = $users->first();

        $this->assertEquals('User One', $user->name);
        $this->assertEquals('userone@userone.com.br', $user->email);
        $this->assertTrue(Hash::check('123456', Hash::make($user->password)));
        $this->assertEquals(true, $user->is_admin);
        $this->assertEquals(true, $user->active);

        $response->assertRedirect($this->userEditGetRoute($user->id));
        $response->assertSessionHasErrors(['email' => 'The email must be a valid email address.']);

        $this->assertTrue(session()->hasOldInput('name'));
        $this->assertTrue(session()->hasOldInput('is_admin'));
        $this->assertTrue(session()->hasOldInput('active'));
    }

    /* @test */
    public function it_admin_edit_a_user_without_password()
    {
        $user = factory(User::class)->create([
            'name'                  => 'User One',
            'email'                 => 'userone@userone.com.br',
            'password'              => '123456',
            'is_admin'              => true,
            'active'                => true,
        ]);

        $response = $this->actingAs($this->admin)->put($this->userUpdateRoute($user->id), [
            'name'                  => 'User Two',
            'email'                 => 'usertwo@usertwo.com.br',
            'password'              => '',
            'password_confirmation' => '',
            'is_admin'              => false,
            'active'                => false,
        ]);

        $this->assertCount(1, $users = User::all());

        $user = $users->first();

        $this->assertEquals('User Two', $user->name);
        $this->assertEquals('usertwo@usertwo.com.br', $user->email);
        $this->assertTrue(Hash::check('1234567', $user->password));
        $this->assertEquals(false, $user->is_admin);
        $this->assertEquals(false, $user->active);

        $response->assertRedirect($this->userIndexGetRoute());
        $response->assertSessionHas('message', [
            'type' => 'success',
            'message' => 'User successfully updated.',
        ]);
    }

    /* @test */
    public function it_admin_cannot_edit_a_user_whose_password_doesnt_have_the_minimum_of_characters()
    {
        $user = factory(User::class)->create([
            'name'                  => 'User One',
            'email'                 => 'userone@userone.com.br',
            'password'              => '123456',
            'is_admin'              => true,
            'active'                => true,
        ]);

        $response = $this->actingAs($this->admin)->from($this->userEditGetRoute($user->id))->put($this->userUpdateRoute($user->id), [
            'name'                  => 'User Two',
            'email'                 => 'usertwo@usertwo.com.br',
            'password'              => '1234',
            'password_confirmation' => '1234',
            'is_admin'              => false,
            'active'                => false,
        ]);

        $this->assertCount(1, $users = User::all());

        $user = $users->first();

        $this->assertEquals('User One', $user->name);
        $this->assertEquals('userone@userone.com.br', $user->email);
        $this->assertTrue(Hash::check('123456', Hash::make($user->password)));
        $this->assertEquals(true, $user->is_admin);
        $this->assertEquals(true, $user->active);

        $response->assertRedirect($this->userEditGetRoute($user->id));
        $response->assertSessionHasErrors(['password' => 'The password must be at least 6 characters.']);

        $this->assertTrue(session()->hasOldInput('name'));
        $this->assertTrue(session()->hasOldInput('email'));
        $this->assertTrue(session()->hasOldInput('is_admin'));
        $this->assertTrue(session()->hasOldInput('active'));
    }

    /* @test */
    public function it_admin_cannot_edit_a_user_when_the_password_confirmation_doesnt_match()
    {
        $user = factory(User::class)->create([
            'name'                  => 'User One',
            'email'                 => 'userone@userone.com.br',
            'password'              => '123456',
            'is_admin'              => true,
            'active'                => true,
        ]);

        $response = $this->actingAs($this->admin)->from($this->userEditGetRoute($user->id))->put($this->userUpdateRoute($user->id), [
            'name'                  => 'User Two',
            'email'                 => 'usertwo@usertwo.com.br',
            'password'              => '123456',
            'password_confirmation' => '1234567',
            'is_admin'              => false,
            'active'                => false,
        ]);

        $this->assertCount(1, $users = User::all());

        $user = $users->first();

        $this->assertEquals('User One', $user->name);
        $this->assertEquals('userone@userone.com.br', $user->email);
        $this->assertTrue(Hash::check('123456', Hash::make($user->password)));
        $this->assertEquals(true, $user->is_admin);
        $this->assertEquals(true, $user->active);

        $response->assertRedirect($this->userEditGetRoute($user->id));
        $response->assertSessionHasErrors(['password' => 'The password confirmation does not match.']);

        $this->assertTrue(session()->hasOldInput('name'));
        $this->assertTrue(session()->hasOldInput('email'));
        $this->assertTrue(session()->hasOldInput('is_admin'));
        $this->assertTrue(session()->hasOldInput('active'));
    }

    /** @test */
    public function it_admin_can_delete_a_user()
    {
        $user = factory(User::class)->create();

        $response = $this->actingAs($this->admin)->from($this->userIndexGetRoute())->delete($this->userDeleteRoute($user->id));

        $this->assertCount(0, User::all());
        $response->assertRedirect($this->userIndexGetRoute());
        $response->assertSessionHas('message', [
            'type' => 'success',
            'message' => 'User deleted successfully.',
        ]);
    }
}
