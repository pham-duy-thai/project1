<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('rooms', function (Blueprint $table) {
            $table->id();

            // 🔹 Mỗi phòng thuộc về 1 tòa (building)
            $table->unsignedBigInteger('building_id')->nullable();
            $table->foreign('building_id')
                ->references('id')
                ->on('buildings')
                ->onDelete('cascade');

            // 🔹 Số tầng (1, 2, 3, ...)
            $table->integer('floor_number');

            // 🔹 Thông tin phòng
            $table->string('room_number')->unique();
            $table->integer('capacity')->default(1);
            $table->enum('gender', ['nam', 'nu'])->default('nam');
            $table->integer('current_occupancy')->default(0);
            $table->decimal('price', 10, 2)->default(0);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rooms');
    }
};
