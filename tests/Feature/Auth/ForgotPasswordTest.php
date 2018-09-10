<?php

namespace Tests\Feature\Auth;

use App\Forum\User\Models\User;
use Tests\TestCase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ForgotPasswordTest extends TestCase
{
    use RefreshDatabase;

    protected function passwordRequestRoute()
    {
        return route('password.request');
    }

    protected function passwordEmailPostRoute()
    {
        return route('password.email');
    }

    protected function passwordEmailGetRoute()
    {
        return route('password.email');
    }

    protected function guestMiddlewareRoute()
    {
        return route('manager.dashboard');
    }

    public function test_user_can_view_an_email_password_form()
    {
        $response = $this->get($this->passwordRequestRoute());

        $response->assertSuccessful();
        $response->assertViewIs('auth.passwords.email');
    }

    public function test_user_cannot_view_an_email_password_form_when_authenticated()
    {
        $response = $this->actingAs($this->user)->get($this->passwordRequestRoute());
        $response->assertRedirect($this->guestMiddlewareRoute());
    }

    public function test_user_receives_an_email_with_a_password_reset_link()
    {
        Notification::fake();

        $user = factory(User::class)->create([
            'email' => 'rafaelzorn@example.com',
        ]);

        $this->post($this->passwordEmailPostRoute(), [
            'email' => $user->email,
        ]);

        $this->assertNotNull($token = DB::table('password_resets')->first());

        Notification::assertSentTo($user, ResetPassword::class, function ($notification, $channels) use ($token) {
            return Hash::check($notification->token, $token->token) === true;
        });
    }

    public function test_email_is_required()
    {
        $response = $this->from($this->passwordEmailGetRoute())->post($this->passwordEmailPostRoute(), []);

        $response->assertRedirect($this->passwordEmailGetRoute());
        $response->assertSessionHasErrors(['email' => 'The email field is required.']);
    }

    public function test_email_is_a_valid_email()
    {
        $response = $this->from($this->passwordEmailGetRoute())->post($this->passwordEmailPostRoute(), [
            'email' => 'invalid-email',
        ]);

        $response->assertRedirect($this->passwordEmailGetRoute());
        $response->assertSessionHasErrors('email');
    }
}
