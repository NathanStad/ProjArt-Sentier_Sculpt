<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Sentier;
use App\Models\MotCle;

class MotCleSentierSeeder extends Seeder {
    /**
     * Exécute les enregistrements de la base de données pour la table pivot motcle_sentier.
     * Pour chaque sentier existant, attache aléatoirement trois mots-clés.
     *
     * @return void
     */
    
    public function run(): void {

        $sentiers = Sentier::all();
        $motCles = MotCle::all();

        $sentiers->each(function ($sentier) use ($motCles) {
            $sentier->motcles()->attach(
                $motCles->random(3)->pluck('id')->toArray()
            );
        });
    }
}