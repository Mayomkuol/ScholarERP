<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Term extends Model
{
    protected $fillable = [
        'academic_year_id',
        'name',
        'code',
        'start_date',
        'end_date',
        'is_current',
        'status',
    ];

    public function academicYear()
    {
        return $this->belongsTo(AcademicYear::class);
    }
    public function teacherAllocations()
{
    return $this->hasMany(TeacherAllocation::class);
}
public function studentFees()
{
    return $this->hasMany(StudentFee::class);
}
}