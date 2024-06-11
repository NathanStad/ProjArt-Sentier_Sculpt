<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Commentaire;
Use App\Models\Sentier;

class CommentaireSeeder extends Seeder {

    /**
     * Exécute les enregistrements de la base de données pour les commentaires.
     * Pour chaque sentier existant, deux commentaires sont créés avec des valeurs d'exemple prédéfinies pour l'email, le nom et le message.
     *
     * @return void
     */

    public function run(): void
    {
        $sentiers = Sentier::all();
        
        foreach ($sentiers as $sentier) {
            Commentaire::create([
                'sentier_id' => $sentier->id,
                'email' => 'example1@example.com',
                'name' => 'MichaelScott',
                'message' => 'Un commentaire typique pour le sentier ' . $sentier->nom . '.',
            ]);

            Commentaire::create([
                'sentier_id' => $sentier->id,
                'email' => 'example2@example.com',
                'name' => 'PamBeesly',
                'message' => 'Un autre commentaire typique pour le sentier ' . $sentier->nom . '.',
            ]);
        }
    }
}