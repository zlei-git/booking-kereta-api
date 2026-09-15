<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Administrator',
            'email' => 'admin@nusarail.id',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'phone' => '021-121'
        ]);

        User::create([
            'name' => 'Budi Santoso',
            'email' => 'budi@demo.id',
            'password' => Hash::make('password'),
            'role' => 'customer',
            'phone' => '081234567890'
        ]);
    }
}
