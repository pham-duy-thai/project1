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
        Schema::create('maintenance_requests', function (Blueprint $table) {
            $table->id();
            $table->string('request_code')->unique(); // Mã yêu cầu
            $table->foreignId('room_id')->constrained()->onDelete('cascade');
            $table->foreignId('student_id')->constrained()->onDelete('cascade');
            $table->string('title'); // Tiêu đề
            $table->text('description'); // Mô tả chi tiết
            $table->enum('category', ['plumbing', 'electrical', 'furniture', 'air_conditioning', 'door_lock', 'window', 'other'])->default('other');
            $table->enum('priority', ['low', 'medium', 'high', 'urgent'])->default('medium');
            $table->enum('status', ['pending', 'approved', 'in_progress', 'completed', 'rejected', 'cancelled'])->default('pending');
            $table->json('images')->nullable(); // Hình ảnh
            $table->timestamp('requested_at'); // Thời gian yêu cầu
            $table->timestamp('approved_at')->nullable();
            $table->timestamp('started_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->foreignId('assigned_to')->nullable()->constrained('users'); // Người được giao
            $table->text('admin_notes')->nullable(); // Ghi chú từ admin
            $table->text('completion_notes')->nullable(); // Ghi chú khi hoàn thành
            $table->decimal('cost', 10, 2)->nullable(); // Chi phí sửa chữa
            $table->timestamps();
            
            $table->index(['room_id', 'status']);
            $table->index(['student_id', 'status']);
            $table->index(['priority', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('maintenance_requests');
    }
};
