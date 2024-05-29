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
            'name' => 'Culinaire',
            'icone' => '<span class="material-symbols-outlined">
            lunch_dining
            </span>',
        ]);

        Theme::create([
            'name' => 'Histoire',
            'icone' => '<span class="material-symbols-outlined">
            castle
            </span>',
        ]);

        Theme::create([
            'name' => 'Architecture',
            'icone' => '<span class="material-symbols-outlined">
            villa
            </span>',
        ]);

        Theme::create([
            'name' => 'Faune & Flore',
            'icone' => '<span class="material-symbols-outlined">
            emoji_nature
            </span>',
        ]);

        Theme::create([
            'name' => 'Street Art',
            'icone' => '<span class="material-symbols-outlined">
            imagesearch_roller
            </span>',
        ]);
    }
}