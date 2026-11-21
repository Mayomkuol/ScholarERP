
<?php

use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        // The level and active columns are already
        // created in the original subjects migration.
    }

    public function down(): void
    {
        // Do not drop columns belonging to the original migration.
    }
};
