<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Service;

class ServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $services = [
            [
                'name' => 'Internet WiFi',
                'description' => 'Dịch vụ internet tốc độ cao, kết nối ổn định 24/7. Phù hợp cho học tập và giải trí.',
                'type' => 'monthly',
                'price' => 100000,
                'unit' => 'tháng',
                'icon' => 'ri-wifi-line',
                'is_active' => true,
                'requires_approval' => false,
                'max_registrations' => null,
            ],
            [
                'name' => 'Giặt ủi',
                'description' => 'Dịch vụ giặt ủi chuyên nghiệp, giá cả hợp lý. Giặt khô, giặt ướt, ủi là.',
                'type' => 'per_use',
                'price' => 15000,
                'unit' => 'kg',
                'icon' => 'ri-t-shirt-line',
                'is_active' => true,
                'requires_approval' => false,
                'max_registrations' => null,
            ],
            [
                'name' => 'Gửi xe máy',
                'description' => 'Dịch vụ gửi xe máy an toàn, có bảo vệ 24/7. Khu vực gửi xe rộng rãi, có mái che.',
                'type' => 'monthly',
                'price' => 50000,
                'unit' => 'tháng',
                'icon' => 'ri-riding-line',
                'is_active' => true,
                'requires_approval' => true,
                'max_registrations' => 200,
            ],
            [
                'name' => 'Gửi xe đạp',
                'description' => 'Dịch vụ gửi xe đạp miễn phí cho sinh viên. Khu vực gửi xe có mái che.',
                'type' => 'monthly',
                'price' => 0,
                'unit' => 'tháng',
                'icon' => 'ri-bike-line',
                'is_active' => true,
                'requires_approval' => false,
                'max_registrations' => null,
            ],
            [
                'name' => 'Phòng gym',
                'description' => 'Phòng tập gym hiện đại với đầy đủ thiết bị. Có HLV hướng dẫn từ 6h-22h.',
                'type' => 'monthly',
                'price' => 200000,
                'unit' => 'tháng',
                'icon' => 'ri-walk-line',
                'is_active' => true,
                'requires_approval' => false,
                'max_registrations' => 50,
            ],
            [
                'name' => 'In ấn photocopy',
                'description' => 'Dịch vụ in ấn, photocopy nhanh chóng. Hỗ trợ in màu, in A3, đóng tài liệu.',
                'type' => 'per_use',
                'price' => 500,
                'unit' => 'trang',
                'icon' => 'ri-printer-line',
                'is_active' => true,
                'requires_approval' => false,
                'max_registrations' => null,
            ],
            [
                'name' => 'Nước uống',
                'description' => 'Dịch vụ cung cấp nước uống tinh khiết. Bình nước 20L giao tận phòng.',
                'type' => 'per_use',
                'price' => 25000,
                'unit' => 'bình',
                'icon' => 'ri-drop-line',
                'is_active' => true,
                'requires_approval' => false,
                'max_registrations' => null,
            ],
            [
                'name' => 'Dọn phòng định kỳ',
                'description' => 'Dịch vụ dọn dẹp phòng định kỳ hàng tuần. Bao gồm lau dọn, sắp xếp đồ đạc.',
                'type' => 'monthly',
                'price' => 150000,
                'unit' => 'tháng',
                'icon' => 'ri-broom-line',
                'is_active' => true,
                'requires_approval' => false,
                'max_registrations' => null,
            ],
        ];

        foreach ($services as $service) {
            Service::create($service);
        }
    }
}
