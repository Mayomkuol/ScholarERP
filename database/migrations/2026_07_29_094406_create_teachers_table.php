<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('teachers', function (Blueprint $table) {

            $table->id();

            $table->string('employee_no')->unique();

            $table->string('first_name');
            $table->string('middle_name')->nullable();
            $table->string('last_name');

            $table->enum('gender', [
                'Male',
                'Female'
            ]);

            $table->date('date_of_birth')->nullable();

            $table->string('national_id')->nullable();

            $table->string('phone')->nullable();

            $table->string('email')->nullable()->unique();

            $table->text('address')->nullable();

            $table->string('photo')->nullable();

            $table->foreignId('department_id')
                  ->nullable()
                  ->constrained()
                  ->nullOnDelete();

            $table->string('designation');

            $table->enum('employment_type',[
                'Permanent',
                'Contract',
                'Part Time'
            ]);

            $table->date('joining_date');

            $table->string('qualification')->nullable();

            $table->string('specialization')->nullable();

            $table->decimal('salary',12,2)->default(0);

            $table->boolean('status')->default(true);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('teachers');
    }
};