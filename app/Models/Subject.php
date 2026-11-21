<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'level',
        'category',
        'active',
    ];

    protected $casts = [
        'active' => 'boolean',
    ];

    /*
    |--------------------------------------------------------------------------
    | SUBJECT ALLOCATIONS
    |--------------------------------------------------------------------------
    */

    public function subjectAllocations()
    {
        return $this->hasMany(
            SubjectAllocation::class,
            'subject_id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | TEACHER ALLOCATIONS
    |--------------------------------------------------------------------------
    */

    public function teacherAllocations()
    {
        return $this->hasMany(
            TeacherAllocation::class,
            'subject_id'
        );
    }
}