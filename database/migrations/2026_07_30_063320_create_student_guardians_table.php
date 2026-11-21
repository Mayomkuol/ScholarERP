<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('student_guardians', function (Blueprint $table) {

            $table->id();

            $table->foreignId('student_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->enum('guardian_type', [
                'Father',
                'Mother',
                'Guardian'
            ]);

            $table->string('name');

            $table->string('phone')->nullable();

            $table->string('email')->nullable();

            $table->string('occupation')->nullable();

            $table->string('relationship')->nullable();

            $table->text('address')->nullable();

            $table->boolean('is_primary')->default(false);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('student_guardians');
    }
};