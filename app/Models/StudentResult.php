<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StudentResult extends Model
{
    protected $fillable = [
        'exam_id',
        'student_id',
        'total_marks',
        'mean_score',
        'mean_grade',
        'points',
        'position',
    ];

    protected $casts = [
        'total_marks' => 'decimal:2',
        'mean_score'  => 'decimal:2',
        'points'      => 'decimal:2',
        'position'    => 'integer',
    ];

    /**
     * Student who owns this result.
     */
    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    /**
     * Examination associated with this result.
     */
    public function exam(): BelongsTo
    {
        return $this->belongsTo(Exam::class);
    }
}