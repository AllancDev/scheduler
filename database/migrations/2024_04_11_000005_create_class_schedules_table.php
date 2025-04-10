<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('class_schedules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('class_id')->constrained()->onDelete('cascade');
            $table->foreignId('teacher_id')->constrained()->onDelete('cascade');
            $table->foreignId('subject_id')->constrained()->onDelete('cascade');
            $table->string('room');
            $table->enum('day_of_week', ['monday', 'tuesday', 'wednesday', 'thursday', 'friday']);
            $table->time('start_time');
            $table->time('end_time');
            $table->boolean('is_substitute')->default(false);
            $table->foreignId('original_teacher_id')->nullable()->constrained('teachers')->onDelete('set null');
            $table->timestamps();

            // Add unique constraints to prevent conflicts
            $table->unique(['teacher_id', 'day_of_week', 'start_time', 'end_time'], 'teacher_time_conflict');
            $table->unique(['class_id', 'day_of_week', 'start_time', 'end_time'], 'class_time_conflict');
            $table->unique(['room', 'day_of_week', 'start_time', 'end_time'], 'room_time_conflict');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('class_schedules');
    }
}; 