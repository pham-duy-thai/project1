<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Thêm trạng thái 'processed' vào enum status
        DB::statement("ALTER TABLE maintenance_requests MODIFY COLUMN status ENUM('pending', 'approved', 'in_progress', 'processed', 'completed', 'rejected', 'cancelled') DEFAULT 'pending'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Xóa trạng thái 'processed' khỏi enum (chuyển các bản ghi 'processed' về 'completed')
        DB::statement("UPDATE maintenance_requests SET status = 'completed' WHERE status = 'processed'");
        DB::statement("ALTER TABLE maintenance_requests MODIFY COLUMN status ENUM('pending', 'approved', 'in_progress', 'completed', 'rejected', 'cancelled') DEFAULT 'pending'");
    }
};
