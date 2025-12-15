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
        Schema::create('announcements', function (Blueprint $table) {
            $table->id();
            $table->string('title'); // Tiêu đề thông báo
            $table->text('content'); // Nội dung
            $table->enum('type', ['general', 'maintenance', 'payment', 'event', 'emergency', 'rule'])->default('general');
            $table->enum('priority', ['low', 'medium', 'high', 'urgent'])->default('medium');
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->timestamp('published_at')->nullable(); // Thời gian xuất bản
            $table->timestamp('expires_at')->nullable(); // Thời gian hết hạn
            $table->boolean('is_published')->default(false);
            $table->boolean('send_email')->default(false); // Gửi email
            $table->boolean('send_notification')->default(true); // Gửi thông báo
            $table->json('target_audience')->nullable(); // Đối tượng nhận: all, specific_building, specific_room
            $table->json('attachments')->nullable(); // File đính kèm
            $table->timestamps();
            
            $table->index(['is_published', 'published_at']);
            $table->index(['type', 'priority']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('announcements');
    }
};
