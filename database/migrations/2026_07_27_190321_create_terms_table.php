<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('terms', function (Blueprint $table) {

            $table->id();

            // Academic Year
            $table->foreignId('academic_year_id')
                  ->constrained()
                  ->cascadeOnDelete();

            // Term Information
            $table->string('name');             // Term 1, Term 2, Term 3
            $table->string('code')->nullable(); // T1, T2, T3

            // Dates
            $table->date('start_date');
            $table->date('end_date');

            // Status
            $table->boolean('is_current')->default(false);

            $table->enum('status', [
                'Open',
                'Closed'
            ])->default('Open');

            $table->timestamps();

            // Prevent duplicate terms in the same academic year
            $table->unique(['academic_year_id', 'name']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('terms');
    }
};