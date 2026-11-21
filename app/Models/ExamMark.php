<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExamMark extends Model
{
    use HasFactory;

    protected $fillable = [
        'exam_id',
        'student_id',
        'subject_id',
        'marks',
        'grade',
        'points',
        'remarks',
    ];

    /**
     * Exam mark belongs to an exam.
     */
    public function exam()
    {
        return $this->belongsTo(
            Exam::class,
            'exam_id'
        );
    }

    /**
     * Exam mark belongs to a student.
     */
    public function student()
    {
        return $this->belongsTo(
            Student::class,
            'student_id'
        );
    }

    /**
     * Exam mark belongs to a subject.
     */
    public function subject()
    {
        return $this->belongsTo(
            Subject::class,
            'subject_id'
        );
    }
}