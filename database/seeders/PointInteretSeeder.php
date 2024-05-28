<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\PointInteret;

class PointInteretSeeder extends Seeder {

    public function run(): void {
        // PointInteret::create([
        //     'nom' => 'Château de Chillon',
        //     'photo' => 'https://example.com/poi1',
        // ]);
        
        // PointInteret::factory(50)->create();

        PointInteret::create([
            'nom' => 'Château de Chillon',
            'photo' => 'https://example.com/poi1',
        ]);

        PointInteret::create([
            'nom' => 'Cathédrale de Lausanne',
            'photo' => 'https://example.com/poi2',
        ]);

        PointInteret::create([
            'nom' => 'Musée Olympique',
            'photo' => 'https://example.com/poi3',
        ]);

        PointInteret::create([
            'nom' => 'Montreux Jazz Festival',
            'photo' => 'https://example.com/poi4',
        ]);

        PointInteret::create([
            'nom' => 'Lac Léman',
            'photo' => 'https://example.com/poi5',
        ]);

        PointInteret::create([
            'nom' => 'Lavaux',
            'photo' => 'https://example.com/poi6',
        ]);

        PointInteret::create([
            'nom' => 'Chaplin’s World',
            'photo' => 'https://example.com/poi7',
        ]);

        PointInteret::create([
            'nom' => 'Musée de l’Élysée',
            'photo' => 'https://example.com/poi8',
        ]);

        PointInteret::create([
            'nom' => 'Alimentarium',
            'photo' => 'https://example.com/poi9',
        ]);

        PointInteret::create([
            'nom' => 'Aquatis',
            'photo' => 'https://example.com/poi10',
        ]);

        PointInteret::create([
            'nom' => 'Musée Jenisch',
            'photo' => 'https://example.com/poi11',
        ]);

        PointInteret::create([
            'nom' => 'Maison Cailler',
            'photo' => 'https://example.com/poi12',
        ]);

        PointInteret::create([
            'nom' => 'Swiss Vapeur Parc',
            'photo' => 'https://example.com/poi13',
        ]);

        PointInteret::create([
            'nom' => 'Glacier 3000',
            'photo' => 'https://example.com/poi14',
        ]);

        PointInteret::create([
            'nom' => 'Musée HR Giger',
            'photo' => 'https://example.com/poi15',
        ]);

        PointInteret::create([
            'nom' => 'Zoo de la Garenne',
            'photo' => 'https://example.com/poi16',
        ]);

        PointInteret::create([
            'nom' => 'Château de Gruyères',
            'photo' => 'https://example.com/poi17',
        ]);

        PointInteret::create([
            'nom' => 'Parc naturel régional Gruyère Pays-d’Enhaut',
            'photo' => 'https://example.com/poi18',
        ]);
    }
}