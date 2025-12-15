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
        Schema::create('contracts', function (Blueprint $table) {
            $table->id();
            $table->string('contract_number')->unique(); // Số hợp đồng
            $table->foreignId('student_id')->constrained()->onDelete('cascade');
            $table->foreignId('room_id')->constrained()->onDelete('restrict');
            $table->date('start_date'); // Ngày bắt đầu
            $table->date('end_date'); // Ngày kết thúc
            $table->decimal('monthly_rent', 10, 2); // Tiền thuê hàng tháng
            $table->decimal('deposit_amount', 10, 2)->default(0); // Tiền đặt cọc
            $table->decimal('total_amount', 10, 2); // Tổng tiền
            $table->enum('payment_cycle', ['monthly', 'semester', 'yearly'])->default('monthly');
            $table->enum('status', ['pending', 'active', 'expired', 'terminated', 'cancelled'])->default('pending');
            $table->text('notes')->nullable();
            $table->timestamp('signed_at')->nullable(); // Ngày ký
            $table->foreignId('signed_by')->nullable()->constrained('users'); // Người ký (admin/manager)
            $table->timestamp('terminated_at')->nullable(); // Ngày chấm dứt
            $table->text('termination_reason')->nullable(); // Lý do chấm dứt
            $table->timestamps();
            
            $table->index(['student_id', 'room_id', 'status']);
            $table->index(['start_date', 'end_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contracts');
    }
};
