<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Sentier;

class CommentaireFactory extends Factory {
    
    /**
     * Définition des valeurs par défaut pour le modèle Commentaire.
     * Chaque commentaire est associé à un sentier, contient un email unique, un nom et un message généré par Faker.
     *
     * @return array
     */

    public function definition(): array
    {
        return [
            'sentier_id' => Sentier::factory(),
            'email' => $this->faker->unique()->safeEmail(),
            'name' => $this->faker->userName(),
            'message' => $this->faker->paragraph(),
        ];
    }
}