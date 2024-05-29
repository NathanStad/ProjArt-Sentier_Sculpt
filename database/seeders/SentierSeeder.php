<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Sentier;

class SentierSeeder extends Seeder {
    public function run(): void {
        // Sentier::create([
        //     'nom' => 'Sentier Typique',
        //     'description' => 'Un sentier typique pour les tests.',
        //     'duree' => 120,
        //     'longueur' => 10.5,
        //     'point_depart' => 'Lausanne',
        //     'point_arrive' => 'Vevey',
        //     'photo' => 'https://example.com/sentier1',
        //     'theme_id' => 1,
        //     'user_id' => 1,
        //     'difficulte_id' => 3
        // ]);
        
        // Sentier::factory(20)->create();

        Sentier::create([
            'nom' => 'Sentier Typique',
            'description' => 'Un sentier typique pour les tests.',
            'duree' => 120,
            'longueur' => 10.5,
            'localisation' => 'Lausanne',
            'compteur' => 0,
            'photo' => '/imgs/sentiers/sentier1.jpg',
            'theme_id' => 1,
            'user_id' => 1,
            'difficulte_id' => 3
        ]);

        Sentier::create([
            'nom' => 'Sentier Historique',
            'description' => 'Un sentier qui vous fait découvrir l\'histoire de la région.',
            'duree' => 150,
            'longueur' => 12.0,
            'localisation' => 'Nyon',
            'compteur' => 0,
            'photo' => '/imgs/sentiers/sentier1.jpg',
            'theme_id' => 2,
            'user_id' => 2,
            'difficulte_id' => 2
        ]);

        Sentier::create([
            'nom' => 'Sentier Nature',
            'description' => 'Un sentier qui traverse les plus beaux paysages naturels.',
            'duree' => 180,
            'longueur' => 15.0,
            'localisation' => 'Montreux',
            'compteur' => 0,
            'photo' => '/imgs/sentiers/sentier1.jpg',
            'theme_id' => 3,
            'user_id' => 3,
            'difficulte_id' => 1
        ]);
    }
}