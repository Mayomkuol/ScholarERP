<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
{
    Schema::table('students', function (Blueprint $table) {

        $table->string('father_name')->nullable();

        $table->string('father_phone')->nullable();

        $table->string('mother_name')->nullable();

        $table->string('mother_phone')->nullable();

        $table->text('medical_conditions')->nullable();

        $table->text('allergies')->nullable();

    });
}
    public function down(): void
{
    Schema::table('students', function (Blueprint $table) {

        $table->dropColumn([
            'father_name',
            'father_phone',
            'mother_name',
            'mother_phone',
            'medical_conditions',
            'allergies',
        ]);

    });
}
};
