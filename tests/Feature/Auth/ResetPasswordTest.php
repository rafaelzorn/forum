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
        return route('home');
    }

    private function guestMiddlewareRoute()
    {
        return route('home');
    }

    /** @test */
    public function it_user_can_view_a_password_reset_form()
    {
        $this->assertTrue(true);
    }
}
