<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Service;

class ServiceSeeder extends Seeder
{
    public function run(): void
    {
        Service::insert([
            [
                'name' => 'Điện',
                'price' => 3500,
                'unit' => 'kWh',
                'description' => 'Tính theo số điện tiêu thụ thực tế hàng tháng.',
            ],
            [
                'name' => 'Nước',
                'price' => 20000,
                'unit' => 'm3',
                'description' => 'Tính theo số nước tiêu thụ thực tế hàng tháng.',
            ],
            [
                'name' => 'Internet',
                'price' => 50000,
                'unit' => 'tháng',
                'description' => 'Phí sử dụng mạng Internet tốc độ cao trong ký túc xá.',
            ],
            [
                'name' => 'Giặt ủi',
                'price' => 10000,
                'unit' => 'kg',
                'description' => 'Dịch vụ giặt quần áo theo cân nặng, có tính phí.',
            ],
        ]);
    }
}
