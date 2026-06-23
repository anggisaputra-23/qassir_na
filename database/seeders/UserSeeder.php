<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Demo Owner',
            'email' => 'demo@owner.com',
            'password' => Hash::make('password'),
            'role' => 'owner',
        ]);

        User::create([
            'name' => 'Demo Kasir',
            'email' => 'demo@kasir.com',
            'password' => Hash::make('password'),
            'role' => 'karyawan',
        ]);
    }
}
