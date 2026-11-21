<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudentMedicalRecord extends Model
{
    protected $fillable = [

        'student_id',
        'blood_group',
        'medical_conditions',
        'allergies',
        'doctor_name',
        'doctor_phone',
        'insurance_provider',
        'insurance_number',
        'notes'
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}