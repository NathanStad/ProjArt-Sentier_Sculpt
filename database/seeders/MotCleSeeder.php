<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\MotCle;

class MotCleSeeder extends Seeder {

    public function run(): void {
        MotCle::create([
            'name' => 'Nature',
        ]);

        MotCle::create([
            'name' => 'Histoire',
        ]);

        MotCle::create([
            'name' => 'Culture',
        ]);

        MotCle::create([
            'name' => 'Aventure',
        ]);

        MotCle::create([
            'name' => 'Sport',
        ]);

        MotCle::create([
            'name' => 'Gastronomie',
        ]);

        MotCle::create([
            'name' => 'Architecture',
        ]);

        MotCle::create([
            'name' => 'Faune',
        ]);

        MotCle::create([
            'name' => 'Flore',
        ]);

        MotCle::create([
            'name' => 'Panorama',
        ]);
    }
}