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
        Schema::create('rules', function (Blueprint $table) {
            $table->id();
            $table->string('title'); // Tiêu đề nội quy
            $table->text('content'); // Nội dung chi tiết
            $table->enum('category', ['safety', 'hygiene', 'discipline', 'general'])->default('general'); // Phân loại
            $table->enum('severity', ['low', 'medium', 'high'])->default('medium'); // Mức độ nghiêm trọng
            $table->decimal('penalty_amount', 10, 2)->nullable(); // Mức phạt (nếu có)
            $table->integer('order')->default(0); // Thứ tự hiển thị
            $table->boolean('is_active')->default(true);
            $table->boolean('requires_acknowledgment')->default(false); // Có yêu cầu xác nhận đã đọc không
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rules');
    }
};
