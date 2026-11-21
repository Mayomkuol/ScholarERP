<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StudentPromotion extends Model
{
    protected $fillable = [
        'student_id',

        'from_academic_year_id',
        'to_academic_year_id',

        'from_class_id',
        'to_class_id',

        'from_stream_id',
        'to_stream_id',

        'promotion_date',

        'remarks',
    ];

    protected $casts = [
        'promotion_date' => 'date',
    ];

    /**
     * Student being promoted.
     */
    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    /**
     * Previous class.
     */
    public function fromClass(): BelongsTo
    {
        return $this->belongsTo(
            SchoolClass::class,
            'from_class_id'
        );
    }

    /**
     * New class.
     */
    public function toClass(): BelongsTo
    {
        return $this->belongsTo(
            SchoolClass::class,
            'to_class_id'
        );
    }

    /**
     * Previous academic year.
     */
    public function fromYear(): BelongsTo
    {
        return $this->belongsTo(
            AcademicYear::class,
            'from_academic_year_id'
        );
    }

    /**
     * New academic year.
     */
    public function toYear(): BelongsTo
    {
        return $this->belongsTo(
            AcademicYear::class,
            'to_academic_year_id'
        );
    }

    /**
     * Previous stream.
     */
    public function fromStream(): BelongsTo
    {
        return $this->belongsTo(
            Stream::class,
            'from_stream_id'
        );
    }

    /**
     * New stream.
     */
    public function toStream(): BelongsTo
    {
        return $this->belongsTo(
            Stream::class,
            'to_stream_id'
        );
    }
}