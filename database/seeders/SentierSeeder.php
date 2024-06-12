<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Sentier;

class SentierSeeder extends Seeder {

    /**
     * Exécute les enregistrements de la base de données pour les sentiers.
     * Crée plusieurs sentiers prédéfinis, chacun avec des informations détaillées telles que le nom, la description, 
     * la durée, la longueur, la localisation, le compteur, le statut d'archive, la photo, et les associations avec 
     * un thème, un utilisateur et une difficulté.
     *
     * @return void
     */

    public function run(): void {

        Sentier::create([
            'nom' => 'La ville qui dure',
            'description' => 'Un sentier typique pour les tests.',
            'duree' => 7200,
            'longueur' => 10.5,
            'localisation' => 'Yverdon',
            'compteur' => 0,
            'archive' => 0,
            'photo' => '/storage/sentiers/sentier1.jpg',
            'theme_id' => 1,
            'user_id' => 1,
            'difficulte_id' => 3
        ]);

        Sentier::create([
            'nom' => 'La marche Olympique',
            'description' => 'Un sentier qui vous fait découvrir l\'histoire de la région.',
            'duree' => 9000,
            'longueur' => 12.0,
            'localisation' => 'Lausanne',
            'compteur' => 0,
            'archive' => 0,
            'photo' => '/storage/sentiers/sentier2.jpg',
            'theme_id' => 2,
            'user_id' => 2,
            'difficulte_id' => 2
        ]);

        Sentier::create([
            'nom' => 'En pleine Nature',
            'description' => 'Un sentier qui traverse les plus beaux paysages naturels.',
            'duree' => 10800,
            'longueur' => 15.0,
            'localisation' => 'Le Sentier',
            'compteur' => 0,
            'archive' => 0,
            'photo' => '/storage/sentiers/sentier3.jpg',
            'theme_id' => 3,
            'user_id' => 3,
            'difficulte_id' => 1
        ]);
    }
}