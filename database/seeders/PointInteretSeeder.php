<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\PointInteret;

class PointInteretSeeder extends Seeder {

    public function run(): void {
        
        PointInteret::create([
            'nom' => 'Château de Chillon',
            'description' => 'Un magnifique château.',
            'latitude' => 46.4,
            'longitude' => 6.8,
            'photo' => 'https://example.com/poi1',
        ]);
        
        PointInteret::factory(50)->create();
    }
}