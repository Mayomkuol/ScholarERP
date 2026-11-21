<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('schools', function (Blueprint $table) {

            $table->id();

            // School Information
            $table->string('name');
            $table->string('code')->nullable();
            $table->string('registration_no')->nullable();
            $table->string('motto')->nullable();
            $table->string('school_type')->nullable();
            $table->string('ownership')->nullable();
            $table->year('established_year')->nullable();

            // Branding
            $table->string('logo')->nullable();
            $table->string('stamp')->nullable();
            $table->string('favicon')->nullable();
            $table->string('login_background')->nullable();

            // Contact
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('alternative_phone')->nullable();
            $table->string('website')->nullable();
            $table->text('postal_address')->nullable();
            $table->text('physical_address')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('country')->default('Kenya');

            // Principal
            $table->string('principal_name')->nullable();
            $table->string('principal_phone')->nullable();
            $table->string('principal_email')->nullable();
            $table->string('principal_signature')->nullable();
            $table->string('principal_photo')->nullable();

            // Academic
            $table->string('timezone')->default('Africa/Nairobi');
            $table->foreignId('academic_year_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();

            $table->foreignId('term_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();

            $table->integer('pass_mark')->default(50);
            $table->time('opening_time')->nullable();
            $table->time('closing_time')->nullable();

            // Financial
            $table->string('currency')->default('KES');
            $table->string('currency_symbol')->default('KSh');
            $table->string('receipt_prefix')->default('RCP');
            $table->string('invoice_prefix')->default('INV');
            $table->decimal('vat_percentage', 5, 2)->default(0);

            // Notifications
            $table->boolean('email_notifications')->default(true);
            $table->boolean('sms_notifications')->default(false);
            $table->boolean('parent_portal')->default(true);
            $table->boolean('student_portal')->default(true);
            $table->boolean('maintenance_mode')->default(false);

            $table->boolean('active')->default(true);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('schools');
    }
};