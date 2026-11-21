<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FeePayment extends Model
{
    protected $fillable = [
        'student_fee_id',
        'student_id',
        'academic_year_id',
        'term_id',

        // Payment allocation
        'amount_paid',
        'amount_received',
        'amount_allocated',
        'wallet_amount_applied',
        'overpayment_amount',

        // Payment details
        'payment_date',
        'payment_method',
        'reference_no',
        'receipt_no',
        'remarks',

        // Receiving user
        'received_by',

        // Reversal
        'is_reversed',
        'reversed_at',
        'reversed_by',
        'reversal_reason',
    ];

    protected $casts = [
        'payment_date'          => 'date',

        'amount_paid'           => 'decimal:2',
        'amount_received'       => 'decimal:2',
        'amount_allocated'      => 'decimal:2',
        'wallet_amount_applied' => 'decimal:2',
        'overpayment_amount'    => 'decimal:2',

        'is_reversed'           => 'boolean',

        'reversed_at'           => 'datetime',

        'created_at'            => 'datetime',
        'updated_at'            => 'datetime',
    ];

    /**
     * Student fee this payment belongs to.
     */
    public function studentFee(): BelongsTo
    {
        return $this->belongsTo(StudentFee::class);
    }

    /**
     * Student who made the payment.
     */
    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    /**
     * Academic year.
     */
    public function academicYear(): BelongsTo
    {
        return $this->belongsTo(AcademicYear::class);
    }

    /**
     * Term.
     */
    public function term(): BelongsTo
    {
        return $this->belongsTo(Term::class);
    }

    /**
     * User who received the payment.
     */
    public function receiver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'received_by');
    }

    /**
     * User who reversed the payment.
     */
    public function reverser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reversed_by');
    }
}