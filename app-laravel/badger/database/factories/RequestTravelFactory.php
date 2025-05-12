<?php

namespace Database\Factories;

use App\Models\RequestTravel;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class RequestTravelFactory extends Factory
{
    protected $model = RequestTravel::class;

    public function definition(): array
    {
        $dataIda = $this->faker->dateTimeBetween('+1 days', '+1 month');
        $dataVolta = (clone $dataIda)->modify('+'.rand(1, 5).' days');

        return [
            'user_id' => User::factory(),
            'nome_solicitante' => $this->faker->name(),
            'destino' => $this->faker->city(),
            'data_ida' => $dataIda->format('Y-m-d'),
            'data_volta' => $dataVolta->format('Y-m-d'),
            'status' => $this->faker->randomElement(['solicitado', 'aprovado', 'cancelado']),
        ];
    }
}
