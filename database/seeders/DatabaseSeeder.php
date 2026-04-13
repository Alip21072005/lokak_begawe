<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Alip maulana',
            'email' => 'muhamadalipmaulana3@gmail.com',
            'password' => Hash::make('Alip210725_'),
            'role' => 'admin',
        ]);
    }
}