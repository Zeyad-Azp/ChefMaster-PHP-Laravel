<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Tests\TestCase;

/**
 * Regression tests for AuthController fixes.
 *
 * Covers:
 *  - Bug 3: Session ID is regenerated after register (prevents session fixation)
 *  - Bug 3: Session ID is regenerated after login as well
 */
class AuthRegressionTest extends TestCase
{
    use RefreshDatabase;

    /** Bug 3 — register() must rotate the session ID. */
    public function test_register_regenerates_session_id(): void
    {
        // Establish a session first.
        $this->get('/register')->assertOk();
        $oldSessionId = Session::getId();

        $response = $this->post('/register', [
            'fullname'              => 'Test Chef',
            'email'                 => 'chef@example.com',
            'password'              => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertRedirect(route('home'));
        $this->assertAuthenticated();
        $this->assertNotSame($oldSessionId, Session::getId(), 'Session ID was not rotated on register.');
    }

    /** Bug 3 — login() must rotate the session ID. */
    public function test_login_regenerates_session_id(): void
    {
        User::factory()->create([
            'email'    => 'login@example.com',
            'password' => Hash::make('password123'),
        ]);

        $this->get('/login')->assertOk();
        $oldSessionId = Session::getId();

        $response = $this->post('/login', [
            'email'    => 'login@example.com',
            'password' => 'password123',
        ]);

        $response->assertRedirect(route('home'));
        $this->assertAuthenticated();
        $this->assertNotSame($oldSessionId, Session::getId(), 'Session ID was not rotated on login.');
    }

    /** Bug 3 — bad credentials do not rotate the session ID and do not authenticate. */
    public function test_failed_login_does_not_authenticate(): void
    {
        User::factory()->create([
            'email'    => 'login@example.com',
            'password' => Hash::make('correct-password'),
        ]);

        $response = $this->post('/login', [
            'email'    => 'login@example.com',
            'password' => 'wrong-password',
        ]);

        $response->assertSessionHasErrors('email');
        $this->assertGuest();
    }
}
