<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Etape;
use App\Models\PointInteret;

class EtapePointInteretSeeder extends Seeder {

    /**
     * Exécute les enregistrements de la base de données pour la table pivot etape_point_interet.
     * Pour chaque étape existante, attache aléatoirement deux points d'intérêt.
     *
     * @return void
     */
    
    public function run(): void {

        $etapes = Etape::all();
        $pointsInteret = PointInteret::all();

        $etapes->each(function ($etape) use ($pointsInteret) {
            $etape->pointsInteret()->attach(
                $pointsInteret->random(2)->pluck('id')->toArray()
            );
        });
    }
}