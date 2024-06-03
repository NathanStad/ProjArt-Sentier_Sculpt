<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\MotCle;

class MotCleSeeder extends Seeder {

    public function run(): void {
        MotCle::create([
            'name' => 'Technologie',
        ]);

        MotCle::create([
            'name' => 'Vin',
        ]);

        MotCle::create([
            'name' => 'Mythes & Légendes',
        ]);

        MotCle::create([
            'name' => 'Châteaux',
        ]);

        MotCle::create([
            'name' => 'Point de vue',
        ]);

        MotCle::create([
            'name' => 'Parc',
        ]);

        MotCle::create([
            'name' => 'Fôret',
        ]);

        MotCle::create([
            'name' => 'Jardins',
        ]);

        MotCle::create([
            'name' => 'Education',
        ]);

        MotCle::create([
            'name' => 'Ruines',
        ]);

        MotCle::create([
            'name' => 'Statues',
        ]);
    }
}