<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Add timetable fields and indexes.
     */
    public function up(): void
    {
        Schema::table('timetables', function (Blueprint $table) {
            // Link each timetable entry to its timetable period.
            $table->foreignId('period_id')
                ->nullable()
                ->constrained('timetable_periods')
                ->nullOnDelete();

            // Type of lesson.
            $table->string('lesson_type', 30)
                ->default('regular');

            // Publication status.
            $table->enum('status', [
                'draft',
                'published',
                'archived',
            ])->default('draft');

            // Optional lesson notes.
            $table->text('notes')->nullable();

            // Class timetable lookup index.
            $table->index([
                'academic_year_id',
                'term_id',
                'school_class_id',
                'day',
                'lesson_no',
            ], 'tt_class_period_idx');

            // Teacher timetable lookup index.
            $table->index([
                'teacher_id',
                'day',
                'lesson_no',
            ], 'tt_teacher_period_idx');

            // Status lookup index.
            $table->index(
                ['status'],
                'tt_status_idx'
            );
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('timetables', function (Blueprint $table) {
            $table->dropIndex('tt_class_period_idx');
            $table->dropIndex('tt_teacher_period_idx');
            $table->dropIndex('tt_status_idx');

            $table->dropForeign(['period_id']);

            $table->dropColumn([
                'period_id',
                'lesson_type',
                'status',
                'notes',
            ]);
        });
    }
};
