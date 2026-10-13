<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use App\Models\User;
use Tests\TestCase;

class AuthLoginValidationTest extends TestCase
{
    public function test_login_rejects_an_invalid_email(): void
    {
        $response = $this->from('/login')->post(route('login.process'), [
            'email' => 'not-an-email',
            'password' => 'password',
        ]);

        $response->assertRedirect('/login')
            ->assertSessionHasErrors('email');
    }

    public function test_login_requires_email_and_password(): void
    {
        $response = $this->from('/login')->post(route('login.process'), []);

        $response->assertRedirect('/login')
            ->assertSessionHasErrors(['email', 'password']);
    }

    public function test_login_is_throttled_after_five_failed_attempts(): void
    {
        $throttleKey = 'user@example.com|127.0.0.1';
        RateLimiter::clear($throttleKey);
        Auth::shouldReceive('attempt')->times(5)->andReturn(false);

        foreach (range(1, 5) as $attempt) {
            $this->from('/login')->post(route('login.process'), [
                'email' => 'user@example.com',
                'password' => 'incorrect-password',
            ])->assertRedirect('/login');
        }

        $this->from('/login')->post(route('login.process'), [
            'email' => 'user@example.com',
            'password' => 'incorrect-password',
        ])->assertRedirect('/login')
            ->assertSessionHasErrors('email');
    }

    public function test_logout_only_accepts_post_requests(): void
    {
        $this->get(route('logout'))->assertStatus(405);
    }

    public function test_authenticated_user_can_log_out_with_post(): void
    {
        $this->actingAs(new User(['name' => 'Test User', 'email' => 'user@example.com']))
            ->post(route('logout'))
            ->assertRedirect(route('login'));

        $this->assertGuest();
    }
}
