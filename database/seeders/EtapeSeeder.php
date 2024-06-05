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

        $latitude2 = 46.7845; 
        $longitude2 = 6.6442;
        $distance1 = $this->haversineGreatCircleDistance($latitude1, $longitude1, $latitude2, $longitude2);
        $duree1 = ($distance1 / 5) * 3600;

        $latitude3 = 46.77887821612798; 
        $longitude3 = 6.64853209607303;
        $distance2 = $this->haversineGreatCircleDistance($latitude2, $longitude2, $latitude3, $longitude3);
        $duree2 = ($distance2 / 5) * 3600;

        Etape::create([
            'sentier_id' => $sentier->id,
            'nom' => 'Le Pont de la Thièle',
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
            'nom' => 'La Grande Piscine',
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
            'nom' => 'Le Lamai Thai',
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

        $latitude2 = 46.5368; // Coordonnées ajustées
        $longitude2 = 6.6257;
        $distance1 = $this->haversineGreatCircleDistance($latitude1, $longitude1, $latitude2, $longitude2);
        $duree1 = ($distance1 / 5) * 3600;

        $latitude3 = 46.5412; // Coordonnées ajustées
        $longitude3 = 6.6321;
        $distance2 = $this->haversineGreatCircleDistance($latitude2, $longitude2, $latitude3, $longitude3);
        $duree2 = ($distance2 / 5) * 3600;

        Etape::create([
            'sentier_id' => $sentier->id,
            'nom' => 'Au dessus de la gare',
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
            'nom' => 'Aux côtés de Michou',
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
            'nom' => 'Vers le Chalet de Gobbet',
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

        $latitude2 = 46.619473;
        $longitude2 = 6.265666;
        $distance1 = $this->haversineGreatCircleDistance($latitude1, $longitude1, $latitude2, $longitude2);
        $duree1 = ($distance1 / 5) * 3600;

        $latitude3 = 46.625705;
        $longitude3 = 6.279295;
        $distance2 = $this->haversineGreatCircleDistance($latitude2, $longitude2, $latitude3, $longitude3);
        $duree2 = ($distance2 / 5) * 3600;

        Etape::create([
            'sentier_id' => $sentier->id,
            'nom' => 'Marais de Shrek',
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
            'nom' => 'Vers les Bious',
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
            'nom' => 'La ferme de Mosieur Gooodman',
            'description' => 'Description de l\'étape 3 du sentier.',
            'latitude' => $latitude3,
            'longitude' => $longitude3,
            'ordre' => 3,
            'photo' => '/imgs/etapes/photo9.jpg',
            'distance' => 0,
            'duree' => 0,
        ]);
    }

    private function haversineGreatCircleDistance($latitudeFrom, $longitudeFrom, $latitudeTo, $longitudeTo, $earthRadius = 6371)
    {
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