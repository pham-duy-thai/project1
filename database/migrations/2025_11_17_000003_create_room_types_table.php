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
        Schema::create('room_types', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // VD: Phòng 2 người, Phòng 4 người, Phòng 6 người
            $table->string('code')->unique();
            $table->integer('capacity'); // Số người tối đa
            $table->decimal('price_per_month', 10, 2); // Giá thuê/tháng
            $table->decimal('price_per_semester', 10, 2)->nullable(); // Giá thuê/học kỳ
            $table->decimal('price_per_year', 10, 2)->nullable(); // Giá thuê/năm
            $table->text('description')->nullable();
            $table->json('amenities')->nullable(); // Tiện nghi: điều hòa, nóng lạnh, tủ lạnh...
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('room_types');
    }
};
