<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'username' => 'admin',
            'email' => 'admin@ronalaundry.com',
            'password' => bcrypt('admin123'),
            'nama_lengkap' => 'Administrator',
            'role' => 'admin',
        ]);
    }
}