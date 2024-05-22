<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Sentier;

class SentierSeeder extends Seeder {
    public function run(): void {
        Sentier::factory(20)->create();
    }
}