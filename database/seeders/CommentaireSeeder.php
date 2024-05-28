<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Commentaire;
Use App\Models\Sentier;

class CommentaireSeeder extends Seeder {

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