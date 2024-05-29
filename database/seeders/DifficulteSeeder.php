<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Difficulte;

class DifficulteSeeder extends Seeder {

    public function run(): void {
        $difficultes = [
            '1',
            '2',
            '3',
        ];
        
        foreach ($difficultes as $difficulte) {
            Difficulte::create(['graduation' => $difficulte]);
        }
    }
    
}