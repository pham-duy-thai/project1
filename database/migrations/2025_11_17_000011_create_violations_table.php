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
        Schema::create('violations', function (Blueprint $table) {
            $table->id();
            $table->string('violation_code')->unique(); // Mã vi phạm
            $table->foreignId('student_id')->constrained()->onDelete('cascade');
            $table->foreignId('room_id')->nullable()->constrained()->onDelete('set null');
            $table->string('title'); // Tiêu đề vi phạm
            $table->text('description'); // Mô tả chi tiết
            $table->enum('severity', ['minor', 'moderate', 'serious', 'critical'])->default('moderate');
            $table->date('violation_date'); // Ngày vi phạm
            $table->decimal('fine_amount', 10, 2)->default(0); // Tiền phạt
            $table->enum('fine_status', ['unpaid', 'paid', 'waived'])->default('unpaid');
            $table->date('fine_paid_date')->nullable(); // Ngày đóng phạt
            $table->foreignId('reported_by')->constrained('users'); // Người báo cáo
            $table->json('evidence')->nullable(); // Bằng chứng (hình ảnh, file)
            $table->text('action_taken')->nullable(); // Hành động đã thực hiện
            $table->enum('status', ['pending', 'confirmed', 'resolved', 'appealed', 'dismissed'])->default('pending');
            $table->text('notes')->nullable();
            $table->timestamps();
            
            $table->index(['student_id', 'status']);
            $table->index(['violation_date', 'severity']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('violations');
    }
};
