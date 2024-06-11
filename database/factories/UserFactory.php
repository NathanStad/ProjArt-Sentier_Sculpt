<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory {

    /**
     * Définition des valeurs par défaut pour le modèle User.
     * Chaque utilisateur contient des informations telles que le nom, l'email, le mot de passe, 
     * le rôle et une photo générés par Faker. Inclut également une méthode pour indiquer si l'email 
     * de l'utilisateur est vérifié ou non.
     *
     * @return array
     */

    protected static ?string $password;

    public function definition(): array
    {
        return [
            'name' => fake()->userName(),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => static::$password ??= Hash::make('password'),
            'remember_token' => Str::random(10),
            'role' => $this->faker->randomElement(['admin', 'institution', 'client']),
            'photo' => $this->faker->imageUrl(),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}