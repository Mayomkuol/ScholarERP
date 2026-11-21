<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('subjects', function (Blueprint $table) {

            $table->id();

            /*
            |--------------------------------------------------------------------------
            | SUBJECT INFORMATION
            |--------------------------------------------------------------------------
            */

            $table->string('name');

            $table->string('code')->unique();

            /*
            |--------------------------------------------------------------------------
            | EDUCATION LEVEL
            |--------------------------------------------------------------------------
            |
            | Determines whether the subject belongs to Primary,
            | Secondary or College.
            |
            */

            $table->enum('level', [
                'Primary',
                'Secondary',
                'College',
            ]);

            /*
            |--------------------------------------------------------------------------
            | SUBJECT CATEGORY
            |--------------------------------------------------------------------------
            */

            $table->enum('category', [
                'Compulsory',
                'Optional',
            ])->default('Compulsory');

            /*
            |--------------------------------------------------------------------------
            | STATUS
            |--------------------------------------------------------------------------
            */

            $table->boolean('active')->default(true);

            $table->timestamps();

            /*
            |--------------------------------------------------------------------------
            | PREVENT DUPLICATE SUBJECTS WITHIN A LEVEL
            |--------------------------------------------------------------------------
            |
            | Mathematics can exist for Primary and Secondary,
            | but not twice within the same level.
            |
            */

            $table->unique(
                ['name', 'level'],
                'subjects_name_level_unique'
            );
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('subjects');
    }
};