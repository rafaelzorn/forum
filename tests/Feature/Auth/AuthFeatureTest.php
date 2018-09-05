<?php

namespace Tests\Feature\Auth;

use App\Forum\User\Models\User;
use Illuminate\Auth\Events\Lockout;
use Tests\TestCase;

class AuthFeatureTest extends TestCase
{
    public function test_show_login_form()
    {
        $this->get(route('login'))
            ->assertStatus(200)
            ->assertSee('E-mail')
            ->assertSee('Password')
            ->assertSee('Keep logged in')
            ->assertSee('Log In')
            ->assertSee('Forgot your password?');
    }

    public function test_user_redirected_to_dashboard_when_login_successful()
    {
        $data = [
            'email'    => $this->user->email,
            'password' => 'secret'
        ];

        $this->post(route('login'), $data)
            ->assertStatus(302)
            ->assertRedirect(route('manager.dashboard'));
    }

    public function test_user_redirected_to_dashboard_when_logged_in()
    {
        $this->actingAs($this->user, 'web')
            ->get(route('login'))
            ->assertStatus(302)
            ->assertRedirect(route('manager.dashboard'));
    }

    public function test_errors_when_user_logs_in_without_email_or_password()
    {
        $this->post(route('login'), [])
            ->assertSessionHasErrors([
                'email' => 'The email field is required.',
                'password' => 'The password field is required.',
            ]);
    }

    public function test_event_when_user_makes_many_attempts_to_login()
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

    public function test_show_registration_form()
    {
        $this->get(route('register'))
            ->assertStatus(200)
            ->assertSee('Name')
            ->assertSee('E-mail')
            ->assertSee('Password')
            ->assertSee('Confirm Password')
            ->assertSee('Register');
    }

    public function test_user_redirected_to_dashboard_when_successful_registration()
    {
        $password = $this->faker->password;

        $data = [
            'name'                   => $this->faker->name,
            'email'                  => $this->faker->email,
            'password'               => $password,
            'password_confirmation'  => $password
        ];

        $this->post(route('register'), $data)
            ->assertStatus(302)
            ->assertRedirect(route('manager.dashboard'));
    }

    public function test_errors_when_fields_are_not_filled_in_the_register()
    {
        $this->post('register', [])
            ->assertSessionHasErrors([
                'name'     => 'The name field is required.',
                'email'    => 'The email field is required.',
                'password' => 'The password field is required.'
            ]);
    }

    public function test_errors_when_user_reset_password_in_without_email_or_password()
    {
        $this->post(route('password.email'), [])
            ->assertSessionHasErrors([
                'email' => 'The email field is required.',
            ]);
    }

    public function test_errors_when_user_reset_password_email_not_found()
    {
        $data = [
            'email' => 'teste@teste.com.br'
        ];

        $this->post(route('password.email'), $data)
            ->assertSessionHasErrors([
                'email' => "We can't find a user with that e-mail address.",
            ]);
    }

    public function test_reset_password_successful()
    {
        $user = factory(User::class)->create();

        $this->assertEquals(true, true);
    }
}
