<?php

namespace Tests\Feature\Auth;

use App\Forum\User\Models\User;
use Tests\TestCase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Event;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RegisterTest extends TestCase
{
    use RefreshDatabase;

    protected function successfulRegistrationRoute()
    {
        return route('manager.dashboard');
    }

    protected function registerGetRoute()
    {
        return route('register');
    }

    protected function registerPostRoute()
    {
        return route('register');
    }

    protected function guestMiddlewareRoute()
    {
        return route('manager.dashboard');
    }

    public function test_user_can_view_a_registration_form()
    {
        $response = $this->get($this->registerGetRoute());

        $response->assertSuccessful();
        $response->assertViewIs('auth.register');
    }

    public function test_user_cannot_view_a_registration_form_when_authenticated()
    {
        $user = factory(User::class)->make();

        $response = $this->actingAs($user)->get($this->registerGetRoute());
        $response->assertRedirect($this->guestMiddlewareRoute());
    }

    public function test_user_can_register()
    {
        Event::fake();

        $response = $this->post($this->registerPostRoute(), [
            'name' => 'Rafael Zorn',
            'email' => 'rafael.zorn@example.com',
            'password' => 'laravel-forever',
            'password_confirmation' => 'laravel-forever',
        ]);

        $response->assertRedirect($this->successfulRegistrationRoute());

        $this->assertCount(1, $users = User::all());
        $this->assertAuthenticatedAs($user = $users->first());
        $this->assertEquals('Rafael Zorn', $user->name);
        $this->assertEquals('rafael.zorn@example.com', $user->email);
        $this->assertTrue(Hash::check('laravel-forever', $user->password));
        
        Event::assertDispatched(Registered::class, function ($e) use ($user) {
            return $e->user->id === $user->id;
        });
    }

    public function test_user_cannot_register_without_name()
    {
        $response = $this->from($this->registerGetRoute())->post($this->registerPostRoute(), [
            'name' => '',
            'email' => 'rafael.zorn@example.com',
            'password' => 'laravel-forever',
            'password_confirmation' => 'laravel-forever',
        ]);

        $this->assertCount(0, User::all());
        $response->assertRedirect($this->registerGetRoute());
        $response->assertSessionHasErrors(['name' => 'The name field is required.']);
        $this->assertTrue(session()->hasOldInput('email'));
        $this->assertFalse(session()->hasOldInput('password'));
        $this->assertGuest();
    }

    public function test_user_cannot_register_without_email()
    {
        $response = $this->from($this->registerGetRoute())->post($this->registerPostRoute(), [
            'name' => 'Rafael Zorn',
            'email' => '',
            'password' => 'laravel-forever',
            'password_confirmation' => 'laravel-forever',
        ]);

        $this->assertCount(0, User::all());
        $response->assertRedirect($this->registerGetRoute());
        $response->assertSessionHasErrors(['email' => 'The email field is required.']);
        $this->assertTrue(session()->hasOldInput('name'));
        $this->assertFalse(session()->hasOldInput('password'));
        $this->assertGuest();
    }

    public function test_user_cannot_register_with_invalid_email()
    {
        $response = $this->from($this->registerGetRoute())->post($this->registerPostRoute(), [
            'name' => 'Rafael Zorn',
            'email' => 'invalid-email',
            'password' => 'laravel-forever',
            'password_confirmation' => 'laravel-forever',
        ]);

        $this->assertCount(0, User::all());
        $response->assertRedirect($this->registerGetRoute());
        $response->assertSessionHasErrors(['email' => 'The email must be a valid email address.']);
        $this->assertTrue(session()->hasOldInput('name'));
        $this->assertTrue(session()->hasOldInput('email'));
        $this->assertFalse(session()->hasOldInput('password'));
        $this->assertGuest();
    }

    public function test_user_cannot_register_without_password()
    {
        $response = $this->from($this->registerGetRoute())->post($this->registerPostRoute(), [
            'name' => 'Rafael Zorn',
            'email' => 'rafael.zorn@example.com',
            'password' => '',
            'password_confirmation' => '',
        ]);

        $this->assertCount(0, User::all());
        $response->assertRedirect($this->registerGetRoute());
        $response->assertSessionHasErrors(['password' => 'The password field is required.']);
        $this->assertTrue(session()->hasOldInput('name'));
        $this->assertTrue(session()->hasOldInput('email'));
        $this->assertFalse(session()->hasOldInput('password'));
        $this->assertGuest();
    }

    public function test_user_cannot_register_without_password_confirmation()
    {
        $response = $this->from($this->registerGetRoute())->post($this->registerPostRoute(), [
            'name' => 'Rafael Zorn',
            'email' => 'rafael.zorn@example.com',
            'password' => 'laravel-forever',
            'password_confirmation' => '',
        ]);

        $this->assertCount(0, User::all());
        $response->assertRedirect($this->registerGetRoute());
        $response->assertSessionHasErrors(['password' => 'The password confirmation does not match.']);
        $this->assertTrue(session()->hasOldInput('name'));
        $this->assertTrue(session()->hasOldInput('email'));
        $this->assertFalse(session()->hasOldInput('password'));
        $this->assertGuest();
    }

    public function testUserCannotRegisterWithPasswordsNotMatching()
    {
        $response = $this->from($this->registerGetRoute())->post($this->registerPostRoute(), [
            'name' => 'Rafael Zorn',
            'email' => 'rafael.zorn@example.com',
            'password' => 'laravel-forever',
            'password_confirmation' => 'laravel-forever-1',
        ]);

        $this->assertCount(0, User::all());
        $response->assertRedirect($this->registerGetRoute());
        $response->assertSessionHasErrors(['password' => 'The password confirmation does not match.']);
        $this->assertTrue(session()->hasOldInput('name'));
        $this->assertTrue(session()->hasOldInput('email'));
        $this->assertFalse(session()->hasOldInput('password'));
        $this->assertGuest();
    }
}
