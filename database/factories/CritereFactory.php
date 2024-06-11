<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class CritereFactory extends Factory {

    /**
     * Définition des valeurs par défaut pour le modèle Critere.
     * Chaque critère contient un nom généré par Faker.
     *
     * @return array
     */

    public function definition(): array
    {
        return [
            'name' => $this->faker->word(),
        ];
    }
}