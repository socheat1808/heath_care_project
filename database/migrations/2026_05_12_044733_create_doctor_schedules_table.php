<?php
// ============================================================
// MIGRATION
// php artisan make:migration create_doctor_schedules_table
// ============================================================

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;



return new class extends Migration
{
    public function up(): void
    {
        Schema::create('doctor_schedules', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('doctor_id');
            $table->enum('day', ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday']);
            $table->boolean('is_active')->default(false);
            $table->time('start_time')->nullable();
            $table->time('end_time')->nullable();
            $table->unsignedTinyInteger('max_appointments')->default(10);
            $table->timestamps();

            $table->unique(['doctor_id', 'day']);   // one row per doctor per day
            $table->foreign('doctor_id')
                ->references('DoctorID')
                ->on('doctors')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('doctor_schedules');
    }
};
