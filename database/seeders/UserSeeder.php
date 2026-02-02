<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::firstOrCreate(['email' => 'admin@stock.test'], [
            'name' => 'Admin',
            'email' => 'admin@stock.test',
            'password' => bcrypt('password'),
            'role' => 'admin',
            'is_active' => true,
        ]);

        User::firstOrCreate(['email' => 'manager@stock.test'], [
            'name' => 'Manager',
            'email' => 'manager@stock.test',
            'password' => bcrypt('password'),
            'role' => 'manager',
            'is_active' => true,
        ]);

        User::firstOrCreate(['email' => 'viewer@stock.test'], [
            'name' => 'Viewer',
            'email' => 'viewer@stock.test',
            'password' => bcrypt('password'),
            'role' => 'viewer',
            'is_active' => true,
        ]);
    }
}
