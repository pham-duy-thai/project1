<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Student;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 🔒 Tạm tắt kiểm tra khóa ngoại để tránh lỗi ràng buộc
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // --- 1️⃣ Xóa và tạo lại bảng roles ---
        DB::table('roles')->delete();
        DB::table('roles')->insert([
            ['id' => 1, 'name' => 'Admin', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 2, 'name' => 'Student', 'created_at' => now(), 'updated_at' => now()],
        ]);

        // --- 2️⃣ Tạo tài khoản Admin ---
        $admin = User::create([
            'name' => 'Admin',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('123456'),
            'role_id' => 1,
        ]);

        // --- 3️⃣ Tạo tài khoản Sinh viên ---
        $studentUser = User::create([
            'name' => 'Nguyễn Văn A',
            'email' => 'sinhvien@gmail.com',
            'password' => Hash::make('sv123456'),
            'role_id' => 2,
        ]);

        // --- 4️⃣ Tạo bản ghi sinh viên tương ứng ---
        Student::create([
            'user_id' => $studentUser->id,
            'student_code' => 'SV001',
            'name' => 'Nguyễn Văn A',
            'gender' => 'Nam',
            'class' => 'DHCNTT17A',
            'phone' => '0387597051',
            'address' => 'Hà Nội',
            'date_of_birth' => '2003-05-22',
        ]);

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $this->command->info('✅ Seeder hoàn tất! Đã tạo Admin và Sinh viên mặc định.');
    }
}
