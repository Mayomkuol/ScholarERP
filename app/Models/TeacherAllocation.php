<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TeacherAllocation extends Model
{
    protected $fillable = [
        'teacher_id',
        'subject_id',
        'school_class_id',
        'stream_id',
        'academic_year_id',
        'term_id',
    ];

    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

   public function schoolClass()
{
    return $this->belongsTo(SchoolClass::class, 'school_class_id');
}

    public function stream()
    {
        return $this->belongsTo(Stream::class);
    }

    public function academicYear()
    {
        return $this->belongsTo(AcademicYear::class);
    }

    public function term()
    {
        return $this->belongsTo(Term::class);
    }
}