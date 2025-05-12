<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\RequestTravel;

class RequestTravelsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        RequestTravel::factory()->count(10)->create();
    }
}
