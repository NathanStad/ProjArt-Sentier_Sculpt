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
            'name' => 'Agence ADNV',
            'email' => 'contact@adnv.ch',
            'password' => Hash::make('adnv1234'),
            'role' => 'institution',
            'photo' => '/storage/users/adnv.jpg',
        ]);

        User::create([
            'name' => 'Pro Natura VD',
            'email' => 'contact@pronatura.ch',
            'password' => Hash::make('pronatura1234'),
            'role' => 'user',
            'photo' => '/storage/users/natura.jpg',
        ]);

        User::create([
            'name' => 'Montreux Riviera',
            'email' => 'contact@riviera.ch',
            'password' => Hash::make('montreux1234'),
            'role' => 'user',
            'photo' => '/storage/users/riviera.jpg',
        ]);
    }
}