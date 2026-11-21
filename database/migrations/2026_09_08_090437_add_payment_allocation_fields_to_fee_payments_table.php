<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('fee_payments', function (Blueprint $table) {

            $table->decimal('amount_received', 12, 2)
                ->after('amount_paid')
                ->default(0);

            $table->decimal('amount_allocated', 12, 2)
                ->after('amount_received')
                ->default(0);

            $table->decimal('overpayment_amount', 12, 2)
                ->after('amount_allocated')
                ->default(0);
        });
    }

    public function down(): void
    {
        Schema::table('fee_payments', function (Blueprint $table) {

            $table->dropColumn([
                'amount_received',
                'amount_allocated',
                'overpayment_amount',
            ]);

        });
    }
};