<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Floor;

class FloorSeeder extends Seeder
{
    public function run(): void
    {
        Floor::insert([
            ['name' => 'Tầng 1', 'building_id' => 1],
            ['name' => 'Tầng 2', 'building_id' => 1],
            ['name' => 'Tầng 1', 'building_id' => 2],
        ]);
    }
}
