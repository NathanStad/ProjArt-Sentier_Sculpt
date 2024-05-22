<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Sentier;
use App\Models\Etape;

class EtapeFactory extends Factory {

    public function definition(): array {
        $latitude = $this->faker->latitude(45.8, 47.8);
        $longitude = $this->faker->longitude(5.9, 10.5);

        return [
            'nom' => $this->faker->word(),
            'description' => $this->faker->paragraph(),
            'latitude' => $latitude,
            'longitude' => $longitude,
            'ordre' => Etape::where('sentier_id', $this->faker->randomDigitNotNull)->count() + 1,
            'sentier_id' => Sentier::factory(),
        ];
    }
}