<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('timetable_blocks', function (Blueprint $table) {

            $table->id();

            $table->foreignId('academic_year_id')
                ->constrained('academic_years')
                ->cascadeOnDelete();

            $table->foreignId('term_id')
                ->constrained('terms')
                ->cascadeOnDelete();

            $table->foreignId('school_class_id')
                ->constrained('school_classes')
                ->cascadeOnDelete();

            $table->foreignId('stream_id')
                ->nullable()
                ->constrained('streams')
                ->nullOnDelete();

            $table->string('name', 150);

            $table->enum('status', [
                'draft',
                'published',
                'archived',
            ])->default('draft');

            $table->date('effective_from')
                ->nullable();

            $table->date('effective_to')
                ->nullable();

            $table->text('notes')
                ->nullable();

            $table->foreignId('created_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamp('published_at')
                ->nullable();

            $table->timestamps();

            /*
            |--------------------------------------------------------------------------
            | Indexes
            |--------------------------------------------------------------------------
            */

            $table->index([
                'academic_year_id',
                'term_id',
            ], 'tt_blocks_year_term_idx');

            $table->index([
                'school_class_id',
                'stream_id',
            ], 'tt_blocks_class_stream_idx');

            $table->index(
                ['status'],
                'tt_blocks_status_idx'
            );
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('timetable_blocks');
    }
};