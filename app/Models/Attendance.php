<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    protected $fillable = [
        'student_id',
        'academic_year_id',
        'term_id',
        'school_class_id',
        'stream_id',
        'attendance_date',
        'status',
        'arrival_time',
        'remarks',
        'recorded_by',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function academicYear()
    {
        return $this->belongsTo(AcademicYear::class);
    }

    public function term()
    {
        return $this->belongsTo(Term::class);
    }

    public function schoolClass()
    {
        return $this->belongsTo(SchoolClass::class);
    }

    public function stream()
    {
        return $this->belongsTo(Stream::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'recorded_by');
    }
}