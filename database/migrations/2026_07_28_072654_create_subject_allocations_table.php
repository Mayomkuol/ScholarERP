<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('subject_allocations', function (Blueprint $table) {

            $table->id();

            /*
            |--------------------------------------------------------------------------
            | CLASS
            |--------------------------------------------------------------------------
            */

            $table->foreignId('school_class_id')
                ->constrained('school_classes')
                ->cascadeOnDelete();

            /*
            |--------------------------------------------------------------------------
            | STREAM
            |--------------------------------------------------------------------------
            */

            $table->foreignId('stream_id')
                ->constrained('streams')
                ->cascadeOnDelete();

            /*
            |--------------------------------------------------------------------------
            | SUBJECT
            |--------------------------------------------------------------------------
            */

            $table->foreignId('subject_id')
                ->constrained('subjects')
                ->cascadeOnDelete();

            /*
            |--------------------------------------------------------------------------
            | PREVENT DUPLICATE ALLOCATION
            |--------------------------------------------------------------------------
            |
            | The same subject cannot be allocated twice to the
            | same class and stream.
            |
            */

            $table->unique([
                'school_class_id',
                'stream_id',
                'subject_id',
            ]);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('subject_allocations');
    }
};