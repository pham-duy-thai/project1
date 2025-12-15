<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('student_code')->unique(); // Mã sinh viên
            $table->string('full_name');
            $table->date('date_of_birth');
            $table->enum('gender', ['male', 'female', 'other']);
            $table->string('phone');
            $table->string('email');
            $table->string('citizen_id')->nullable(); // CMND/CCCD
            $table->text('permanent_address'); // Địa chỉ thường trú
            $table->text('contact_address')->nullable(); // Địa chỉ liên hệ
            $table->string('parent_name')->nullable(); // Tên phụ huynh
            $table->string('parent_phone')->nullable(); // SĐT phụ huynh
            $table->string('faculty')->nullable(); // Khoa
            $table->string('major')->nullable(); // Ngành
            $table->string('class')->nullable(); // Lớp
            $table->integer('academic_year')->nullable(); // Năm học
            $table->string('avatar')->nullable();
            $table->enum('status', ['active', 'inactive', 'graduated', 'suspended'])->default('active');
            $table->timestamps();
            
            $table->index(['student_code', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
