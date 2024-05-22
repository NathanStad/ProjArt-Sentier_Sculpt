<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Sentier;
use App\Models\MotCle;

class MotCleSentierSeeder extends Seeder {
    public function run(): void {
        $sentier = Sentier::first();
        $theme = MotCle::first();

        if ($sentier && $theme) {
            $sentier->motcles()->attach($theme->id);
        }
        
        $sentiers = Sentier::all();
        $themes = MotCle::all();

        $sentiers->each(function ($sentier) use ($themes) {
            $sentier->motcles()->attach(
                $themes->random(rand(1, 3))->pluck('id')->toArray()
            );
        }); 
    }
}