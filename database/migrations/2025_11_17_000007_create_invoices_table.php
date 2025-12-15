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
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_number')->unique(); // Mã hóa đơn
            $table->foreignId('contract_id')->constrained()->onDelete('cascade');
            $table->foreignId('student_id')->constrained()->onDelete('cascade');
            $table->foreignId('room_id')->constrained()->onDelete('restrict');
            $table->enum('invoice_type', ['rent', 'electricity', 'water', 'service', 'other'])->default('rent');
            $table->string('title'); // Tiêu đề hóa đơn
            $table->text('description')->nullable();
            $table->date('billing_period_start'); // Kỳ tính tiền từ
            $table->date('billing_period_end'); // Kỳ tính tiền đến
            $table->date('issue_date'); // Ngày phát hành
            $table->date('due_date'); // Ngày đến hạn thanh toán
            $table->decimal('amount', 10, 2); // Số tiền
            $table->decimal('electricity_cost', 10, 2)->default(0); // Tiền điện
            $table->decimal('water_cost', 10, 2)->default(0); // Tiền nước
            $table->decimal('service_cost', 10, 2)->default(0); // Phí dịch vụ
            $table->decimal('other_cost', 10, 2)->default(0); // Chi phí khác
            $table->decimal('total_amount', 10, 2); // Tổng tiền
            $table->decimal('paid_amount', 10, 2)->default(0); // Đã thanh toán
            $table->decimal('remaining_amount', 10, 2); // Còn lại
            $table->enum('status', ['pending', 'paid', 'partial', 'overdue', 'cancelled'])->default('pending');
            $table->text('notes')->nullable();
            $table->timestamps();
            
            $table->index(['student_id', 'status']);
            $table->index(['contract_id', 'billing_period_start']);
            $table->index(['due_date', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
