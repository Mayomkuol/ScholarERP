<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Exam extends Model
{
    protected $fillable = [

        'exam_type_id',
        'academic_year_id',
        'term_id',
        'school_class_id',

        'name',

        'start_date',
        'end_date',

        'status'

    ];

    protected $casts = [

        'start_date'=>'date',
        'end_date'=>'date',

    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function type()
    {
        return $this->belongsTo(ExamType::class,'exam_type_id');
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

    public function marks()
    {
        return $this->hasMany(ExamMark::class);
    }
}