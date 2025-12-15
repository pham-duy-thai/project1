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
        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Tên dịch vụ
            $table->text('description')->nullable(); // Mô tả
            $table->enum('type', ['monthly', 'one_time', 'per_use'])->default('monthly'); // Loại phí
            $table->decimal('price', 10, 2); // Giá dịch vụ
            $table->string('unit')->nullable(); // Đơn vị (tháng, lần, ...)
            $table->string('icon')->nullable(); // Icon class (ri-wifi-line, ri-car-line...)
            $table->boolean('is_active')->default(true);
            $table->boolean('requires_approval')->default(false); // Cần duyệt không
            $table->integer('max_registrations')->nullable(); // Giới hạn số lượng đăng ký
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('services');
    }
};
