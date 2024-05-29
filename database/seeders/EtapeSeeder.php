<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Etape;
use App\Models\Sentier;
use Faker\Factory as Faker;

class EtapeSeeder extends Seeder {
    public function run(): void
    {
        // Étapes pour le premier sentier (comme déjà fait précédemment)
        $sentier1 = Sentier::find(1);
        $this->createEtapesForSentier1($sentier1);

        // Étapes pour le deuxième sentier
        $sentier2 = Sentier::find(2);
        $this->createEtapesForSentier2($sentier2);

        // Étapes pour le troisième sentier
        $sentier3 = Sentier::find(3);
        $this->createEtapesForSentier3($sentier3);
    }

    private function createEtapesForSentier1($sentier)
    {
        $latitude1 = 46.778091603482416;
        $longitude1 = 6.634807457364027;

        $latitude2 = 46.7845; // Coordonnées ajustées
        $longitude2 = 6.6442;
        $distance1 = $this->haversineGreatCircleDistance($latitude1, $longitude1, $latitude2, $longitude2);
        $duree1 = ($distance1 / 5) * 3600;

        $latitude3 = 46.77887821612798; // Coordonnées ajustées
        $longitude3 = 6.64853209607303;
        $distance2 = $this->haversineGreatCircleDistance($latitude2, $longitude2, $latitude3, $longitude3);
        $duree2 = ($distance2 / 5) * 3600;

        Etape::create([
            'sentier_id' => $sentier->id,
            'nom' => 'Étape 1 du Sentier Typique',
            'description' => 'Description de l\'étape 1 du sentier.',
            'latitude' => $latitude1,
            'longitude' => $longitude1,
            'ordre' => 1,
            'photo' => '/imgs/etapes/photo1.jpg',
            'distance' => $distance1,
            'duree' => $duree1,
        ]);

        Etape::create([
            'sentier_id' => $sentier->id,
            'nom' => 'Étape 2 du Sentier Typique',
            'description' => 'Description de l\'étape 2 du sentier.',
            'latitude' => $latitude2,
            'longitude' => $longitude2,
            'ordre' => 2,
            'photo' => '/imgs/etapes/photo2.jpg',
            'distance' => $distance2,
            'duree' => $duree2,
        ]);

        Etape::create([
            'sentier_id' => $sentier->id,
            'nom' => 'Étape 3 du Sentier Typique',
            'description' => 'Description de l\'étape 3 du sentier.',
            'latitude' => $latitude3,
            'longitude' => $longitude3,
            'ordre' => 3,
            'photo' => '/imgs/etapes/photo3.jpg',
            'distance' => 0,
            'duree' => 0,
        ]);
    }

    private function createEtapesForSentier2($sentier)
    {
        $latitude1 = 46.52700585711486;
        $longitude1 = 6.6116923896493995;

        // Générer des coordonnées pour l'étape 2 entre 1 km et 2.5 km de la première étape
        do {
            $latitude2 = $latitude1 + (rand(-25000, 25000) / 1000000);
            $longitude2 = $longitude1 + (rand(-25000, 25000) / 1000000);
            $distance1 = $this->haversineGreatCircleDistance($latitude1, $longitude1, $latitude2, $longitude2);
        } while ($distance1 < 1 || $distance1 > 2.5);

        $duree1 = ($distance1 / 5) * 3600;

        // Générer des coordonnées pour l'étape 3 entre 1 km et 2.5 km de la deuxième étape
        do {
            $latitude3 = $latitude2 + (rand(-25000, 25000) / 1000000);
            $longitude3 = $longitude2 + (rand(-25000, 25000) / 1000000);
            $distance2 = $this->haversineGreatCircleDistance($latitude2, $longitude2, $latitude3, $longitude3);
        } while ($distance2 < 1 || $distance2 > 2.5);

        $duree2 = ($distance2 / 5) * 3600;

        Etape::create([
            'sentier_id' => $sentier->id,
            'nom' => 'Étape 1 du Sentier Historique',
            'description' => 'Description de l\'étape 1 du sentier.',
            'latitude' => $latitude1,
            'longitude' => $longitude1,
            'ordre' => 1,
            'photo' => '/imgs/etapes/photo4.jpg',
            'distance' => $distance1,
            'duree' => $duree1,
        ]);

        Etape::create([
            'sentier_id' => $sentier->id,
            'nom' => 'Étape 2 du Sentier Historique',
            'description' => 'Description de l\'étape 2 du sentier.',
            'latitude' => $latitude2,
            'longitude' => $longitude2,
            'ordre' => 2,
            'photo' => '/imgs/etapes/photo5.jpg',
            'distance' => $distance2,
            'duree' => $duree2,
        ]);

        Etape::create([
            'sentier_id' => $sentier->id,
            'nom' => 'Étape 3 du Sentier Historique',
            'description' => 'Description de l\'étape 3 du sentier.',
            'latitude' => $latitude3,
            'longitude' => $longitude3,
            'ordre' => 3,
            'photo' => '/imgs/etapes/photo6.jpg',
            'distance' => 0,
            'duree' => 0,
        ]);
    }

    private function createEtapesForSentier3($sentier)
    {
        $latitude1 = 46.60920005752568;
        $longitude1 = 6.248820104351553;

        // Générer des coordonnées pour l'étape 2 entre 1 km et 2.5 km de la première étape
        do {
            $latitude2 = $latitude1 + (rand(-25000, 25000) / 1000000);
            $longitude2 = $longitude1 + (rand(-25000, 25000) / 1000000);
            $distance1 = $this->haversineGreatCircleDistance($latitude1, $longitude1, $latitude2, $longitude2);
        } while ($distance1 < 1 || $distance1 > 2.5);

        $duree1 = ($distance1 / 5) * 3600;

        // Générer des coordonnées pour l'étape 3 entre 1 km et 2.5 km de la deuxième étape
        do {
            $latitude3 = $latitude2 + (rand(-25000, 25000) / 1000000);
            $longitude3 = $longitude2 + (rand(-25000, 25000) / 1000000);
            $distance2 = $this->haversineGreatCircleDistance($latitude2, $longitude2, $latitude3, $longitude3);
        } while ($distance2 < 1 || $distance2 > 2.5);

        $duree2 = ($distance2 / 5) * 3600;

        Etape::create([
            'sentier_id' => $sentier->id,
            'nom' => 'Étape 1 du Sentier Nature',
            'description' => 'Description de l\'étape 1 du sentier.',
            'latitude' => $latitude1,
            'longitude' => $longitude1,
            'ordre' => 1,
            'photo' => '/imgs/etapes/photo7.jpg',
            'distance' => $distance1,
            'duree' => $duree1,
        ]);

        Etape::create([
            'sentier_id' => $sentier->id,
            'nom' => 'Étape 2 du Sentier Nature',
            'description' => 'Description de l\'étape 2 du sentier.',
            'latitude' => $latitude2,
            'longitude' => $longitude2,
            'ordre' => 2,
            'photo' => '/imgs/etapes/photo8.jpg',
            'distance' => $distance2,
            'duree' => $duree2,
        ]);

        Etape::create([
            'sentier_id' => $sentier->id,
            'nom' => 'Étape 3 du Sentier Nature',
            'description' => 'Description de l\'étape 3 du sentier.',
            'latitude' => $latitude3,
            'longitude' => $longitude3,
            'ordre' => 3,
            'photo' => '/imgs/etapes/photo9.jpg',
            'distance' => 0,
            'duree' => 0,
        ]);
    }

    // Fonction de calcul de la distance en utilisant la formule Haversine
    private function haversineGreatCircleDistance($latitudeFrom, $longitudeFrom, $latitudeTo, $longitudeTo, $earthRadius = 6371)
    {
        // Convertir les degrés en radians
        $latFrom = deg2rad($latitudeFrom);
        $lonFrom = deg2rad($longitudeFrom);
        $latTo = deg2rad($latitudeTo);
        $lonTo = deg2rad($longitudeTo);

        $latDelta = $latTo - $latFrom;
        $lonDelta = $lonTo - $lonFrom;

        $angle = 2 * asin(sqrt(pow(sin($latDelta / 2), 2) +
            cos($latFrom) * cos($latTo) * pow(sin($lonDelta / 2), 2)));
        return $angle * $earthRadius;
    }
}