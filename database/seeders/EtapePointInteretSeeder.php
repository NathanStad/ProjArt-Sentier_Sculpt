<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Etape;
use App\Models\PointInteret;

class EtapePointInteretSeeder extends Seeder {
    public function run(): void {
        
        $etape = Etape::first();
        $pointInteret = PointInteret::first();

        if ($etape && $pointInteret) {
            $etape->pointsInteret()->attach($pointInteret->id);
        }
        
        $etapes = Etape::all();
        $pointsInteret = PointInteret::all();

        $etapes->each(function ($etape) use ($pointsInteret) {
            $etape->pointsInteret()->attach(
                $pointsInteret->random(rand(1, 3))->pluck('id')->toArray()
            );
        });
    }
}