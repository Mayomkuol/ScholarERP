<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class StudentFee extends Model
{
    protected $fillable = [
        'student_id',
        'academic_year_id',
        'term_id',
        'total_fee',
        'discount',
        'net_fee',
        'paid_amount',
        'status',
    ];

    protected $casts = [
        'total_fee'   => 'decimal:2',
        'discount'    => 'decimal:2',
        'net_fee'     => 'decimal:2',
        'paid_amount' => 'decimal:2',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function academicYear(): BelongsTo
    {
        return $this->belongsTo(AcademicYear::class);
    }

    public function term(): BelongsTo
    {
        return $this->belongsTo(Term::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(FeePayment::class);
    }

    /*
    |--------------------------------------------------------------------------
    | Computed Attributes
    |--------------------------------------------------------------------------
    */

    /**
     * Outstanding balance.
     */
    public function getBalanceAttribute(): float
    {
        return max(0, (float) ($this->net_fee - $this->paid_amount));
    }

    /**
     * True if the fee has been fully paid.
     */
    public function getIsPaidAttribute(): bool
    {
        return $this->balance <= 0;
    }

    /**
     * Percentage paid.
     */
    public function getPercentagePaidAttribute(): float
    {
        if ($this->net_fee <= 0) {
            return 0;
        }

        return round(($this->paid_amount / $this->net_fee) * 100, 2);
    }
}