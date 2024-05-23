<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Critere;

class CritereSeeder extends Seeder {

    public function run(): void
    {
        Critere::create([
            'name' => 'Parking disponible',
        ]);

        Critere::create([
            'name' => 'Mobilité réduite',
        ]);

        Critere::factory()->count(10)->create();
    }
}