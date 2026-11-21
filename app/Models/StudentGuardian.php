<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudentGuardian extends Model
{
    protected $fillable = [

        'student_id',
        'guardian_type',
        'name',
        'phone',
        'email',
        'occupation',
        'relationship',
        'address',
        'is_primary'
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}
