<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder {

    /**
     * Exécute les enregistrements de la base de données pour les utilisateurs.
     * Crée plusieurs utilisateurs prédéfinis, chacun avec des informations détaillées telles que le nom, l'email, 
     * le mot de passe, le rôle et la photo.
     *
     * @return void
     */

    public function run(): void {
        User::create([
            'name' => 'michaelscott',
            'email' => 'michael.scott@dunder.com',
            'password' => Hash::make('password'),
            'role' => 'institution',
            'photo' => '/storage/users/michaelscott.jpg',
        ]);

        User::create([
            'name' => 'pamhalpert',
            'email' => 'pam.halpert@dunder.com',
            'password' => Hash::make('password'),
            'role' => 'user',
            'photo' => '/storage/users/jimhalpert.jpg',
        ]);

        User::create([
            'name' => 'jimd',
            'email' => 'jim.d@dunder.com',
            'password' => Hash::make('password'),
            'role' => 'user',
            'photo' => '/storage/users/pambeesly.jpg',
        ]);
    }
}