<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Rule;

class RuleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $rules = [
            [
                'title' => 'Giữ gìn vệ sinh phòng ở',
                'content' => "- Sinh viên phải giữ gìn vệ sinh phòng ở sạch sẽ, ngăn nắp.\n- Quét dọn phòng hàng ngày.\n- Không vứt rác bừa bãi trong phòng và hành lang.\n- Tham gia vệ sinh khu vực chung theo lịch phân công.",
                'category' => 'hygiene',
                'severity' => 'medium',
                'penalty_amount' => 100000,
                'order' => 1,
                'requires_acknowledgment' => true,
                'is_active' => true,
            ],
            [
                'title' => 'Tiết kiệm điện nước',
                'content' => "- Tắt điện, nước khi không sử dụng.\n- Không để đèn sáng khi ra ngoài.\n- Sử dụng điện, nước tiết kiệm, đúng mục đích.\n- Không sử dụng các thiết bị điện công suất lớn không được phép.",
                'category' => 'general',
                'severity' => 'medium',
                'penalty_amount' => 200000,
                'order' => 2,
                'requires_acknowledgment' => true,
                'is_active' => true,
            ],
            [
                'title' => 'An toàn phòng cháy chữa cháy',
                'content' => "- Không được nấu ăn trong phòng.\n- Không sử dụng bếp điện, bếp gas trong phòng.\n- Không hút thuốc trong phòng và khu vực ký túc xá.\n- Không đốt nến, đèn dầu trong phòng.\n- Không để các vật dụng dễ cháy gần nguồn nhiệt.",
                'category' => 'safety',
                'severity' => 'high',
                'penalty_amount' => 500000,
                'order' => 3,
                'requires_acknowledgment' => true,
                'is_active' => true,
            ],
            [
                'title' => 'Giờ giấc sinh hoạt',
                'content' => "- Giờ vào phòng: Trước 23h00.\n- Giờ tắt đèn: 23h30.\n- Không gây ồn ào sau 22h00.\n- Tôn trọng giấc ngủ của các bạn khác.\n- Không tổ chức tiệc tùng, sinh nhật ồn ào trong phòng.",
                'category' => 'discipline',
                'severity' => 'medium',
                'penalty_amount' => 150000,
                'order' => 4,
                'requires_acknowledgment' => true,
                'is_active' => true,
            ],
            [
                'title' => 'Bảo quản tài sản',
                'content' => "- Bảo quản tốt tài sản trong phòng (giường, tủ, bàn ghế...).\n- Không tự ý di chuyển, sửa chữa tài sản.\n- Báo ngay cho ban quản lý nếu có hư hỏng.\n- Chịu trách nhiệm bồi thường nếu làm hư hỏng, mất mát tài sản.",
                'category' => 'general',
                'severity' => 'medium',
                'penalty_amount' => null,
                'order' => 5,
                'requires_acknowledgment' => false,
                'is_active' => true,
            ],
            [
                'title' => 'Khách đến thăm',
                'content' => "- Khách đến thăm phải đăng ký tại bảo vệ.\n- Khách chỉ được vào khu vực tiếp khách, không được vào phòng.\n- Thời gian tiếp khách: 8h00 - 22h00.\n- Sinh viên chịu trách nhiệm về khách của mình.",
                'category' => 'discipline',
                'severity' => 'low',
                'penalty_amount' => 100000,
                'order' => 6,
                'requires_acknowledgment' => false,
                'is_active' => true,
            ],
            [
                'title' => 'Cam kết không sử dụng chất kích thích',
                'content' => "- Nghiêm cấm sử dụng rượu bia, thuốc lá, ma túy và các chất kích thích khác.\n- Nghiêm cấm tàng trữ, mua bán các chất trên.\n- Vi phạm sẽ bị xử lý kỷ luật nghiêm khắc và thông báo nhà trường.",
                'category' => 'discipline',
                'severity' => 'high',
                'penalty_amount' => 1000000,
                'order' => 7,
                'requires_acknowledgment' => true,
                'is_active' => true,
            ],
        ];

        foreach ($rules as $rule) {
            Rule::create($rule);
        }
    }
}
