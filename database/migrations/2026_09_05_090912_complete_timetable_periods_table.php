
<?php

use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * The July migration already creates all required
     * timetable_periods columns and indexes.
     */
    public function up(): void
    {
        // No action required.
    }

    /**
     * Preserve the table structure owned by the July migration.
     */
    public function down(): void
    {
        // No action required.
    }
};
