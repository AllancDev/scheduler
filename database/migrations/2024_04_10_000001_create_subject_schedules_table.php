<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('subject_schedules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('subject_id')->constrained()->onDelete('cascade');
            $table->foreignId('teacher_id')->constrained()->onDelete('cascade');
            $table->string('room');
            $table->enum('day_of_week', ['segunda', 'terca', 'quarta', 'quinta', 'sexta', 'sabado', 'domingo']);
            $table->time('start_time');
            $table->time('end_time');
            $table->boolean('is_substitute')->default(false);
            $table->foreignId('original_teacher_id')->nullable()->constrained('teachers')->onDelete('set null');
            $table->timestamps();

            // Add unique constraint to prevent room conflicts
            $table->unique(['room', 'day_of_week', 'start_time', 'end_time'], 'room_schedule_unique');

            // Add unique constraint to prevent teacher conflicts (except for substitutes)
            $table->unique(['teacher_id', 'day_of_week', 'start_time', 'end_time', 'is_substitute'], 'teacher_schedule_unique');
        });
    }

    public function down()
    {
        Schema::dropIfExists('subject_schedules');
    }
}; 