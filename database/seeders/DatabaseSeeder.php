<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        DB::table('statistics')->truncate();
        DB::table('contracts')->truncate();
        DB::table('room_registrations')->truncate();
        DB::table('services')->truncate();
        DB::table('rules')->truncate();
        DB::table('rooms')->truncate();
        DB::table('floors')->truncate();
        DB::table('buildings')->truncate();
        DB::table('students')->truncate();
        DB::table('users')->truncate();
        DB::table('roles')->truncate();

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // ROLES
        DB::table('roles')->insert([
            ['name' => 'admin'],
            ['name' => 'student'],
        ]);

        // USERS
        DB::table('users')->insert([
            [
                'name' => 'Admin',
                'email' => 'admin@gmail.com',
                'password' => Hash::make('123456'),
                'role_id' => 1
            ],
            [
                'name' => 'Student User 1',
                'email' => 'student1@gmail.com',
                'password' => Hash::make('123456'),
                'role_id' => 2
            ],
            [
                'name' => 'Student User 2',
                'email' => 'student2@gmail.com',
                'password' => Hash::make('123456'),
                'role_id' => 2
            ],
            [
                'name' => 'Student User 3',
                'email' => 'student3@gmail.com',
                'password' => Hash::make('123456'),
                'role_id' => 2
            ],
            [
                'name' => 'Student User 4',
                'email' => 'student4@gmail.com',
                'password' => Hash::make('123456'),
                'role_id' => 2
            ],
            [
                'name' => 'Student User 5',
                'email' => 'student5@gmail.com',
                'password' => Hash::make('123456'),
                'role_id' => 2
            ],
        ]);

        // STUDENTS (gán đúng user_id)
        DB::table('students')->insert([
            [
                'student_code' => 'SV001',
                'name' => 'Nguyen Van A',
                'gender' => 'nam',
                'class' => 'CTK42',
                'user_id' => 2
            ],
            [
                'student_code' => 'SV002',
                'name' => 'Tran Thi B',
                'gender' => 'nu',
                'class' => 'CTK43',
                'user_id' => 3
            ],
            [
                'student_code' => 'SV003',
                'name' => 'Le Van C',
                'gender' => 'nam',
                'class' => 'CTK44',
                'user_id' => 4
            ],
            [
                'student_code' => 'SV004',
                'name' => 'Pham Thi D',
                'gender' => 'nu',
                'class' => 'CTK42',
                'user_id' => 5
            ],
            [
                'student_code' => 'SV005',
                'name' => 'Hoang Van E',
                'gender' => 'nam',
                'class' => 'CTK41',
                'user_id' => 6
            ],
        ]);

        // BUILDINGS
        DB::table('buildings')->insert([
            ['name' => 'A', 'total_floors' => 5],
            ['name' => 'B', 'total_floors' => 4],
            ['name' => 'C', 'total_floors' => 3],
            ['name' => 'D', 'total_floors' => 2],
            ['name' => 'E', 'total_floors' => 6],
        ]);

        // FLOORS
        DB::table('floors')->insert([
            ['building_id' => 1, 'floor_number' => 1],
            ['building_id' => 1, 'floor_number' => 2],
            ['building_id' => 2, 'floor_number' => 1],
            ['building_id' => 3, 'floor_number' => 1],
            ['building_id' => 5, 'floor_number' => 3],
        ]);

        // ROOMS
        DB::table('rooms')->insert([
            [
                'building_id' => 1,
                'floor_number' => 1,
                'room_number' => 'A101',
                'capacity' => 4,
                'gender' => 'nam',
                'price' => 500000
            ],
            [
                'building_id' => 1,
                'floor_number' => 2,
                'room_number' => 'A201',
                'capacity' => 4,
                'gender' => 'nu',
                'price' => 500000
            ],
            [
                'building_id' => 2,
                'floor_number' => 1,
                'room_number' => 'B101',
                'capacity' => 6,
                'gender' => 'nam',
                'price' => 450000
            ],
            [
                'building_id' => 3,
                'floor_number' => 1,
                'room_number' => 'C101',
                'capacity' => 3,
                'gender' => 'nu',
                'price' => 600000
            ],
            [
                'building_id' => 5,
                'floor_number' => 3,
                'room_number' => 'E301',
                'capacity' => 8,
                'gender' => 'nam',
                'price' => 550000
            ],
        ]);

        // RULES
        DB::table('rules')->insert([
            ['title' => 'Giờ giấc KTX', 'description' => 'Đóng cổng lúc 23h00'],
            ['title' => 'Bảo vệ tài sản', 'description' => 'Không tự ý di chuyển tài sản chung'],
            ['title' => 'Vệ sinh chung', 'description' => 'Giữ vệ sinh trong phòng và khu vực chung'],
            ['title' => 'Điện thoại', 'description' => 'Không gây ồn sau 22h'],
            ['title' => 'Sinh hoạt chung', 'description' => 'Không tụ tập quá đông trong phòng'],
        ]);

        // SERVICES
        DB::table('services')->insert([
            ['name' => 'Giặt ủi', 'price' => 30000],
            ['name' => 'Gửi xe', 'price' => 50000],
            ['name' => 'Nước uống', 'price' => 10000],
            ['name' => 'Internet', 'price' => 70000],
            ['name' => 'Điện', 'price' => 3500],
        ]);

        // ROOM REGISTRATIONS
        DB::table('room_registrations')->insert([
            ['student_id' => 1, 'room_id' => 1, 'status' => 'approved'],
            ['student_id' => 2, 'room_id' => 2, 'status' => 'pending'],
            ['student_id' => 3, 'room_id' => 3, 'status' => 'approved'],
            ['student_id' => 4, 'room_id' => 4, 'status' => 'completed'],
            ['student_id' => 5, 'room_id' => 5, 'status' => 'pending'],
        ]);

        // CONTRACTS
        DB::table('contracts')->insert([
            [
                'room_registration_id' => 1,
                'start_date' => '2024-01-01',
                'end_date' => '2024-06-30',
                'total_amount' => 3000000
            ],
            [
                'room_registration_id' => 2,
                'start_date' => '2024-02-01',
                'end_date' => '2024-07-31',
                'total_amount' => 3200000
            ],
            [
                'room_registration_id' => 3,
                'start_date' => '2024-03-01',
                'end_date' => '2024-08-31',
                'total_amount' => 2800000
            ],
            [
                'room_registration_id' => 4,
                'start_date' => '2024-01-15',
                'end_date' => '2024-06-15',
                'total_amount' => 3100000
            ],
            [
                'room_registration_id' => 5,
                'start_date' => '2024-04-01',
                'end_date' => '2024-09-30',
                'total_amount' => 2900000
            ],
        ]);

        // STATISTICS
        DB::table('statistics')->insert([
            ['room_id' => 1, 'month' => 1, 'year' => 2024, 'total_students' => 4, 'revenue' => 2000000],
            ['room_id' => 2, 'month' => 1, 'year' => 2024, 'total_students' => 3, 'revenue' => 1500000],
            ['room_id' => 3, 'month' => 1, 'year' => 2024, 'total_students' => 6, 'revenue' => 2700000],
            ['room_id' => 4, 'month' => 1, 'year' => 2024, 'total_students' => 2, 'revenue' => 1200000],
            ['room_id' => 5, 'month' => 1, 'year' => 2024, 'total_students' => 5, 'revenue' => 2750000],
        ]);
    }
}
