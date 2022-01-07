<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Factories\UserFactory;
use Hash;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Str;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Assert that login endpoint is valid.
     *
     * @return void
     */
    public function test_login_endpoint()
    {
        $response = $this->get('/login');

        $response->assertStatus(200);
    }

    public function test_register_endpoint()
    {
        $response = $this->get('/register');

        $response->assertStatus(200);
    }

    public function test_can_register()
    {
        $response = $this->post('/register', [
            'email' => 'test@example.com',
            'name' => 'Test User',
            'password' => '1234567890',
            'password-confirm' => '134567890'
        ]);

        $response->assertRedirect('/');
    }

    public function test_can_login()
    {
        $user = User::create([
            'email' => Str::random() . '@example.com',
            'name' => 'Test User',
            'password' => Hash::make('1234567890')
        ]);

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => '1234567890'
        ]);

        $response->assertRedirect('/');
    }

    public function test_cannot_login_with_invalid_credentials()
    {
        $user = User::create([
            'email' => Str::random() . '@example.com',
            'name' => 'Test User',
            'password' => Hash::make('1234567890')
        ]);

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'incorrect-password'
        ]);

        $response->assertRedirect('/');
    }

    public function test_redirected_if_invalid_email()
    {
        $response = $this->from('/login')->post('/login', [
            'email' => 'invalid-email',
            'password' => '1234567890'
        ]);

        $response->assertRedirect('/login');
        $response->assertSessionHasErrors('email');
    }

    public function test_can_logout()
    {
        $user = User::create([
            'email' => Str::random() . '@example.com',
            'name' => 'Test User',
            'password' => Hash::make('1234567890')
        ]);

        $response = $this->actingAs($user)->post('/logout');
        $response->assertRedirect('/');
    }
}
