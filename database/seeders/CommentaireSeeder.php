<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Commentaire;

class CommentaireSeeder extends Seeder {

    public function run(): void
    {
        Commentaire::create([
            'sentier_id' => 1,
            'email' => 'example@example.com',
            'name' => 'MichaelScott',
            'message' => 'Un commentaire typique pour le sentier.',
        ]);

        Commentaire::factory()->count(30)->create();
    }
}