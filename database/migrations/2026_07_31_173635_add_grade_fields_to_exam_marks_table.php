<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('exam_marks', function (Blueprint $table) {

            $table->string('grade')->nullable()->after('marks');

            $table->decimal('points',4,2)->nullable()->after('grade');

            $table->string('remarks')->nullable()->change();

            $table->boolean('absent')->default(false)->after('remarks');

        });
    }

    public function down(): void
    {
        Schema::table('exam_marks', function (Blueprint $table) {

            $table->dropColumn([
                'grade',
                'points',
                'absent'
            ]);

        });
    }
};
