<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => 'password', // otomatis di-hash oleh cast di model

        ]);

        User::create([
            'name' => 'Participant User',
            'email' => 'participant@example.com',
            'password' => 'password',
        ]);

        User::create([
            'name' => 'Committee User',
            'email' => 'committee@example.com',
            'password' => 'password',
        ]);
    }
}