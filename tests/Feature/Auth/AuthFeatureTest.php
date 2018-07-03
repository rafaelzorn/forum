<?php

namespace Tests\Feature\Auth;

use App\Forum\User\Models\User;
use Illuminate\Auth\Events\Lockout;
use Tests\TestCase;

class AuthFeatureTest extends TestCase
{
    public function test_shows_the_login_form()
    {
        $this->get(route('login'))
            ->assertStatus(200)
            ->assertSee('E-mail')
            ->assertSee('Password')
            ->assertSee('Keep logged in')
            ->assertSee('Log In')
            ->assertSee('Forgot your password?');
    }

    public function tests_throws_the_too_many_login_attempts_event()
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

    public function tests_can_show_the_user_dashboard()
    {
        $this->actingAs($this->user, 'web')
            ->get(route('dashboard'))
            ->assertStatus(200);
    }

    public function tests_can_go_to_my_users_page_on_successful_login()
    {
        $user = factory(User::class)->create();

        $data = [
            'email'    => $user->email,
            'password' => 'secret'
        ];

        $this->post(route('login'), $data)
            ->assertStatus(302)
            ->assertRedirect(route('dashboard'));
    }

    public function tests_errors_when_the_users_is_logging_in_without_the_email_or_password()
    {
        $this->post('login', [])
            ->assertSessionHasErrors([
                'email'    => 'The email field is required.',
                'password' => 'The password field is required.'
            ]);
    }
}
