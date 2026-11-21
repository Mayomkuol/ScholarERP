<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('student_disciplines', function (Blueprint $table) {

            $table->id();

            $table->foreignId('student_id')
                  ->constrained()
                  ->cascadeOnDelete();

            $table->date('incident_date');

            $table->string('category');

            $table->text('incident');

            $table->text('action_taken')->nullable();

            $table->string('reported_by')->nullable();

            $table->enum('status',[
                'Pending',
                'Resolved'
            ])->default('Pending');

            $table->timestamps();

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('student_disciplines');
    }
};