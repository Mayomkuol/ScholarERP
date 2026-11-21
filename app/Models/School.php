<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class School extends Model
{
    protected $fillable = [

        // =====================================================
        // SCHOOL INFORMATION
        // =====================================================

        'name',
        'code',
        'registration_no',
        'motto',
        'school_type',
        'ownership',

        // =====================================================
        // CONTACT INFORMATION
        // =====================================================

        'email',
        'phone',
        'website',
        'address',
        'country',
        'timezone',

        // =====================================================
        // BRANDING
        // =====================================================

        'logo',
        'stamp',
        'favicon',
        'login_background',

        // =====================================================
        // PRINCIPAL INFORMATION
        // =====================================================

        'principal_name',
        'principal_email',
        'principal_phone',

        // =====================================================
        // ACADEMIC SETTINGS
        // =====================================================

        'current_academic_year',
        'current_term',
        'pass_mark',

        // =====================================================
        // FINANCIAL SETTINGS
        // =====================================================

        'currency',
        'currency_symbol',
        'receipt_prefix',
        'invoice_prefix',

        // =====================================================
        // SYSTEM NOTIFICATIONS
        // =====================================================

        'email_notifications',
        'sms_notifications',
        'maintenance_mode',

        // =====================================================
        // STATUS
        // =====================================================

        'active',
    ];

    protected $casts = [

        'pass_mark' => 'decimal:2',

        'active' => 'boolean',

        'email_notifications' => 'boolean',

        'sms_notifications' => 'boolean',

        'maintenance_mode' => 'boolean',
    ];


    // =========================================================
    // RELATIONSHIPS
    // =========================================================

    public function academicYear(): BelongsTo
    {
        return $this->belongsTo(AcademicYear::class);
    }


    public function term(): BelongsTo
    {
        return $this->belongsTo(Term::class);
    }


    public function teacherAllocations(): HasMany
    {
        return $this->hasMany(TeacherAllocation::class);
    }
}