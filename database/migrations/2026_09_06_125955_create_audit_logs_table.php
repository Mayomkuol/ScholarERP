<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('audit_logs', function (Blueprint $table) {
            $table->id();

            // User who performed the action
            $table->foreignId('user_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            // Action information
            $table->string('action', 100);
            $table->string('module', 100)->nullable();

            // What was affected
            $table->nullableMorphs('auditable');

            // Human-readable description
            $table->text('description')->nullable();

            // Previous and new values
            $table->json('old_values')->nullable();
            $table->json('new_values')->nullable();

            // Request information
            $table->ipAddress('ip_address')->nullable();
            $table->text('user_agent')->nullable();

            $table->timestamps();

            $table->index(['action']);
            $table->index(['module']);
            $table->index(['user_id']);
            $table->index(['created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('audit_logs');
    }
};