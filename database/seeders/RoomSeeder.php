<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Room;

class RoomSeeder extends Seeder
{
    public function run(): void
    {
        Room::insert([
            ['name' => '101', 'floor_id' => 1, 'capacity' => 4, 'price' => 1000000],
            ['name' => '102', 'floor_id' => 1, 'capacity' => 4, 'price' => 1000000],
            ['name' => '201', 'floor_id' => 2, 'capacity' => 6, 'price' => 1200000],
        ]);
    }
}
