<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Database\Seeder;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'username' => 'superadmin',
            'email' => 'superadmin@gmail.com',
            'role' => 'admin', // Role untuk akses penuh
            'password' => Hash::make('superadmin123'),
        ]);

        User::create([
            'username' => 'usertoko',
            'email' => 'usertoko@gmail.com',
            'role' => 'user', // Role untuk user biasa
            'password' => Hash::make('user123'),
        ]);

    }
}
