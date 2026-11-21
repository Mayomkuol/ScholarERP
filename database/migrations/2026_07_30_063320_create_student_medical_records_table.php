<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('student_medical_records', function (Blueprint $table) {

            $table->id();

            $table->foreignId('student_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->string('blood_group')->nullable();

            $table->text('medical_conditions')->nullable();

            $table->text('allergies')->nullable();

            $table->string('doctor_name')->nullable();

            $table->string('doctor_phone')->nullable();

            $table->string('insurance_provider')->nullable();

            $table->string('insurance_number')->nullable();

            $table->text('notes')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('student_medical_records');
    }
};