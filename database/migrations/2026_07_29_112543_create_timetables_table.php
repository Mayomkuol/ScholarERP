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
    Schema::create('timetables', function (Blueprint $table) {

        $table->id();

        $table->foreignId('academic_year_id')
              ->constrained()
              ->cascadeOnDelete();

        $table->foreignId('term_id')
              ->constrained()
              ->cascadeOnDelete();

        $table->foreignId('school_class_id')
              ->constrained('school_classes')
              ->cascadeOnDelete();

        $table->foreignId('stream_id')
              ->nullable()
              ->constrained()
              ->nullOnDelete();

        $table->foreignId('subject_id')
              ->constrained()
              ->cascadeOnDelete();

        $table->foreignId('teacher_id')
              ->constrained()
              ->cascadeOnDelete();

        $table->enum('day', [
            'Monday',
            'Tuesday',
            'Wednesday',
            'Thursday',
            'Friday',
            'Saturday'
        ]);

        $table->unsignedTinyInteger('lesson_no');

        $table->time('start_time');

        $table->time('end_time');

        $table->string('room')->nullable();

        $table->timestamps();

    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('timetables');
    }
};
