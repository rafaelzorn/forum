<?php

namespace Tests\Feature\Auth;

use App\Forum\User\Models\User;
use Tests\TestCase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Password;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ResetPasswordTest extends TestCase
{
    use RefreshDatabase;

    private function getValidToken($user)
    {
        return Password::broker()->createToken($user);
    }

    private function getInvalidToken()
    {
        return 'invalid-token';
    }

    private function passwordResetGetRoute($token)
    {
        return route('password.reset', $token);
    }

    private function passwordResetPostRoute()
    {
        return '/password/reset';
    }

    private function successfulPasswordResetRoute()
    {
        return route('manager.dashboard');
    }

    private function guestMiddlewareRoute()
    {
        return route('manager.dashboard');
    }

    /** @test */
    public function it_user_can_view_a_password_reset_form()
    {
        $user = factory(User::class)->create();

        $response = $this->get($this->passwordResetGetRoute($token = $this->getValidToken($user)));
        $response->assertSuccessful();

        $response->assertViewIs('auth.passwords.reset');
        $response->assertViewHas('token', $token);
    }

    /** @test */
    public function it_user_cannot_view_a_password_reset_form_when_authenticated()
    {
        $user = factory(User::class)->create();

        $response = $this->actingAs($user)->get($this->passwordResetGetRoute($this->getValidToken($user)));

        $response->assertRedirect($this->guestMiddlewareRoute());
    }

    /** @test */
    public function it_user_can_reset_password_with_valid_token()
    {
        Event::fake();

        $user = factory(User::class)->create();

        $response = $this->post($this->passwordResetPostRoute(), [
            'token' => $this->getValidToken($user),
            'email' => $user->email,
            'password' => 'new-password',
            'password_confirmation' => 'new-password',
        ]);

        $response->assertRedirect($this->successfulPasswordResetRoute());

        $this->assertEquals($user->email, $user->fresh()->email);
        $this->assertTrue(Hash::check('new-password', $user->fresh()->password));
        $this->assertAuthenticatedAs($user);

        Event::assertDispatched(PasswordReset::class, function ($e) use ($user) {
            return $e->user->id === $user->id;
        });
    }

    /** @test */
    public function it_user_cannot_reset_password_with_invalid_token()
    {
        $user = factory(User::class)->create([
            'password' => bcrypt('old-password'),
        ]);

        $response = $this->from($this->passwordResetGetRoute($this->getInvalidToken()))->post($this->passwordResetPostRoute(), [
            'token' => $this->getInvalidToken(),
            'email' => $user->email,
            'password' => 'new-password',
            'password_confirmation' => 'new-password',
        ]);

        $response->assertRedirect($this->passwordResetGetRoute($this->getInvalidToken()));

        $this->assertEquals($user->email, $user->fresh()->email);
        $this->assertTrue(Hash::check('old-password', $user->fresh()->password));
        $this->assertGuest();
    }

    /** @test */
    public function it_user_cannot_reset_password_without_providing_a_new_password()
    {
        $user = factory(User::class)->create([
            'password' => bcrypt('old-password'),
        ]);

        $response = $this->from($this->passwordResetGetRoute($token = $this->getValidToken($user)))->post($this->passwordResetPostRoute(), [
            'token' => $token,
            'email' => $user->email,
            'password' => '',
            'password_confirmation' => '',
        ]);

        $response->assertRedirect($this->passwordResetGetRoute($token));

        $response->assertSessionHasErrors(['password' => 'The password field is required.']);
        $this->assertTrue(session()->hasOldInput('email'));
        $this->assertFalse(session()->hasOldInput('password'));
        $this->assertEquals($user->email, $user->fresh()->email);
        $this->assertTrue(Hash::check('old-password', $user->fresh()->password));
        $this->assertGuest();
    }

    /** @test */
    public function it_user_cannot_reset_password_without_proving_an_email()
    {
        $user = factory(User::class)->create([
            'password' => bcrypt('old-password'),
        ]);

        $response = $this->from($this->passwordResetGetRoute($token = $this->getValidToken($user)))->post($this->passwordResetPostRoute(), [
            'token' => $token,
            'email' => '',
            'password' => 'new-password',
            'password_confirmation' => 'new-password',
        ]);

        $response->assertRedirect($this->passwordResetGetRoute($token));

        $response->assertSessionHasErrors(['email' => 'The email field is required.']);
        $this->assertFalse(session()->hasOldInput('password'));
        $this->assertEquals($user->email, $user->fresh()->email);
        $this->assertTrue(Hash::check('old-password', $user->fresh()->password));
        $this->assertGuest();
    }
}
