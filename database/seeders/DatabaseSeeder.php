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
        // ✅ Tạm tắt kiểm tra khóa ngoại để tránh lỗi truncate
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // ✅ Xóa dữ liệu cũ (nếu có)
        DB::table('students')->truncate();
        DB::table('users')->truncate();
        DB::table('roles')->truncate();

        // ✅ 1️⃣ Tạo bảng roles cơ bản (nếu chưa có)
        DB::table('roles')->insert([
            ['id' => 1, 'name' => 'admin'],
            ['id' => 2, 'name' => 'student'],
        ]);

        // ✅ 2️⃣ Tạo tài khoản Admin
        $admin = User::create([
            'name' => 'Quản trị viên',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('123456'), // mật khẩu mặc định
            'role_id' => 1, // Gán role Admin
        ]);

        // ✅ 3️⃣ Tạo tài khoản Sinh viên
        $studentUser = User::create([
            'name' => 'Nguyễn Văn A',
            'email' => 'sinhvien@gmail.com',
            'password' => Hash::make('sv123456'),
            'role_id' => 2, // Gán role Student
        ]);

        // ✅ 4️⃣ Tạo thông tin sinh viên tương ứng
        Student::create([
            'user_id' => $studentUser->id,
            'student_code' => 'SV001',
            'name' => 'Nguyễn Văn A',
            'gender' => 'Nam',
            'class' => 'DHCNTT17A',
            'phone' => '0387597051',
            'address' => 'Nghệ An',
            'date_of_birth' => '2003-06-15',
            'status' => 'active',
        ]);

        // ✅ Bật lại kiểm tra khóa ngoại
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $this->command->info('✅ Seeder hoàn tất! Đã tạo tài khoản Admin và Sinh viên mặc định.');
        $this->command->warn('👤 Admin: admin@gmail.com / 123456');
        $this->command->warn('🎓 Student: sinhvien@gmail.com / sv123456');
    }
}
