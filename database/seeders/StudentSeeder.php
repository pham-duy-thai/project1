<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Student;

class StudentSeeder extends Seeder
{
    public function run(): void
    {
        Student::insert([
            [
                'student_code' => 'SV001',
                'name' => 'Nguyễn Văn A',
                'gender' => 'Nam',
                'class' => '63K1',
                'phone' => '0387000001',
                'status' => 'active',
            ],
            [
                'student_code' => 'SV002',
                'name' => 'Trần Thị B',
                'gender' => 'Nữ',
                'class' => '63K1',
                'phone' => '0387000002',
                'status' => 'active',
            ],
        ]);
    }
}
