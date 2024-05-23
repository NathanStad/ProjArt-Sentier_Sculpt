<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Theme;

class ThemeSeeder extends Seeder {

    public function run(): void
    {
        Theme::create([
            'name' => 'Architecture',
        ]);

        Theme::factory()->count(20)->create();
    }
}