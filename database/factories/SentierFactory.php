<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class SentierFactory extends Factory {
    public function definition(): array {
        return [
            'nom' => $this->faker->word(),
            'description' => $this->faker->paragraph(),
            'duree' => $this->faker->numberBetween(30, 300), 
            'longueur' => $this->faker->randomFloat(2, 1, 50), // longueur en kilomètres
            'point_depart' => $this->faker->city(),
            'point_arrive' => $this->faker->city(),
            'photo' => $this->faker->imageUrl(),
            'theme_id' => $this->faker->numberBetween(1,20),
        ];
    }
}