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
        Schema::create('streams', function (Blueprint $table) {

            $table->id();

            /*
            |--------------------------------------------------------------------------
            | SCHOOL CLASS
            |--------------------------------------------------------------------------
            */

            $table->foreignId('school_class_id')
                ->constrained('school_classes')
                ->cascadeOnDelete();

            /*
            |--------------------------------------------------------------------------
            | STREAM INFORMATION
            |--------------------------------------------------------------------------
            */

            $table->string('name');

            $table->string('code')->nullable();

            $table->unsignedInteger('capacity')->default(50);

            $table->boolean('active')->default(true);

            $table->timestamps();

            /*
            |--------------------------------------------------------------------------
            | PREVENT DUPLICATE STREAMS IN SAME CLASS
            |--------------------------------------------------------------------------
            |
            | Example:
            |
            | Class 7 → Blue
            | Class 7 → Green
            |
            | But we should not have two "Blue" streams in Class 7.
            |
            */

            $table->unique([
                'school_class_id',
                'name',
            ]);

            $table->unique([
                'school_class_id',
                'code',
            ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('streams');
    }
};