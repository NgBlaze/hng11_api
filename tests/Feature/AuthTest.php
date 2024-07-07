<?php


namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_should_register_user_successfully_with_default_organisation()
    {
        $response = $this->postJson('/api/auth/register', [
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'john.doe@example.com',
            'password' => 'password123',
        ]);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'user' => ['id', 'first_name', 'last_name', 'email', 'organisation_id'],
                'access_token',
            ]);

        $this->assertDatabaseHas('users', ['email' => 'john.doe@example.com']);
        $this->assertDatabaseHas('organisations', ['name' => "John's Organisation"]);
    }

    public function test_it_should_log_the_user_in_successfully()
    {
        $user = User::factory()->create([
            'email' => 'john.doe@example.com',
            'password' => bcrypt('password123'),
        ]);

        $response = $this->postJson('/api/auth/login', [
            'email' => 'john.doe@example.com',
            'password' => 'password123',
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'user' => ['id', 'first_name', 'last_name', 'email'],
                'access_token',
            ]);
    }

    public function test_it_should_fail_if_required_fields_are_missing()
    {
        $response = $this->postJson('/api/auth/register', [
            'first_name' => 'John',
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['last_name', 'email', 'password']);
    }

    public function test_it_should_fail_if_there_is_duplicate_email()
    {
        User::factory()->create(['email' => 'john.doe@example.com']);

        $response = $this->postJson('/api/auth/register', [
            'first_name' => 'Jane',
            'last_name' => 'Doe',
            'email' => 'john.doe@example.com',
            'password' => 'password123',
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['email']);
    }
}
