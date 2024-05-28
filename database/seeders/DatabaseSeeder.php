<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder {

    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            ThemeSeeder::class,
            DifficulteSeeder::class,
            SentierSeeder::class,
            CommentaireSeeder::class,
            MotCleSeeder::class,
            CritereSeeder::class,
            PointInteretSeeder::class,
            EtapeSeeder::class,
            MotCleSentierSeeder::class,
            CritereSentierSeeder::class,
            EtapePointInteretSeeder::class,
        ]);
    }
}