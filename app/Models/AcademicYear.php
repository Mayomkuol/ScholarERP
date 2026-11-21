<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AcademicYear extends Model
{
    protected $fillable = [
        'name',
        'start_date',
        'end_date',
        'is_current',
        'status',
    ];

    /**
     * Get all terms for this academic year.
     */
    public function terms()
    {
        return $this->hasMany(Term::class);
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