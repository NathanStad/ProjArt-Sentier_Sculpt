<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class SentierFactory extends Factory {
    
    /**
     * Définition des valeurs par défaut pour le modèle Sentier.
     * Chaque sentier contient des informations telles que le nom, la description, la durée, 
     * la longueur, le point de départ et d'arrivée, une photo, et des associations avec un thème, 
     * un utilisateur et une difficulté, générés par Faker.
     *
     * @return array
     */

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
            'user_id' => $this->faker->numberBetween(1,11),
            'difficulte_id' => $this->faker->numberBetween(1,3)
        ];
    }
}