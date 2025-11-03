<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Building;

class BuildingSeeder extends Seeder
{
    public function run(): void
    {
        Building::insert([
            ['name' => 'Tòa A', 'address' => 'Đường 1'],
            ['name' => 'Tòa B', 'address' => 'Đường 2'],
        ]);
    }
}
