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
        Schema::table('contracts', function (Blueprint $table) {
            $table->boolean('signed_by_student')->default(false)->after('signed_by');
            $table->boolean('signed_by_manager')->default(false)->after('signed_by_student');
            $table->timestamp('student_signed_at')->nullable()->after('signed_by_manager');
            $table->timestamp('manager_signed_at')->nullable()->after('student_signed_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('contracts', function (Blueprint $table) {
            $table->dropColumn(['signed_by_student', 'signed_by_manager', 'student_signed_at', 'manager_signed_at']);
        });
    }
};
