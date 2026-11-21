<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('student_promotions', function (Blueprint $table) {

            $table->id();

            $table->foreignId('student_id')
                  ->constrained()
                  ->cascadeOnDelete();

            $table->foreignId('from_academic_year_id')
                  ->constrained('academic_years');

            $table->foreignId('to_academic_year_id')
                  ->constrained('academic_years');

            $table->foreignId('from_class_id')
                  ->constrained('school_classes');

            $table->foreignId('to_class_id')
                  ->constrained('school_classes');

            $table->foreignId('from_stream_id')
                  ->nullable()
                  ->constrained('streams')
                  ->nullOnDelete();

            $table->foreignId('to_stream_id')
                  ->nullable()
                  ->constrained('streams')
                  ->nullOnDelete();

            $table->date('promotion_date');

            $table->string('remarks')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('student_promotions');
    }
};