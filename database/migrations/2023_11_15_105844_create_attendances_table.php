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
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('emp_code')->constrained(
                table: 'employees', indexName: 'attendance_emp_code'
            )->onDelete('cascade');
            $table->dateTime('checkin_time')->nullable();
            $table->dateTime('checkout_time')->nullable();
            $table->integer('mode')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendance');
    }
};
