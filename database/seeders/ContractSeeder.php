<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Contract;

class ContractSeeder extends Seeder
{
    public function run(): void
    {
        Contract::insert([
            [
                'student_id' => 1,
                'room_id' => 1,
                'start_date' => now(),
                'end_date' => now()->addMonths(6),
                'status' => 'active',
                'total_price' => 6000000,
            ],
            [
                'student_id' => 2,
                'room_id' => 2,
                'start_date' => now(),
                'end_date' => now()->addMonths(6),
                'status' => 'active',
                'total_price' => 6000000,
            ],
        ]);
    }
}
