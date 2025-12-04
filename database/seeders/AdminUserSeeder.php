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
            'phone' => '1234567890',
            'role' => 'admin',
            'password' => Hash::make('superadmin123'),
        ]);

        User::create([
            'username' => 'usertoko',
            'email' => 'usertoko@gmail.com',
            'phone' => '0987654321',
            'role' => 'user',
            'password' => Hash::make('user123'),
        ]);

    }
}
