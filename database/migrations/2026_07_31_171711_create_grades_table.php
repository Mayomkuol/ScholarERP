<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('grades', function (Blueprint $table) {

            $table->id();

            $table->string('name',10);

            $table->decimal('minimum_mark',5,2);

            $table->decimal('maximum_mark',5,2);

            $table->decimal('points',5,2)->default(0);

            $table->string('remarks')->nullable();

            $table->boolean('is_pass')->default(true);

            $table->boolean('active')->default(true);

            $table->timestamps();

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('grades');
    }
};