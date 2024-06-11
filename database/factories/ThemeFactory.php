<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ThemeFactory extends Factory {

    /**
     * Définition des valeurs par défaut pour le modèle Theme.
     * Chaque thème contient un nom et une icône générés par Faker.
     *
     * @return array
     */

    public function definition(): array
    {
        return [
            'name' => $this->faker->word(),
            'icone' => $this->faker->imageUrl(800, 800, 'cats', true, 'Faker'),
        ];
    }
}