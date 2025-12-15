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
        Schema::table('announcements', function (Blueprint $table) {
            // Thêm cột target_audience dạng string thay vì JSON
            $table->dropColumn('target_audience');
            $table->enum('target_audience', ['all', 'students', 'staff', 'building', 'room'])->default('all')->after('send_notification');
            $table->unsignedBigInteger('target_id')->nullable()->after('target_audience')->comment('ID của building hoặc room khi target_audience = building/room');
            
            // Index
            $table->index(['target_audience', 'target_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('announcements', function (Blueprint $table) {
            $table->dropIndex(['target_audience', 'target_id']);
            $table->dropColumn('target_id');
            $table->dropColumn('target_audience');
            $table->json('target_audience')->nullable()->after('send_notification');
        });
    }
};
