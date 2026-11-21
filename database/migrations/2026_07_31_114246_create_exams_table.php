<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('exams', function (Blueprint $table) {

            $table->id();

            $table->foreignId('exam_type_id')
                  ->constrained()
                  ->cascadeOnDelete();

            $table->foreignId('academic_year_id')
                  ->constrained()
                  ->cascadeOnDelete();

            $table->foreignId('term_id')
                  ->constrained()
                  ->cascadeOnDelete();

            $table->foreignId('school_class_id')
                  ->constrained('school_classes')
                  ->cascadeOnDelete();

            $table->string('name');

            $table->date('start_date');

            $table->date('end_date');

            $table->enum('status',[
                'Draft',
                'Open',
                'Closed',
                'Published'
            ])->default('Draft');

            $table->timestamps();

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('exams');
    }
};