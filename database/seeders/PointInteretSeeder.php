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
            'photo' => '/imgs/pois/photo1.jpg',
        ]);

        PointInteret::create([
            'nom' => 'Cathédrale de Lausanne',
            'photo' => '/imgs/pois/photo2.jpg',
        ]);

        PointInteret::create([
            'nom' => 'Musée Olympique',
            'photo' => '/imgs/pois/photo3.jpg',
        ]);

        PointInteret::create([
            'nom' => 'Montreux Jazz Festival',
            'photo' => '/imgs/pois/photo4.jpg',
        ]);

        PointInteret::create([
            'nom' => 'Lac Léman',
            'photo' => '/imgs/pois/photo5.jpg',
        ]);

        PointInteret::create([
            'nom' => 'Lavaux',
            'photo' => '/imgs/pois/photo6.jpg',
        ]);

        PointInteret::create([
            'nom' => 'Chaplin’s World',
            'photo' => '/imgs/pois/photo7.jpg',
        ]);

        PointInteret::create([
            'nom' => 'Musée de l’Élysée',
            'photo' => '/imgs/pois/photo8.jpg',
        ]);

        PointInteret::create([
            'nom' => 'Alimentarium',
            'photo' => '/imgs/pois/photo9.jpg',
        ]);

        PointInteret::create([
            'nom' => 'Aquatis',
            'photo' => '/imgs/pois/photo10.jpg',
        ]);

        PointInteret::create([
            'nom' => 'Musée Jenisch',
            'photo' => '/imgs/pois/photo11.jpg',
        ]);

        PointInteret::create([
            'nom' => 'Maison Cailler',
            'photo' => '/imgs/pois/photo12.jpg',
        ]);

        PointInteret::create([
            'nom' => 'Swiss Vapeur Parc',
            'photo' => '/imgs/pois/photo13.jpg',
        ]);

        PointInteret::create([
            'nom' => 'Glacier 3000',
            'photo' => '/imgs/pois/photo14.jpg',
        ]);

        PointInteret::create([
            'nom' => 'Musée HR Giger',
            'photo' => '/imgs/pois/photo15.jpg',
        ]);

        PointInteret::create([
            'nom' => 'Zoo de la Garenne',
            'photo' => '/imgs/pois/photo16.jpg',
        ]);

        PointInteret::create([
            'nom' => 'Château de Gruyères',
            'photo' => '/imgs/pois/photo17.jpg',
        ]);

        PointInteret::create([
            'nom' => 'Parc naturel régional Gruyère Pays-d’Enhaut',
            'photo' => '/imgs/pois/photo18.jpg',
        ]);
    }
}