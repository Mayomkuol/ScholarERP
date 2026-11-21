<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Stream extends Model
{
    use HasFactory;

    protected $fillable = [
        'school_class_id',
        'name',
        'code',
        'capacity',
        'active',
    ];

    protected $casts = [
        'capacity' => 'integer',
        'active'   => 'boolean',
    ];

    /*
    |--------------------------------------------------------------------------
    | SCHOOL CLASS
    |--------------------------------------------------------------------------
    */

    public function schoolClass(): BelongsTo
    {
        return $this->belongsTo(
            SchoolClass::class,
            'school_class_id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | STUDENTS
    |--------------------------------------------------------------------------
    */

    public function students(): HasMany
    {
        return $this->hasMany(
            Student::class,
            'stream_id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | ACTIVE STUDENTS
    |--------------------------------------------------------------------------
    */

    public function activeStudents(): HasMany
    {
        return $this->hasMany(
            Student::class,
            'stream_id'
        )->where('status', 'Active');
    }

    /*
    |--------------------------------------------------------------------------
    | SUBJECT ALLOCATIONS
    |--------------------------------------------------------------------------
    */

    public function subjectAllocations(): HasMany
    {
        return $this->hasMany(
            SubjectAllocation::class,
            'stream_id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | TEACHER ALLOCATIONS
    |--------------------------------------------------------------------------
    */

    public function teacherAllocations(): HasMany
    {
        return $this->hasMany(
            TeacherAllocation::class,
            'stream_id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | CLASS TEACHER ASSIGNMENTS
    |--------------------------------------------------------------------------
    */

    public function classTeacherAssignments(): HasMany
    {
        return $this->hasMany(
            ClassTeacherAssignment::class,
            'stream_id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | DISPLAY NAME
    |--------------------------------------------------------------------------
    |
    | Examples:
    |
    | Class 7 + Blue  → 7 Blue
    | Class 8 + Green → 8 Green
    | Form 1 + North  → Form 1 North
    |
    */

    public function getDisplayNameAttribute(): string
    {
        if (!$this->schoolClass) {
            return $this->name;
        }

        $className = trim($this->schoolClass->name);

        if (preg_match('/(\d+)/', $className, $matches)) {
            return $matches[1] . ' ' . $this->name;
        }

        return $className . ' ' . $this->name;
    }

    /*
    |--------------------------------------------------------------------------
    | STUDENT COUNT
    |--------------------------------------------------------------------------
    */

    public function getStudentCountAttribute(): int
    {
        return $this->students()->count();
    }

    /*
    |--------------------------------------------------------------------------
    | AVAILABLE SPACES
    |--------------------------------------------------------------------------
    */

    public function getAvailableSpacesAttribute(): int
    {
        return max(
            0,
            $this->capacity - $this->student_count
        );
    }

    /*
    |--------------------------------------------------------------------------
    | FULL STATUS
    |--------------------------------------------------------------------------
    */

    public function getIsFullAttribute(): bool
    {
        return $this->student_count >= $this->capacity;
    }
}