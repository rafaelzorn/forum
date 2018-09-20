<?php

namespace Tests\Feature\Auth;

use App\Forum\User\Models\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    private function successfulLoginRoute()
    {
        return route('manager.dashboard');
    }

    private function loginGetRoute()
    {
        return route('login');
    }

    private function loginPostRoute()
    {
        return route('login');
    }

    private function logoutRoute()
    {
        return route('logout');
    }

    private function guestMiddlewareRoute()
    {
        return route('manager.dashboard');
    }

    private function successfulLogoutRoute()
    {
        return '/';
    }

    /** @test */
    public function it_user_can_view_a_login_form()
    {
        $response = $this->get($this->loginGetRoute());

        $response->assertSuccessful();
        $response->assertViewIs('auth.login');
    }

    /** @test */
    public function it_user_cannot_view_a_login_form_when_authenticated()
    {
        $response = $this->actingAs($this->user)->get($this->loginGetRoute());
        $response->assertRedirect($this->guestMiddlewareRoute());
    }

    /** @test */
    public function it_user_can_login_with_correct_credentials()
    {
        $user = factory(User::class)->create([
            'password' => bcrypt($password = 'laravel-forever'),
        ]);

        $response = $this->post($this->loginPostRoute(), [
            'email' => $user->email,
            'password' => $password,
        ]);

        $response->assertRedirect($this->successfulLoginRoute());
        $this->assertAuthenticatedAs($user);
    }

    /** @test */
    public function it_user_cannot_login_with_email_that_does_not_exist()
    {
        $response = $this->from($this->loginGetRoute())->post($this->loginPostRoute(), [
            'email' => 'nobody@example.com',
            'password' => 'invalid-password',
        ]);

        $response->assertRedirect($this->loginGetRoute());
        $response->assertSessionHasErrors(['email' => 'These credentials do not match our records.']);
        $this->assertTrue(session()->hasOldInput('email'));
        $this->assertFalse(session()->hasOldInput('password'));

        $this->assertGuest();
    }

    /** @test */
    public function it_email_and_password_is_required()
    {
        $response = $this->from($this->loginGetRoute())->post($this->loginPostRoute(), []);

        $response->assertRedirect($this->loginGetRoute());
        $response->assertSessionHasErrors([
            'email' => 'The email field is required.',
            'password' => 'The password field is required.',
        ]);

        $this->assertGuest();
    }

    /** @test */
    public function it_user_can_logout()
    {
        $this->be($this->user);

        $response = $this->post($this->logoutRoute());

        $response->assertRedirect($this->successfulLogoutRoute());
        $this->assertGuest();
    }

    /** @test */
    public function it_user_cannot_logout_when_not_authenticated()
    {
        $response = $this->post($this->logoutRoute());

        $response->assertRedirect($this->successfulLogoutRoute());
        $this->assertGuest();
    }

    /** @test */
    public function it_user_cannot_make_more_than_five_attempts_in_one_minute()
    {
        for ($i=0; $i <= 5; $i++) {
            $response = $this->from($this->loginGetRoute())->post($this->loginPostRoute(), [
                'email' => $this->user->email,
                'password' => 'invalid-password',
            ]);
        }

        $response->assertRedirect($this->loginGetRoute());
        $response->assertSessionHasErrors('email');

        $this->assertContains(
            'Too many login attempts.',
            collect(
                $response
                ->baseResponse
                ->getSession()
                ->get('errors')
                ->getBag('default')
                ->get('email')
            )->first()
        );

        $this->assertTrue(session()->hasOldInput('email'));
        $this->assertFalse(session()->hasOldInput('password'));
        $this->assertGuest();
    }
}
