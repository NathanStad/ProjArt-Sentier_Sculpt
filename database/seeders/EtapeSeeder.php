<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Etape;
use App\Models\Sentier;
use Faker\Factory as Faker;

class EtapeSeeder extends Seeder {
    public function run(): void {

        $sentier = Sentier::first();

        if ($sentier) {
            Etape::create([
                'sentier_id' => $sentier->id,
                'nom' => 'Étape Typique',
                'description' => 'Une étape typique pour les tests.',
                'latitude' => 46.5,
                'longitude' => 6.5,
                'ordre' => 1,
                'photo' => 'https://example.com/photo1User'
            ]);
        }
        
        $faker = Faker::create();

        if (Sentier::count() > 0) {
            $sentiers = Sentier::all();

            $sentiers->each(function ($sentier) use ($faker) {
                $latitude = $faker->latitude(46.2, 46.6);
                $longitude = $faker->longitude(6.0, 7.1);

                $firstEtape = Etape::factory()->create([
                    'sentier_id' => $sentier->id,
                    'latitude' => $latitude,
                    'longitude' => $longitude,
                    'ordre' => 1,
                ]);
                
                foreach (range(2, 5) as $i) {
                    Etape::factory()->create([
                        'sentier_id' => $sentier->id,
                        'latitude' => $faker->latitude($firstEtape->latitude - 0.05, $firstEtape->latitude + 0.05),
                        'longitude' => $faker->longitude($firstEtape->longitude - 0.05, $firstEtape->longitude + 0.05),
                        'ordre' => $i,
                    ]);
                }
            });
        } else {
            $sentiers = Sentier::factory()->count(10)->create();
            $sentiers->each(function ($sentier) use ($faker) {
                $latitude = $faker->latitude(46.2, 46.6);
                $longitude = $faker->longitude(6.0, 7.1);

                $firstEtape = Etape::factory()->create([
                    'sentier_id' => $sentier->id,
                    'latitude' => $latitude,
                    'longitude' => $longitude,
                    'ordre' => 1,
                ]);
                
                foreach (range(2, 5) as $i) {
                    Etape::factory()->create([
                        'sentier_id' => $sentier->id,
                        'latitude' => $faker->latitude($firstEtape->latitude - 0.05, $firstEtape->latitude + 0.05),
                        'longitude' => $faker->longitude($firstEtape->longitude - 0.05, $firstEtape->longitude + 0.05),
                        'ordre' => $i,
                    ]);
                }
            });
        }
    }  
}