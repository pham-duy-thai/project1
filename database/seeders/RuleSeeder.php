<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Rule;

class RuleSeeder extends Seeder
{
    public function run(): void
    {
        Rule::insert([
            [
                'title' => 'Giữ gìn vệ sinh chung',
                'description' => 'Sinh viên phải giữ vệ sinh phòng ở, hành lang, khu vệ sinh và khu vực sinh hoạt chung sạch sẽ.',
            ],
            [
                'title' => 'Không gây ồn ào',
                'description' => 'Không làm ồn, mở nhạc lớn hoặc tụ tập đông người sau 22h00.',
            ],
            [
                'title' => 'Bảo quản tài sản',
                'description' => 'Không làm hư hại cơ sở vật chất, thiết bị trong phòng và khu ký túc xá.',
            ],
            [
                'title' => 'Giờ giấc ra vào',
                'description' => 'Sinh viên phải tuân thủ giờ ra vào ký túc xá từ 5h00 đến 23h00 hàng ngày.',
            ],
        ]);
    }
}
