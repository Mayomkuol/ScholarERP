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
        Schema::create('class_teacher_assignments', function (Blueprint $table) {

            $table->id();

            /*
            |--------------------------------------------------------------------------
            | Teacher
            |--------------------------------------------------------------------------
            */
            $table->foreignId('teacher_id')
                ->constrained('teachers')
                ->cascadeOnDelete();

            /*
            |--------------------------------------------------------------------------
            | School Class
            |--------------------------------------------------------------------------
            */
            $table->foreignId('school_class_id')
                ->constrained('school_classes')
                ->cascadeOnDelete();

            /*
            |--------------------------------------------------------------------------
            | Stream
            |--------------------------------------------------------------------------
            |
            | Nullable because some classes do not have streams.
            |
            */
            $table->foreignId('stream_id')
                ->nullable()
                ->constrained('streams')
                ->nullOnDelete();

            /*
            |--------------------------------------------------------------------------
            | Academic Period
            |--------------------------------------------------------------------------
            */
            $table->foreignId('academic_year_id')
                ->constrained('academic_years')
                ->cascadeOnDelete();

            $table->foreignId('term_id')
                ->constrained('terms')
                ->cascadeOnDelete();

            /*
            |--------------------------------------------------------------------------
            | Assignment Status
            |--------------------------------------------------------------------------
            */
            $table->string('status')
                ->default('Active');

            $table->date('assigned_at')
                ->nullable();

            /*
            |--------------------------------------------------------------------------
            | Audit
            |--------------------------------------------------------------------------
            */
            $table->foreignId('assigned_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamps();

            /*
            |--------------------------------------------------------------------------
            | Prevent Duplicate Assignments
            |--------------------------------------------------------------------------
            |
            | One class/stream can have only one class teacher
            | for a particular academic year and term.
            |
            */
            $table->unique([
                'school_class_id',
                'stream_id',
                'academic_year_id',
                'term_id',
            ], 'class_teacher_unique_assignment');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('class_teacher_assignments');
    }
};