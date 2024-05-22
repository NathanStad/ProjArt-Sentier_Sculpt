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
            SentierSeeder::class,
            MotCleSeeder::class,
            PointInteretSeeder::class,
            EtapeSeeder::class,
            MotCleSentierSeeder::class,
            EtapePointInteretSeeder::class,
        ]);
    }
}