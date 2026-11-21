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
        Schema::create('timetable_periods', function (Blueprint $table) {

            $table->id();

            /*
            |--------------------------------------------------------------------------
            | Period Information
            |--------------------------------------------------------------------------
            */

            $table->string('name');

            $table->unsignedTinyInteger('period_number')->nullable();

            $table->time('start_time');

            $table->time('end_time');

            /*
            |--------------------------------------------------------------------------
            | Period Type
            |--------------------------------------------------------------------------
            |
            | lesson     = Normal teaching period
            | break      = Short break
            | lunch      = Lunch break
            | assembly   = Assembly
            | activity   = School activity
            |
            */

            $table->enum('type', [
                'lesson',
                'break',
                'lunch',
                'assembly',
                'activity'
            ])->default('lesson');

            $table->boolean('is_active')->default(true);

            $table->timestamps();

            /*
            |--------------------------------------------------------------------------
            | Indexes
            |--------------------------------------------------------------------------
            */

            $table->index('period_number');
            $table->index('type');
            $table->index('is_active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('timetable_periods');
    }
};