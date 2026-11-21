<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use App\Traits\Auditable;
class Student extends Model
{
    use HasFactory;
    use Auditable;
    /*
    |--------------------------------------------------------------------------
    | Mass Assignment
    |--------------------------------------------------------------------------
    */

    protected $fillable = [
        'admission_number',
        'admission_no',
        'admission_date',

        'first_name',
        'middle_name',
        'last_name',

        'gender',
        'dob',
        'birth_certificate',

        'religion',
        'nationality',
        'blood_group',

        'phone',
        'email',
        'address',

        'school_class_id',
        'stream_id',
        'academic_year_id',
        'term_id',

        'parent_name',
        'parent_phone',
        'parent_email',

        'father_name',
        'father_phone',

        'mother_name',
        'mother_phone',

        'guardian_name',
        'guardian_phone',
        'guardian_relationship',

        'medical_conditions',
        'allergies',

        'previous_school',

        'photo',
        'status',
    ];

    /*
    |--------------------------------------------------------------------------
    | Attribute Casting
    |--------------------------------------------------------------------------
    */

    protected function casts(): array
    {
        return [
            'admission_date' => 'date',
            'dob'            => 'date',
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Full Name
    |--------------------------------------------------------------------------
    |
    | Usage:
    | $student->full_name
    |
    */

    public function getFullNameAttribute(): string
    {
        return trim(
            implode(' ', array_filter([
                $this->first_name,
                $this->middle_name,
                $this->last_name,
            ]))
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Academic Relationships
    |--------------------------------------------------------------------------
    */

    public function schoolClass(): BelongsTo
    {
        return $this->belongsTo(
            SchoolClass::class,
            'school_class_id'
        );
    }

    public function stream(): BelongsTo
    {
        return $this->belongsTo(
            Stream::class,
            'stream_id'
        );
    }

    public function academicYear(): BelongsTo
    {
        return $this->belongsTo(
            AcademicYear::class,
            'academic_year_id'
        );
    }

    public function term(): BelongsTo
    {
        return $this->belongsTo(
            Term::class,
            'term_id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Class Teacher
    |--------------------------------------------------------------------------
    */

    public function getClassTeacherAttribute(): ?Teacher
    {
        $assignment = ClassTeacherAssignment::forClass(
            $this->school_class_id,
            $this->stream_id,
            $this->academic_year_id,
            $this->term_id
        );

        return $assignment?->teacher;
    }

    /*
    |--------------------------------------------------------------------------
    | Student Documents
    |--------------------------------------------------------------------------
    */

    public function documents(): HasMany
    {
        return $this->hasMany(
            StudentDocument::class,
            'student_id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Student Promotions
    |--------------------------------------------------------------------------
    */

    public function promotions(): HasMany
    {
        return $this->hasMany(
            StudentPromotion::class,
            'student_id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Attendance
    |--------------------------------------------------------------------------
    */

    public function attendances(): HasMany
    {
        return $this->hasMany(
            Attendance::class,
            'student_id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Guardians
    |--------------------------------------------------------------------------
    */

    public function guardians(): HasMany
    {
        return $this->hasMany(
            StudentGuardian::class,
            'student_id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Medical Record
    |--------------------------------------------------------------------------
    */

    public function medicalRecord(): HasOne
    {
        return $this->hasOne(
            StudentMedicalRecord::class,
            'student_id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Discipline Records
    |--------------------------------------------------------------------------
    */

    public function disciplines(): HasMany
    {
        return $this->hasMany(
            StudentDiscipline::class,
            'student_id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Backward Compatibility
    |--------------------------------------------------------------------------
    */

    public function disciplineRecords(): HasMany
    {
        return $this->hasMany(
            StudentDiscipline::class,
            'student_id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Student Transfers
    |--------------------------------------------------------------------------
    */

    public function transfers(): HasMany
    {
        return $this->hasMany(
            StudentTransfer::class,
            'student_id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Student Fees
    |--------------------------------------------------------------------------
    */

    public function studentFees(): HasMany
    {
        return $this->hasMany(
            StudentFee::class,
            'student_id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Attendance Percentage
    |--------------------------------------------------------------------------
    */

    public function getAttendancePercentageAttribute(): float
    {
        $total = $this->attendances->count();

        if ($total === 0) {
            return 0;
        }

        $present = $this->attendances
            ->whereIn('status', [
                'Present',
                'present',
            ])
            ->count();

        return round(
            ($present / $total) * 100,
            1
        );
    }
}