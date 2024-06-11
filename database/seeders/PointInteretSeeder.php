<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\PointInteret;

class PointInteretSeeder extends Seeder {
    
    /**
     * Exécute les enregistrements de la base de données pour les points d'intérêt.
     * Crée plusieurs points d'intérêt prédéfinis, chacun avec un nom spécifique et une photo.
     *
     * @return void
     */

    public function run(): void {
        PointInteret::create([
            'nom' => 'Château de Chillon',
            'photo' => '/storage/pois/photo1.jpg',
        ]);

        PointInteret::create([
            'nom' => 'Cathédrale de Lausanne',
            'photo' => '/storage/pois/photo2.jpg',
        ]);

        PointInteret::create([
            'nom' => 'Musée Olympique',
            'photo' => '/storage/pois/photo3.jpg',
        ]);

        PointInteret::create([
            'nom' => 'Montreux Jazz Festival',
            'photo' => '/storage/pois/photo4.jpg',
        ]);

        PointInteret::create([
            'nom' => 'Lac Léman',
            'photo' => '/storage/pois/photo5.jpg',
        ]);

        PointInteret::create([
            'nom' => 'Lavaux',
            'photo' => '/storage/pois/photo6.jpg',
        ]);

        PointInteret::create([
            'nom' => 'Chaplin’s World',
            'photo' => '/storage/pois/photo7.jpg',
        ]);

        PointInteret::create([
            'nom' => 'Musée de l’Élysée',
            'photo' => '/storage/pois/photo8.jpg',
        ]);

        PointInteret::create([
            'nom' => 'Alimentarium',
            'photo' => '/storage/pois/photo9.jpg',
        ]);

        PointInteret::create([
            'nom' => 'Aquatis',
            'photo' => '/storage/pois/photo10.jpg',
        ]);

        PointInteret::create([
            'nom' => 'Musée Jenisch',
            'photo' => '/storage/pois/photo11.jpg',
        ]);

        PointInteret::create([
            'nom' => 'Maison Cailler',
            'photo' => '/storage/pois/photo12.jpg',
        ]);

        PointInteret::create([
            'nom' => 'Swiss Vapeur Parc',
            'photo' => '/storage/pois/photo13.jpg',
        ]);

        PointInteret::create([
            'nom' => 'Glacier 3000',
            'photo' => '/storage/pois/photo14.jpg',
        ]);

        PointInteret::create([
            'nom' => 'Musée HR Giger',
            'photo' => '/storage/pois/photo15.jpg',
        ]);

        PointInteret::create([
            'nom' => 'Zoo de la Garenne',
            'photo' => '/storage/pois/photo16.jpg',
        ]);

        PointInteret::create([
            'nom' => 'Château de Gruyères',
            'photo' => '/storage/pois/photo17.jpg',
        ]);

        PointInteret::create([
            'nom' => 'Parc naturel régional Gruyère Pays-d’Enhaut',
            'photo' => '/storage/pois/photo18.jpg',
        ]);
    }
}