<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Sentier;

class SentierSeeder extends Seeder {
    public function run(): void {
        Sentier::create([
            'nom' => 'Sentier Typique',
            'description' => 'Un sentier typique pour les tests.',
            'duree' => 120,
            'longueur' => 10.5,
            'point_depart' => 'Lausanne',
            'point_arrive' => 'Vevey',
            'photo' => 'https://example.com/sentier1',
            'theme_id' => 1
        ]);
        
        Sentier::factory(20)->create();
    }
}