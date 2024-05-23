<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Sentier;

class CommentaireFactory extends Factory {

    public function definition(): array
    {
        return [
            'sentier_id' => Sentier::factory(),
            'email' => $this->faker->unique()->safeEmail(),
            'message' => $this->faker->paragraph(),
        ];
    }
}