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
        Schema::create('amenities', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // VD: Điều hòa, Nóng lạnh, Tủ lạnh, Giường, Tủ quần áo
            $table->string('code')->unique();
            $table->text('description')->nullable();
            $table->string('icon')->nullable(); // Icon class hoặc path
            $table->boolean('is_common')->default(true); // Tiện ích phổ biến
            $table->timestamps();
        });

        // Bảng trung gian cho room_amenities
        Schema::create('room_amenities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('room_id')->constrained()->onDelete('cascade');
            $table->foreignId('amenity_id')->constrained()->onDelete('cascade');
            $table->integer('quantity')->default(1); // Số lượng
            $table->enum('condition', ['new', 'good', 'fair', 'poor', 'broken'])->default('good');
            $table->text('notes')->nullable();
            $table->timestamps();
            
            $table->unique(['room_id', 'amenity_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('room_amenities');
        Schema::dropIfExists('amenities');
    }
};
