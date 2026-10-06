<?php

namespace Tests\Feature;

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
}
