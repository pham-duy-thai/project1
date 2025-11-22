<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;   // <-- thêm dòng này

return new class extends Migration {
    public function up(): void
    {
        Schema::create('room_registrations', function (Blueprint $table) {
            $table->id();

            $table->foreignId('student_id')
                ->constrained('students')
                ->onDelete('cascade');

            $table->foreignId('room_id')
                ->constrained('rooms')
                ->onDelete('cascade');

            $table->date('registration_date')->default(DB::raw('CURRENT_DATE'));

            $table->date('end_date')->nullable();

            $table->enum('status', [
                'pending',
                'approved',
                'rejected',
                'completed'
            ])->default('pending');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('room_registrations');
    }
};
