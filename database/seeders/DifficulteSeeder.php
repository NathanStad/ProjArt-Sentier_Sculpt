<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Difficulte;

class DifficulteSeeder extends Seeder {

    /**
     * Exécute les enregistrements de la base de données pour les niveaux de difficulté.
     * Crée plusieurs niveaux de difficulté prédéfinis, chacun avec une graduation spécifique.
     *
     * @return void
     */

    public function run(): void {
        $difficultes = [
            '1',
            '2',
            '3',
        ];
        
        foreach ($difficultes as $difficulte) {
            Difficulte::create(['graduation' => $difficulte]);
        }
    } 
}