<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SchoolClass extends Model
{
    protected $fillable = [
        'name',
        'code',
        'level',
        'capacity',
        'active',
    ];

    protected $casts = [
        'capacity' => 'integer',
        'active'   => 'boolean',
    ];

    /*
    |--------------------------------------------------------------------------
    | STREAMS
    |--------------------------------------------------------------------------
    */

    public function streams(): HasMany
    {
        return $this->hasMany(Stream::class, 'school_class_id');
    }

    /*
    |--------------------------------------------------------------------------
    | SUBJECT ALLOCATIONS
    |--------------------------------------------------------------------------
    |
    | Determines which subjects are offered in this class.
    |
    */

    public function subjectAllocations(): HasMany
    {
        return $this->hasMany(
            SubjectAllocation::class,
            'school_class_id'
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
            'school_class_id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | ACTIVE CLASS TEACHER ASSIGNMENTS
    |--------------------------------------------------------------------------
    */

    public function activeClassTeacherAssignments(): HasMany
    {
        return $this->hasMany(
            ClassTeacherAssignment::class,
            'school_class_id'
        )->where('status', 'Active');
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
            'school_class_id'
        );
    }
}