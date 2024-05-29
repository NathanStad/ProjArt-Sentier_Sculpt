<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder {

    public function run(): void {
        // User::create([
        //     'name' => 'michaelscott',
        //     'email' => 'michael.scott@dunder.com',
        //     'password' => Hash::make('password'), 
        //     'role' => 'institution',
        //     'photo' => 'https://example.com/photo1',
        // ]);
        
        // User::factory(10)->create();

        User::create([
            'name' => 'michaelscott',
            'email' => 'michael.scott@dunder.com',
            'password' => Hash::make('password'),
            'role' => 'institution',
            'photo' => 'https://example.com/photo1',
        ]);

        User::create([
            'name' => 'pamhalpert',
            'email' => 'pam.halpert@dunder.com',
            'password' => Hash::make('password'),
            'role' => 'user',
            'photo' => 'https://example.com/photo2',
        ]);

        User::create([
            'name' => 'jimd',
            'email' => 'jim.d@dunder.com',
            'password' => Hash::make('password'),
            'role' => 'user',
            'photo' => 'https://example.com/photo3',
        ]);
    }
}