<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Admin
        User::create([
            'name' => 'Admin chính',
            'email' => 'admin@ktx.com',
            'password' => bcrypt('123456'),
            'role_id' => 1,
        ]);




        // Sinh viên
        User::create([
            'name' => 'Sinh viên 1',
            'email' => 'student1@ktx.com',
            'password' => bcrypt('123456'),
            'role_id' => 2,
        ]);

        User::create([
            'name' => 'Sinh viên 2',
            'email' => 'student2@ktx.com',
            'password' => bcrypt('123456'),
            'role_id' => 2,
        ]);
    }
}
