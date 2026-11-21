<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('students', function (Blueprint $table) {

            $table->id();

            // Admission

            $table->string('admission_no')->unique();

            // Personal Details

            $table->string('first_name');

            $table->string('middle_name')->nullable();

            $table->string('last_name');

            $table->enum('gender',[
                'Male',
                'Female'
            ]);

            $table->date('dob');

            $table->string('birth_certificate')->nullable();

            // Contacts

            $table->string('phone')->nullable();

            $table->string('email')->nullable();

            // Academic Placement

            $table->foreignId('school_class_id')
                  ->constrained()
                  ->cascadeOnDelete();

            $table->foreignId('stream_id')
                  ->nullable()
                  ->constrained()
                  ->nullOnDelete();

            $table->foreignId('academic_year_id')
                  ->constrained()
                  ->cascadeOnDelete();

            $table->foreignId('term_id')
                  ->constrained()
                  ->cascadeOnDelete();

            // Parent

            $table->string('parent_name');

            $table->string('parent_phone');

            $table->string('parent_email')->nullable();

            $table->text('address')->nullable();

            // Others

            $table->string('photo')->nullable();

            $table->enum('status',[
                'Active',
                'Transferred',
                'Graduated',
                'Inactive'
            ])->default('Active');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
