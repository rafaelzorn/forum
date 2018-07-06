<?php

namespace Tests\Feature\Auth;

use App\Forum\User\Models\User;
use Illuminate\Auth\Events\Lockout;
use Tests\TestCase;

class AuthFeatureTest extends TestCase
{
    public function test_show_the_login_form()
    {
        $this->get(route('login'))
            ->assertStatus(200)
            ->assertSee('E-mail')
            ->assertSee('Password')
            ->assertSee('Keep logged in')
            ->assertSee('Log In')
            ->assertSee('Forgot your password?');
    }

    public function test_if_login_successful()
    {
        $data = [
            'email'    => $this->user->email,
            'password' => 'secret'
        ];

        $this->post(route('login'), $data)
            ->assertStatus(302)
            ->assertRedirect(route('manager.dashboard'));
    }

    public function test_errors_login_without_email_or_password()
    {
        $this->post('login', [])
            ->assertSessionHasErrors([
                'email'    => 'The email field is required.',
                'password' => 'The password field is required.'
            ]);
    }

    public function test_throws_the_event_when_too_many_attempts_login()
    {
        $this->expectsEvents(Lockout::class);

        $user = factory(User::class)->create();

        for ($i=0; $i <= 5; $i++) {
            $data = [
                'email'    => $user->email,
                'password' => 'unknown'
            ];

            $this->post(route('login'), $data);
        }
    }

    public function test_logout_successful()
    {
        $this->actingAs($this->user, 'web')
            ->get(route('logout'))
            ->assertStatus(302)
            ->assertRedirect(route('home'));
    }

    public function test_show_the_register_form()
    {
        $this->get(route('register'))
            ->assertStatus(200)
            ->assertSee('Name')
            ->assertSee('E-mail')
            ->assertSee('Password')
            ->assertSee('Confirm Password')
            ->assertSee('Register');
    }

    public function test_if_register_successful()
    {
        $data = [
            'name'                   => $this->faker->name,
            'email'                  => $this->faker->email,
            'password'               => 'secret',
            'password_confirmation'  => 'secret'
        ];

        $this->post(route('register'), $data)
            ->assertStatus(302)
            ->assertRedirect(route('manager.dashboard'));
    }

    public function test_errors_register_no_filled_fields()
    {
        $this->post('register', [])
            ->assertSessionHasErrors([
                'name'     => 'The name field is required.',
                'email'    => 'The email field is required.',
                'password' => 'The password field is required.'
            ]);
    }
}
