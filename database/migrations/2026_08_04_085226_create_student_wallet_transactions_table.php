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
        Schema::create('student_wallet_transactions', function (Blueprint $table) {

    $table->id();

    $table->foreignId('student_wallet_id')->constrained()->cascadeOnDelete();

    $table->decimal('amount',12,2);

    $table->enum('type',[
        'Credit',
        'Debit'
    ]);

    $table->string('description');

    $table->foreignId('fee_payment_id')
          ->nullable()
          ->constrained()
          ->nullOnDelete();

    $table->foreignId('created_by')
          ->nullable()
          ->constrained('users')
          ->nullOnDelete();

    $table->timestamps();

});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_wallet_transactions');
    }
};
