<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Critere;

class CritereSeeder extends Seeder {

    public function run(): void
    {

        Critere::create([
            'name' => 'Parking',
            'icone' => '<span class="material-symbols-outlined">
            local_parking
            </span>'
        ]);

        Critere::create([
            'name' => 'Adapté aux enfants',
            'icone' => '<span class="material-symbols-outlined">
            child_friendly
            </span>'
        ]);

        Critere::create([
            'name' => 'Animaux de compagnie autorisés',
            'icone' => '<span class="material-symbols-outlined">
            pets
            </span>'
        ]);

        Critere::create([
            'name' => 'Gratuit',
            'icone' => '<span class="material-symbols-outlined">
            money_off
            </span>'
        ]);

        Critere::create([
            'name' => 'Adapté aux personnes à mobilité réduite',
            'icone' => '<span class="material-symbols-outlined">
            accessible
            </span>'
        ]);

        Critere::create([
            'name' => 'Restauration',
            'icone' => '<span class="material-symbols-outlined">
            restaurant
            </span>'
        ]);

        Critere::create([
            'name' => 'Facilités sanitaires',
            'icone' => '<span class="material-symbols-outlined">
            wc
            </span>'
        ]);

        Critere::create([
            'name' => 'Accès en transport public',
            'icone' => '<span class="material-symbols-outlined">
            directions_bus
            </span>'
        ]);
    }
}