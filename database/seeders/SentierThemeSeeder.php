<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Sentier;
use App\Models\MotCle;

class SentierThemeSeeder extends Seeder {
    public function run(): void {
        $sentiers = Sentier::all();
        $themes = MotCle::all();

        $sentiers->each(function ($sentier) use ($themes) {
            $sentier->themes()->attach(
                $themes->random(rand(1, 3))->pluck('id')->toArray()
            );
        }); 
    }
}