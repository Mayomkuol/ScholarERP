<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('students', function (Blueprint $table) {

            $table->date('admission_date')->nullable()->after('admission_no');

            $table->string('religion')->nullable()->after('birth_certificate');

            $table->string('nationality')->nullable()->after('religion');

            $table->string('blood_group')->nullable()->after('nationality');

            $table->string('guardian_name')->nullable()->after('mother_phone');

            $table->string('guardian_phone')->nullable()->after('guardian_name');

            $table->string('guardian_relationship')->nullable()->after('guardian_phone');

            $table->string('previous_school')->nullable()->after('allergies');

        });
    }

    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {

            $table->dropColumn([
                'admission_date',
                'religion',
                'nationality',
                'blood_group',
                'guardian_name',
                'guardian_phone',
                'guardian_relationship',
                'previous_school',
            ]);

        });
    }
};