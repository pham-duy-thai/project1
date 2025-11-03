<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            RoleSeeder::class,
            UserSeeder::class,
            StudentSeeder::class,
            BuildingSeeder::class,
            FloorSeeder::class,
            RoomSeeder::class,
            RuleSeeder::class,
            ServiceSeeder::class,
            RoomRegistrationSeeder::class,
            ContractSeeder::class,
        ]);
    }
}
