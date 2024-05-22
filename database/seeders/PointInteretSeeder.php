<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\PointInteret;

class PointInteretSeeder extends Seeder {

    public function run(): void {
        PointInteret::factory(50)->create();
    }
}