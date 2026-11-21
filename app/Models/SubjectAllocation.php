<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubjectAllocation extends Model
{
    use HasFactory;

    protected $fillable = [
        'school_class_id',
        'stream_id',
        'subject_id',
    ];

    /**
     * Class this allocation belongs to.
     */
    public function schoolClass()
    {
        return $this->belongsTo(
            SchoolClass::class,
            'school_class_id'
        );
    }

    /**
     * Stream this allocation belongs to.
     *
     * stream_id may be NULL if the subject is allocated
     * to the whole class rather than a particular stream.
     */
    public function stream()
    {
        return $this->belongsTo(
            Stream::class,
            'stream_id'
        );
    }

    /**
     * Subject being allocated.
     */
    public function subject()
    {
        return $this->belongsTo(
            Subject::class,
            'subject_id'
        );
    }

    /**
     * Allocations for a specific class.
     */
    public function scopeForClass($query, $classId)
    {
        return $query->where(
            'school_class_id',
            $classId
        );
    }

    /**
     * Allocations for a specific stream.
     */
    public function scopeForStream($query, $streamId)
    {
        return $query->where(
            'stream_id',
            $streamId
        );
    }

    /**
     * Allocations for a specific subject.
     */
    public function scopeForSubject($query, $subjectId)
    {
        return $query->where(
            'subject_id',
            $subjectId
        );
    }
}
