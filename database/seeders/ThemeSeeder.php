<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Theme;

class ThemeSeeder extends Seeder {

    public function run(): void
    {
        // Theme::create([
        //     'name' => 'Architecture',
        //     'icone' => 'https://example.com/cats/photo1',
        // ]);

        // Theme::factory()->count(20)->create();

        Theme::create([
            'name' => 'Architecture',
            'icone' => 'https://example.com/cats/photo1',
        ]);

        Theme::create([
            'name' => 'Nature',
            'icone' => 'https://example.com/cats/photo2',
        ]);

        Theme::create([
            'name' => 'Histoire',
            'icone' => 'https://example.com/cats/photo3',
        ]);

        Theme::create([
            'name' => 'Art',
            'icone' => 'https://example.com/cats/photo4',
        ]);

        Theme::create([
            'name' => 'Gastronomie',
            'icone' => 'https://example.com/cats/photo5',
        ]);
    }
}