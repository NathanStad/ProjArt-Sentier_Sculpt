<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;


class PointInteretFactory extends Factory {

    public function definition(): array
    {
        return [
            'nom' => $this->faker->word(),
            'description' => $this->faker->paragraph(),
            'latitude' => $this->faker->latitude(),
            'longitude' => $this->faker->longitude(),
            'photo' => $this->faker->imageUrl(),
        ];
    }
}