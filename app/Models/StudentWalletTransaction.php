<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StudentWalletTransaction extends Model
{
    protected $fillable = [
        'student_wallet_id',
        'student_fee_id',
        'amount',
        'type',
        'description',
        'fee_payment_id',
        'created_by',
    ];

    public function wallet(): BelongsTo
    {
        return $this->belongsTo(
            StudentWallet::class,
            'student_wallet_id'
        );
    }

    public function studentFee(): BelongsTo
    {
        return $this->belongsTo(
            StudentFee::class,
            'student_fee_id'
        );
    }

    public function feePayment(): BelongsTo
    {
        return $this->belongsTo(
            FeePayment::class,
            'fee_payment_id'
        );
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'created_by'
        );
    }
}