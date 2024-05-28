<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Critere;

class CritereSeeder extends Seeder {

    public function run(): void
    {
        // Critere::create([
        //     'name' => 'Parking disponible',
        // ]);

        // Critere::create([
        //     'name' => 'Mobilité réduite',
        // ]);

        // Critere::factory()->count(10)->create();

        Critere::create([
            'name' => 'Parking disponible',
        ]);

        Critere::create([
            'name' => 'Mobilité réduite',
        ]);

        Critere::create([
            'name' => 'Adapté aux enfants/poussettes',
        ]);

        Critere::create([
            'name' => 'Animaux de compagnie autorisés',
        ]);

        Critere::create([
            'name' => 'Payant/gratuit',
        ]);

        Critere::create([
            'name' => 'Dénivelé',
        ]);

        Critere::create([
            'name' => 'Options pour manger (restaurant, pique-nique)',
        ]);

        Critere::create([
            'name' => 'Type de terrain',
        ]);

        Critere::create([
            'name' => 'Facilités sanitaires',
        ]);

        Critere::create([
            'name' => 'Accès en transport public',
        ]);
    }
}