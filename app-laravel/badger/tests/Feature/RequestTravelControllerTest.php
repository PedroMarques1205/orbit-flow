<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\RequestTravel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RequestTravelControllerTest extends TestCase
{
    use RefreshDatabase;

    
    public function test_cannot_create_travel_with_invalid_dates()
    {
        $user = User::factory()->create();

        $data = [
            'destino' => 'Salvador',
            'data_ida' => '2025-06-10',
            'data_volta' => '2025-06-01',
            'email_solicitante' => $user->email,
        ];

        $response = $this->postJson('/api/v1/request-travels', $data);

        $response->assertStatus(422);
    }

    public function test_can_show_a_travel_request()
    {
        $requestTravel = RequestTravel::factory()->create();

        $response = $this->getJson("/api/v1/request-travels/{$requestTravel->id}");

        $response->assertStatus(200)
                 ->assertJsonFragment(['destino' => $requestTravel->destino]);
    }

    public function test_can_list_travel_requests_with_filters()
    {
        RequestTravel::factory()->create([
            'destino' => 'Fortaleza',
            'data_ida' => '2025-06-01',
            'status' => 'solicitado'
        ]);

        $response = $this->getJson('/api/v1/request-travels?status=solicitado&destino=fort');

        $response->assertStatus(200)
                 ->assertJsonCount(1, 'data');
    }

    public function test_cannot_update_own_travel_request()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $requestTravel = RequestTravel::factory()->create([
            'user_id' => $user->id,
            'status' => 'solicitado'
        ]);

        $response = $this->putJson("/api/v1/request-travels/{$requestTravel->id}", [
            'status' => 'cancelado'
        ]);

        $response->assertStatus(403)
                 ->assertJsonFragment(['error' => 'Você não pode alterar seu próprio pedido.']);
    }
}
