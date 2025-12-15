<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Dormitory;
use App\Models\Building;
use App\Models\RoomType;
use App\Models\Room;
use App\Models\Student;
use App\Models\Amenity;

class DormitorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Create Admin User
        $admin = User::create([
            'name' => 'Admin',
            'email' => 'admin@dormitory.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'is_active' => true,
            'email_verified_at' => now()
        ]);

        // 2. Create Dormitory
        $dormitory = Dormitory::create([
            'name' => 'Ký túc xá Đại học ABC',
            'code' => 'KTX-ABC',
            'address' => '123 Đường XYZ, Quận 1, TP.HCM',
            'phone' => '0281234567',
            'email' => 'ktx@abc.edu.vn',
            'description' => 'Ký túc xá hiện đại với đầy đủ tiện nghi',
            'total_capacity' => 1000,
            'is_active' => true
        ]);

        // 4. Create Buildings
        $buildingA = Building::create([
            'dormitory_id' => $dormitory->id,
            'name' => 'Tòa A',
            'code' => 'A',
            'floors' => 5,
            'gender' => 'male',
            'description' => 'Tòa nam',
            'is_active' => true
        ]);

        $buildingB = Building::create([
            'dormitory_id' => $dormitory->id,
            'name' => 'Tòa B',
            'code' => 'B',
            'floors' => 5,
            'gender' => 'female',
            'description' => 'Tòa nữ',
            'is_active' => true
        ]);

        // 5. Create Room Types
        $roomType2 = RoomType::create([
            'name' => 'Phòng 2 người',
            'code' => 'R2',
            'capacity' => 2,
            'price_per_month' => 1500000,
            'price_per_semester' => 8000000,
            'price_per_year' => 15000000,
            'amenities' => ['Giường', 'Tủ quần áo', 'Bàn học', 'Điều hòa', 'Nước nóng'],
            'description' => 'Phòng 2 người với đầy đủ tiện nghi',
            'is_active' => true
        ]);

        $roomType4 = RoomType::create([
            'name' => 'Phòng 4 người',
            'code' => 'R4',
            'capacity' => 4,
            'price_per_month' => 800000,
            'price_per_semester' => 4500000,
            'price_per_year' => 8500000,
            'amenities' => ['Giường tầng', 'Tủ quần áo', 'Bàn học', 'Quạt trần'],
            'description' => 'Phòng 4 người tiêu chuẩn',
            'is_active' => true
        ]);

        $roomType6 = RoomType::create([
            'name' => 'Phòng 6 người',
            'code' => 'R6',
            'capacity' => 6,
            'price_per_month' => 600000,
            'price_per_semester' => 3200000,
            'price_per_year' => 6000000,
            'amenities' => ['Giường tầng', 'Tủ quần áo', 'Quạt trần'],
            'description' => 'Phòng 6 người tiết kiệm',
            'is_active' => true
        ]);

        // 6. Create Amenities
        $amenities = [
            ['name' => 'Giường đơn', 'code' => 'BED', 'description' => 'Giường đơn 1m x 2m', 'icon' => 'bed'],
            ['name' => 'Giường tầng', 'code' => 'BUNK', 'description' => 'Giường tầng 2 tầng', 'icon' => 'bed-bunk'],
            ['name' => 'Tủ quần áo', 'code' => 'WARDROBE', 'description' => 'Tủ quần áo 2 cánh', 'icon' => 'wardrobe'],
            ['name' => 'Bàn học', 'code' => 'DESK', 'description' => 'Bàn học kèm ghế', 'icon' => 'desk'],
            ['name' => 'Điều hòa', 'code' => 'AC', 'description' => 'Máy lạnh 12000 BTU', 'icon' => 'air-conditioner'],
            ['name' => 'Quạt trần', 'code' => 'FAN', 'description' => 'Quạt trần 3 cánh', 'icon' => 'fan'],
            ['name' => 'Bình nóng lạnh', 'code' => 'WATER', 'description' => 'Bình nước nóng 20L', 'icon' => 'water-heater'],
        ];

        foreach ($amenities as $amenity) {
            Amenity::create($amenity);
        }

        // 7. Create Rooms for Building A (Male)
        $roomTypes = [$roomType2, $roomType4, $roomType6];
        for ($floor = 1; $floor <= 5; $floor++) {
            for ($roomNum = 1; $roomNum <= 10; $roomNum++) {
                $roomType = $roomTypes[($floor + $roomNum) % 3];
                $roomNumber = sprintf('%d%02d', $floor, $roomNum);
                
                Room::create([
                    'building_id' => $buildingA->id,
                    'room_type_id' => $roomType->id,
                    'room_number' => $roomNumber,
                    'floor' => $floor,
                    'capacity' => $roomType->capacity,
                    'current_occupancy' => 0,
                    'area' => 20,
                    'status' => 'available',
                    'is_active' => true
                ]);
            }
        }

        // 8. Create Rooms for Building B (Female)
        for ($floor = 1; $floor <= 5; $floor++) {
            for ($roomNum = 1; $roomNum <= 10; $roomNum++) {
                $roomType = $roomTypes[($floor + $roomNum) % 3];
                $roomNumber = sprintf('%d%02d', $floor, $roomNum);
                
                Room::create([
                    'building_id' => $buildingB->id,
                    'room_type_id' => $roomType->id,
                    'room_number' => $roomNumber,
                    'floor' => $floor,
                    'capacity' => $roomType->capacity,
                    'current_occupancy' => 0,
                    'area' => 20,
                    'status' => 'available',
                    'is_active' => true
                ]);
            }
        }

        // 9. Create Sample Students
        for ($i = 1; $i <= 20; $i++) {
            $gender = $i <= 10 ? 'male' : 'female';
            $user = User::create([
                'name' => 'Student ' . $i,
                'email' => 'student' . $i . '@abc.edu.vn',
                'password' => Hash::make('password'),
                'role' => 'student',
                'is_active' => true,
                'email_verified_at' => now()
            ]);

            Student::create([
                'user_id' => $user->id,
                'student_code' => 'SV' . str_pad($i, 6, '0', STR_PAD_LEFT),
                'full_name' => 'Sinh viên ' . $i,
                'date_of_birth' => now()->subYears(20)->format('Y-m-d'),
                'gender' => $gender,
                'phone' => '09' . rand(10000000, 99999999),
                'email' => 'student' . $i . '@abc.edu.vn',
                'citizen_id' => '0' . rand(100000000, 999999999),
                'permanent_address' => 'Địa chỉ thường trú ' . $i,
                'contact_address' => 'Địa chỉ liên hệ ' . $i,
                'parent_name' => 'Phụ huynh ' . $i,
                'parent_phone' => '09' . rand(10000000, 99999999),
                'faculty' => 'Khoa Công nghệ thông tin',
                'major' => 'Kỹ thuật phần mềm',
                'class' => 'K' . (20 + ($i % 3)),
                'academic_year' => 2024,
                'status' => 'active'
            ]);
        }

        echo "✅ Seeding completed successfully!\n";
        echo "🔑 Login credentials:\n";
        echo "   Admin: admin@dormitory.com / password\n";
        echo "   Manager: manager@dormitory.com / password\n";
        echo "   Students: student1@abc.edu.vn to student20@abc.edu.vn / password\n";
    }
}

