<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Sentier;
use App\Models\Critere;

class CritereSentierSeeder extends Seeder {

    public function run(): void {
        $sentiers = Sentier::all();
        $criteres = Critere::all();

        $sentiers->each(function ($sentier) use ($criteres) {
            $sentier->criteres()->attach(
                $criteres->random(3)->pluck('id')->toArray()
            );
        });
    }
}