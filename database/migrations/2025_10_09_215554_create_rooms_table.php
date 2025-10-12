<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::create('rooms', function (Blueprint $table) {
        $table->id();
        $table->string('name'); // Tên phòng, ví dụ: 206, 101A
        $table->integer('capacity')->default(4); // Sức chứa
        $table->integer('current_students')->default(0); // Số SV hiện tại
        $table->decimal('price', 10, 2)->default(500000); // Giá tiền / tháng
        $table->string('status')->default('available'); // available / full / maintenance
        $table->timestamps();
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
