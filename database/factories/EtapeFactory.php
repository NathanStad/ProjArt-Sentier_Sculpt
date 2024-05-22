<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Sentier;
use App\Models\Etape;

class EtapeFactory extends Factory {

    public function definition(): array {
        $latitude = $this->faker->latitude(46.2, 46.6);
        $longitude = $this->faker->longitude(6.0, 7.1);

        return [
            'sentier_id' => Sentier::factory(),
            'nom' => $this->faker->word(),
            'description' => $this->faker->paragraph(),
            'latitude' => $latitude,
            'longitude' => $longitude,
            'ordre' => 1,
        ];
    }
}