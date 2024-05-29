<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Etape;
use App\Models\Sentier;
use Faker\Factory as Faker;

class EtapeSeeder extends Seeder {
    public function run(): void {
        $sentiers = Sentier::all();
    
        foreach ($sentiers as $sentier) {
            $baseLatitude = 46.5617;
            $baseLongitude = 6.5360;
            $previousLatitude = $baseLatitude;
            $previousLongitude = $baseLongitude;
    
            for ($i = 1; $i <= 3; $i++) {
                if ($i === 1) {
                    $latitude = $baseLatitude + (rand(-5000, 5000) / 1000000);
                    $longitude = $baseLongitude + (rand(-5000, 5000) / 1000000);
                } else {
                    do {
                        $latitude = $previousLatitude + (rand(-25000, 25000) / 1000000);
                        $longitude = $previousLongitude + (rand(-25000, 25000) / 1000000);
                        $distance = $this->haversineGreatCircleDistance($previousLatitude, $previousLongitude, $latitude, $longitude);
                    } while ($distance < 1 || $distance > 2.5);
    
                   $duree = ($distance / 5) * 3600;
                }
    
                if ($i === 3) {
                    $distance = 0;
                    $duree = 0;
                } else {
                   $distance = $this->haversineGreatCircleDistance($previousLatitude, $previousLongitude, $latitude, $longitude);
                   $duree = ($distance / 5) * 3600;
                }
    
                Etape::create([
                    'sentier_id' => $sentier->id,
                    'nom' => 'Étape ' . $i . ' du ' . $sentier->nom,
                    'description' => 'Description de l\'étape ' . $i . ' du sentier.',
                    'latitude' => $latitude,
                    'longitude' => $longitude,
                    'ordre' => $i,
                    'photo' => '/imgs/etapes/photo' . $i . '.jpg',
                    'distance' => $distance,
                    'duree' => $duree,
                ]);
    
                $previousLatitude = $latitude;
                $previousLongitude = $longitude;
            }
        }
    }

    private function haversineGreatCircleDistance($latitudeFrom, $longitudeFrom, $latitudeTo, $longitudeTo, $earthRadius = 6371) {
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