<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('fee_structures', function (Blueprint $table) {

            $table->id();

            $table->foreignId('academic_year_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('term_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('school_class_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('fee_category_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->decimal('amount',12,2);

            $table->timestamps();

            $table->unique(
    [
        'academic_year_id',
        'term_id',
        'school_class_id',
        'fee_category_id'
    ],
    'fs_unique'
);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fee_structures');
    }
};