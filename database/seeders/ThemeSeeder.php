<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Theme;

class ThemeSeeder extends Seeder {

    /**
     * Exécute les enregistrements de la base de données pour les thèmes.
     * Crée plusieurs thèmes prédéfinis, chacun avec un nom spécifique et une icône correspondante.
     *
     * @return void
     */

    public function run(): void
    {
        Theme::create([
            'name' => 'Archéologie',
            'icone' => '<span class="material-symbols-outlined">
            tibia
            </span>',
        ]);

        Theme::create([
            'name' => 'Ecologie',
            'icone' => '<span class="material-symbols-outlined">
            nest_eco_leaf
            </span>',
        ]);

        Theme::create([
            'name' => 'Géologie',
            'icone' => '<span class="material-symbols-outlined">
            volcano
            </span>',
        ]);

        Theme::create([
            'name' => 'Artisanat local',
            'icone' => '<span class="material-symbols-outlined">
            storefront
            </span>',
        ]);

        Theme::create([
            'name' => 'Littérature',
            'icone' => '<span class="material-symbols-outlined">
            book_4
            </span>',
        ]);

        Theme::create([
            'name' => 'Arts',
            'icone' => '<span class="material-symbols-outlined">
            palette
            </span>',
        ]);

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