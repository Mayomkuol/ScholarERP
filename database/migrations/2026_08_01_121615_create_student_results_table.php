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
    Schema::create('student_results', function (Blueprint $table) {

        $table->id();

        $table->foreignId('exam_id')->constrained()->cascadeOnDelete();

        $table->foreignId('student_id')->constrained()->cascadeOnDelete();

        $table->decimal('total_marks',8,2);

        $table->decimal('mean_score',8,2);

        $table->string('mean_grade');

        $table->decimal('points',8,2);

        $table->integer('position')->nullable();

        $table->timestamps();

        $table->unique([
            'exam_id',
            'student_id'
        ]);

    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_results');
    }
};
