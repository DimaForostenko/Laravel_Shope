<?php

namespace Tests\Unit;

use App\Http\Controllers\API\AuthController;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthControllerTest extends TestCase
{
    public function test_register_creates_user_and_returns_token()
    {
        $data = [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ];

        $request = Request::create('/api/register', 'POST', $data);
        $controller = new AuthController();
        $response = $controller->register($request);

        $this->assertEquals(201, $response->getStatusCode());
        $this->assertArrayHasKey('user', $response->getData(true));
        $this->assertArrayHasKey('token', $response->getData(true));
        $this->assertDatabaseHas('users', ['email' => 'john@example.com']);
    }

    public function test_login_with_valid_credentials_returns_token()
    {
        $user = User::factory()->create([
            'password' => Hash::make('password123'),
        ]);

        $data = [
            'email' => $user->email,
            'password' => 'password123',
        ];

        $request = Request::create('/api/login', 'POST', $data);
        $controller = new AuthController();
        $response = $controller->login($request);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertArrayHasKey('user', $response->getData(true));
        $this->assertArrayHasKey('token', $response->getData(true));
    }

    public function test_login_with_invalid_credentials_returns_error()
    {
        $user = User::factory()->create([
            'password' => Hash::make('password123'),
        ]);

        $data = [
            'email' => $user->email,
            'password' => 'wrongpassword',
        ];

        $request = Request::create('/api/login', 'POST', $data);
        $controller = new AuthController();
        $response = $controller->login($request);

        $this->assertEquals(401, $response->getStatusCode());
        $this->assertEquals('Invalid credentials', $response->getData(true)['message']);
    }

    public function test_logout_deletes_token()
    {
        $user = User::factory()->create();
        $token = $user->createToken('auth_token')->plainTextToken;

        $request = Request::create('/api/logout', 'POST');
        $request->headers->set('Authorization', 'Bearer ' . $token);
        $controller = new AuthController();
        $response = $controller->logout($request);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('Logged out', $response->getData(true)['message']);
    }
}