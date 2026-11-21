<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('timetables', function (Blueprint $table) {

            $table->foreignId('timetable_block_id')
                ->nullable()
                ->after('id')
                ->constrained('timetable_blocks')
                ->nullOnDelete();

            $table->index(
                'timetable_block_id',
                'tt_block_id_idx'
            );
        });
    }

    public function down(): void
    {
        Schema::table('timetables', function (Blueprint $table) {

            $table->dropForeign([
                'timetable_block_id'
            ]);

            $table->dropIndex(
                'tt_block_id_idx'
            );

            $table->dropColumn(
                'timetable_block_id'
            );
        });
    }
};