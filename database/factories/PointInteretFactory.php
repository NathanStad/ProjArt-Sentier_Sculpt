<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;


class PointInteretFactory extends Factory {

    /**
     * Définition des valeurs par défaut pour le modèle PointInteret.
     * Chaque point d'intérêt contient un nom et une photo générés par Faker.
     *
     * @return array
     */

    public function definition(): array
    {
        return [
            'nom' => $this->faker->word(),
            'photo' => $this->faker->imageUrl(),
        ];
    }
}