<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\RoomRegistration;

class RoomRegistrationSeeder extends Seeder
{
    public function run(): void
    {
        RoomRegistration::insert([
            [
                'student_id' => 1,
                'room_id' => 1,
                'registration_date' => now(),
                'status' => 'active',
            ],
            [
                'student_id' => 2,
                'room_id' => 2,
                'registration_date' => now(),
                'status' => 'active',
            ],
        ]);
    }
}
