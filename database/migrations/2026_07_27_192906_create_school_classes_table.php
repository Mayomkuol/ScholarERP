<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('school_classes', function (Blueprint $table) {

            $table->id();

            // Class name displayed throughout the system
            // Examples: Grade 1, Grade 2, Class 7, Form 1
            $table->string('name');

            // Unique class code
            // Examples: G1, G2, C7, F1
            $table->string('code')->unique();

            // Education level
            $table->enum('level', [
                'Primary',
                'Secondary',
                'College',
            ]);

            // Maximum number of students
            $table->unsignedInteger('capacity')->default(40);

            // Whether the class is currently available
            $table->boolean('active')->default(true);

            $table->timestamps();

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('school_classes');
    }
};