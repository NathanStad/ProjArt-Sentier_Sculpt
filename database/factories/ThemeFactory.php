<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ThemeFactory extends Factory {

    public function definition(): array
    {
        return [
            'name' => $this->faker->word(),
            'icone' => $this->faker->imageUrl(800, 800, 'cats', true, 'Faker'),
        ];
    }
}