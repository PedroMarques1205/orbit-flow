<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class UserControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_list_all_users()
    {
        User::factory()->count(3)->create();

        $response = $this->getJson('/api/v1/users');

        $response->assertStatus(200)
                 ->assertJsonCount(3);
    }

    public function test_can_create_a_user()
    {
        $data = [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => 'password123'
        ];

        $response = $this->postJson('/api/v1/users', $data);

        $response->assertStatus(201)
                 ->assertJsonFragment(['email' => 'john@example.com']);

        $this->assertDatabaseHas('users', ['email' => 'john@example.com']);
    }

    public function test_can_show_a_user()
    {
        $user = User::factory()->create();

        $response = $this->getJson("/api/v1/users/{$user->id}");

        $response->assertStatus(200)
                 ->assertJsonFragment(['email' => $user->email]);
    }

    public function test_can_update_a_user()
    {
        $user = User::factory()->create();

        $data = ['name' => 'Updated Name'];

        $response = $this->putJson("/api/v1/users/{$user->id}", $data);

        $response->assertStatus(200)
                 ->assertJsonFragment(['name' => 'Updated Name']);

        $this->assertDatabaseHas('users', ['id' => $user->id, 'name' => 'Updated Name']);
    }

    public function test_can_delete_a_user()
    {
        $user = User::factory()->create();

        $response = $this->deleteJson("/api/v1/users/{$user->id}");

        $response->assertStatus(200)
                 ->assertJsonFragment(['message' => 'Usuário removido com sucesso.']);

        $this->assertDatabaseMissing('users', ['id' => $user->id]);
    }

    public function test_login_with_invalid_credentials()
    {
        $user = User::factory()->create([
            'password' => Hash::make('secret123')
        ]);

        $response = $this->postJson('/api/v1/users/login', [
            'email' => $user->email,
            'password' => 'wrongpass',
        ]);

        $response->assertStatus(405);
    }
}
