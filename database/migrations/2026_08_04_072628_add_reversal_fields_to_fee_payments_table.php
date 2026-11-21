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
    Schema::table('fee_payments', function (Blueprint $table) {

        $table->boolean('is_reversed')
              ->default(false)
              ->after('remarks');

        $table->timestamp('reversed_at')
              ->nullable();

        $table->foreignId('reversed_by')
              ->nullable()
              ->constrained('users')
              ->nullOnDelete();

        $table->text('reversal_reason')
              ->nullable();

    });
}
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('fee_payments', function (Blueprint $table) {
            //
        });
    }
};
