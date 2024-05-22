<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Etape;
use App\Models\PointInteret;

class EtapePointInteretSeeder extends Seeder {
    public function run(): void {
        $etapes = Etape::all();
        $pointsInteret = PointInteret::all();

        $etapes->each(function ($etape) use ($pointsInteret) {
            $etape->pointsInteret()->attach(
                $pointsInteret->random(rand(1, 3))->pluck('id')->toArray()
            );
        });
    }
}