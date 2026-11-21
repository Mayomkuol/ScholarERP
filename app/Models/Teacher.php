<?php

namespace App\Models;
use App\Models\User;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Auditable;

class Teacher extends Model
{
    use Auditable;
    protected $fillable = [
        'user_id',
        'employee_no',

        'first_name',
        'middle_name',
        'last_name',

        'gender',
        'date_of_birth',
        'national_id',
        'phone',
        'email',
        'address',
        'photo',

        'department_id',
        'designation',
        'employment_type',
        'joining_date',
        'qualification',
        'specialization',
        'salary',
        'status',
    ];

    protected $casts = [
        'joining_date' => 'date',
        'date_of_birth' => 'date',
        'status' => 'boolean',
    ];

    /*
    |--------------------------------------------------------------------------
    | Department
    |--------------------------------------------------------------------------
    */

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    /*
    |--------------------------------------------------------------------------
    | User Account
    |--------------------------------------------------------------------------
    */

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /*
    |--------------------------------------------------------------------------
    | Teacher Allocations
    |--------------------------------------------------------------------------
    */

    public function allocations()
    {
        return $this->hasMany(TeacherAllocation::class);
    }

    /*
    |--------------------------------------------------------------------------
    | Class Teacher Assignments
    |--------------------------------------------------------------------------
    */

    public function classTeacherAssignments()
    {
        return $this->hasMany(
            ClassTeacherAssignment::class,
            'teacher_id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Full Name
    |--------------------------------------------------------------------------
    */

    public function getFullNameAttribute()
    {
        return trim(
            "{$this->first_name} {$this->middle_name} {$this->last_name}"
        );
    }
}