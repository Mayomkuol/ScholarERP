<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TimetableBlock extends Model
{
    protected $fillable = [
        'academic_year_id',
        'term_id',
        'school_class_id',
        'stream_id',
        'name',
        'status',
        'effective_from',
        'effective_to',
        'notes',
        'created_by',
        'published_at',
    ];

    protected $casts = [
        'effective_from' => 'date',
        'effective_to' => 'date',
        'published_at' => 'datetime',
    ];

    public function academicYear(): BelongsTo
    {
        return $this->belongsTo(AcademicYear::class);
    }

    public function term(): BelongsTo
    {
        return $this->belongsTo(Term::class);
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
        return $this->belongsTo(Stream::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'created_by'
        );
    }

    public function timetables(): HasMany
    {
        return $this->hasMany(
            Timetable::class,
            'timetable_block_id'
        );
    }

    public function isDraft(): bool
    {
        return $this->status === 'draft';
    }

    public function isPublished(): bool
    {
        return $this->status === 'published';
    }

    public function isArchived(): bool
    {
        return $this->status === 'archived';
    }
}