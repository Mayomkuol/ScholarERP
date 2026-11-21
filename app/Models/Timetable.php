<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Timetable extends Model
{
    protected $fillable = [
        'timetable_block_id',
        'teacher_id',
        'subject_id',
        'school_class_id',
        'stream_id',
        'academic_year_id',
        'term_id',
        'period_id',
        'day',
        'lesson_no',
        'start_time',
        'end_time',
        'room',
        'lesson_type',
        'status',
        'notes',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function timetableBlock(): BelongsTo
    {
        return $this->belongsTo(
            TimetableBlock::class,
            'timetable_block_id'
        );
    }

    public function teacher(): BelongsTo
    {
        return $this->belongsTo(
            Teacher::class,
            'teacher_id'
        );
    }

    public function subject(): BelongsTo
    {
        return $this->belongsTo(
            Subject::class,
            'subject_id'
        );
    }

    public function schoolClass(): BelongsTo
    {
        return $this->belongsTo(
            SchoolClass::class,
            'school_class_id'
        );
    }

    public function stream(): BelongsTo
    {
        return $this->belongsTo(
            Stream::class,
            'stream_id'
        );
    }

    public function academicYear(): BelongsTo
    {
        return $this->belongsTo(
            AcademicYear::class,
            'academic_year_id'
        );
    }

    public function term(): BelongsTo
    {
        return $this->belongsTo(
            Term::class,
            'term_id'
        );
    }

    public function period(): BelongsTo
    {
        return $this->belongsTo(
            TimetablePeriod::class,
            'period_id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Status Helpers
    |--------------------------------------------------------------------------
    */

    public function isPublished(): bool
    {
        return $this->status === 'published';
    }

    public function isDraft(): bool
    {
        return $this->status === 'draft';
    }

    public function isArchived(): bool
    {
        return $this->status === 'archived';
    }
}