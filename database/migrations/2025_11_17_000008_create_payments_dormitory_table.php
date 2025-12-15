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
        // Drop old payments table if exists
        Schema::dropIfExists('payments');
        
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->string('payment_code')->unique(); // Mã thanh toán
            $table->foreignId('invoice_id')->constrained()->onDelete('cascade');
            $table->foreignId('student_id')->constrained()->onDelete('cascade');
            $table->decimal('amount', 10, 2); // Số tiền thanh toán
            $table->enum('payment_method', ['cash', 'bank_transfer', 'vnpay', 'momo', 'zalopay'])->default('cash');
            $table->date('payment_date'); // Ngày thanh toán
            $table->string('transaction_id')->nullable(); // Mã giao dịch (với thanh toán online)
            $table->text('notes')->nullable();
            $table->enum('status', ['pending', 'completed', 'failed', 'refunded', 'cancelled'])->default('completed');
            $table->foreignId('received_by')->nullable()->constrained('users'); // Người thu tiền
            $table->string('receipt_number')->nullable(); // Số biên lai
            $table->json('payment_details')->nullable(); // Chi tiết thanh toán (response từ gateway)
            $table->timestamps();
            
            $table->index(['invoice_id', 'status']);
            $table->index(['student_id', 'payment_date']);
            $table->index(['payment_code', 'transaction_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
