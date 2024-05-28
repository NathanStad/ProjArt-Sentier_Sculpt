<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;


class PointInteretFactory extends Factory {

    public function definition(): array
    {
        return [
            'nom' => $this->faker->word(),
            'photo' => $this->faker->imageUrl(),
        ];
    }
}