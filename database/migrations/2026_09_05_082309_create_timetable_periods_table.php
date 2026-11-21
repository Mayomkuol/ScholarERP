<?php

use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * The timetable_periods table is already created
     * with its complete structure by the July migration.
     */
    public function up(): void
    {
        // No action required.
    }

    /**
     * Do not drop the table here because it belongs
     * to the July migration.
     */
    public function down(): void
    {
        // No action required.
    }
};
