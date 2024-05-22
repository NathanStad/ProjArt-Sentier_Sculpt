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

        MotCle::factory()->count(10)->create();
    }
}