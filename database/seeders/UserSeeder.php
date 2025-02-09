<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run()
    {
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => Hash::make('12341234'),
            'role' => 'admin',
        ]);

        User::create([
            'name' => 'Writer User',
            'email' => 'writer@example.com',
            'password' => Hash::make('12341234'),
            'role' => 'writer',
        ]);

        User::create([
            'name' => 'Regular User',
            'email' => 'user@example.com',
            'password' => Hash::make('12341234'),
            'role' => 'user',
        ]);
    }
}