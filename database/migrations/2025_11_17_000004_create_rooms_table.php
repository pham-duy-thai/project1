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
        Schema::create('rooms', function (Blueprint $table) {
            $table->id();
            $table->foreignId('building_id')->constrained()->onDelete('cascade');
            $table->foreignId('room_type_id')->constrained()->onDelete('restrict');
            $table->string('room_number'); // Số phòng
            $table->integer('floor'); // Tầng
            $table->integer('capacity'); // Số chỗ tối đa
            $table->integer('current_occupancy')->default(0); // Số người đang ở
            $table->decimal('area', 8, 2)->nullable(); // Diện tích m²
            $table->enum('status', ['available', 'occupied', 'maintenance', 'reserved'])->default('available');
            $table->text('description')->nullable();
            $table->json('images')->nullable(); // Hình ảnh phòng
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            
            $table->index(['building_id', 'status', 'is_active']);
            $table->unique(['building_id', 'room_number']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rooms');
    }
};
