<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');

            // ✅ Cột role_id liên kết tới bảng roles
            $table->foreignId('role_id')
                ->nullable()
                ->constrained('roles')
                ->onDelete('set null');

            $table->rememberToken(); // Dùng cho chức năng "ghi nhớ đăng nhập"
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
