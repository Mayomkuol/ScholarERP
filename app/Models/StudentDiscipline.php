<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudentDiscipline extends Model
{
    protected $fillable = [

        'student_id',

        'incident_date',

        'category',

        'incident',

        'action_taken',

        'reported_by',

        'status',

    ];

    protected $casts = [

        'incident_date' => 'date',

    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}