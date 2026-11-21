<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClassTeacherAssignment extends Model
{
    use HasFactory;

    protected $fillable = [
        'teacher_id',
        'school_class_id',
        'stream_id',
        'academic_year_id',
        'term_id',
        'status',
        'assigned_at',
        'assigned_by',
    ];

    protected $casts = [
        'assigned_at' => 'date',
    ];


    /*
    |--------------------------------------------------------------------------
    | Teacher
    |--------------------------------------------------------------------------
    */

    public function teacher()
    {
        return $this->belongsTo(
            Teacher::class,
            'teacher_id'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | School Class
    |--------------------------------------------------------------------------
    */

    public function schoolClass()
    {
        return $this->belongsTo(
            SchoolClass::class,
            'school_class_id'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Stream
    |--------------------------------------------------------------------------
    */

    public function stream()
    {
        return $this->belongsTo(
            Stream::class,
            'stream_id'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Academic Year
    |--------------------------------------------------------------------------
    */

    public function academicYear()
    {
        return $this->belongsTo(
            AcademicYear::class,
            'academic_year_id'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Term
    |--------------------------------------------------------------------------
    */

    public function term()
    {
        return $this->belongsTo(
            Term::class,
            'term_id'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | User Who Created Assignment
    |--------------------------------------------------------------------------
    */

    public function assignedBy()
    {
        return $this->belongsTo(
            User::class,
            'assigned_by'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Active Assignments
    |--------------------------------------------------------------------------
    */

    public function scopeActive($query)
    {
        return $query->where('status', 'Active');
    }


    /*
    |--------------------------------------------------------------------------
    | Find Class Teacher
    |--------------------------------------------------------------------------
    |
    | Priority:
    |
    | 1. Specific stream teacher
    | 2. Whole-class teacher
    |
    */

    public static function forClass(
        int $schoolClassId,
        ?int $streamId,
        int $academicYearId,
        int $termId
    ) {
        return static::with('teacher')
            ->active()
            ->where('school_class_id', $schoolClassId)
            ->where('academic_year_id', $academicYearId)
            ->where('term_id', $termId)
            ->where(function ($query) use ($streamId) {

                if ($streamId) {

                    $query->where('stream_id', $streamId)
                        ->orWhereNull('stream_id');

                } else {

                    $query->whereNull('stream_id');
                }
            })
            ->orderByRaw(
                'CASE WHEN stream_id IS NULL THEN 1 ELSE 0 END'
            )
            ->first();
    }
}