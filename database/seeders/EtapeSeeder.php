<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Etape;
use App\Models\Sentier;

class EtapeSeeder extends Seeder {
    public function run(): void {
        if (Sentier::count() > 0) {
            $sentiers = Sentier::all();
            
            $sentiers->each(function ($sentier) {
                foreach (range(1, 5) as $i) {
                    Etape::factory()->create([
                        'sentier_id' => $sentier->id,
                        'ordre' => $i
                    ]);
                }
            });
        } else {
            // Créer des sentiers si aucun n'existe
            $sentiers = Sentier::factory()->count(10)->create();

            // Créer des étapes pour ces sentiers
            $sentiers->each(function ($sentier) {
                foreach (range(1, 5) as $i) {
                    Etape::factory()->create([
                        'sentier_id' => $sentier->id,
                        'ordre' => $i
                    ]);
                }
            });
        }
    }  
}