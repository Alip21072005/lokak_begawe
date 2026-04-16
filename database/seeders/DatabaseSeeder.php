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
        User::updateOrCreate(
            ['email' => 'muhamadalipmaulana3@gmail.com'],
            [
                'name' => 'Alip maulana',
                'email' => 'muhamadalipmaulana3@gmail.com',
                'password' => Hash::make('Alip210725_'),
                'role' => 'admin',
            ]
        );
        
        User::updateOrCreate(
            ['email' => 'admin@mail.com'],
            [
                'name' => 'Admin',
                'email' => 'admin@mail.com',
                'password' => Hash::make('admin'),
                'role' => 'admin',
            ]
        );

        User::updateOrCreate(
            ['email' => 'mitra@mail.com'],
            [
                'name' => 'Mitra',
                'email' => 'mitra@mail.com',
                'password' => Hash::make('mitra'),
                'role' => 'mitra',
            ]
        );

        User::updateOrCreate(
            ['email' => 'pelamar@mail.com'],
            [
                'name' => 'Pelamar',
                'email' => 'pelamar@mail.com',
                'password' => Hash::make('pelamar'),
                'role' => 'pelamar',
            ]
        );
    }
}