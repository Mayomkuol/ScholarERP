<?php

namespace App\Policies;

use App\Models\ClassTeacherAssignment;
use App\Models\Student;
use App\Models\User;

class StudentPolicy
{
    /**
     * Determine whether the user can view any students.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('students.view');
    }

    /**
     * Determine whether the user can view a specific student.
     */
    public function view(User $user, Student $student): bool
    {
        /*
         * Super Admin has unrestricted access.
         */
        if ($user->hasRole('Super Admin')) {
            return true;
        }

        /*
         * Registrar has student-management access.
         */
        if ($user->hasRole('Registrar')) {
            return true;
        }

        /*
         * A class teacher can only view students
         * belonging to their current assignment.
         */
        return $this->isClassTeacherForStudent($user, $student);
    }

    /**
     * Determine whether the user can create students.
     */
    public function create(User $user): bool
    {
        return $user->can('students.create');
    }

    /**
     * Determine whether the user can update a student.
     */
    public function update(User $user, Student $student): bool
    {
        /*
         * Student master records are sensitive.
         *
         * Only Super Admin and Registrar may edit them.
         */
        if ($user->hasRole('Super Admin')) {
            return true;
        }

        if ($user->hasRole('Registrar')) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can delete a student.
     */
    public function delete(User $user, Student $student): bool
    {
        /*
         * Physical student deletion is restricted
         * to Super Admin.
         */
        return $user->hasRole('Super Admin');
    }

    /**
     * Determine whether the user can create
     * a discipline record for this student.
     */
    public function createDiscipline(
        User $user,
        Student $student
    ): bool {
        if ($user->hasRole('Super Admin')) {
            return true;
        }

        if ($user->hasRole('Registrar')) {
            return true;
        }

        return $this->isClassTeacherForStudent(
            $user,
            $student
        );
    }

    /**
     * Determine whether the student belongs
     * to the logged-in teacher's active class
     * teacher assignment.
     */
    protected function isClassTeacherForStudent(
        User $user,
        Student $student
    ): bool {
        $teacher = $user->teacher;

        if (!$teacher) {
            return false;
        }

        return ClassTeacherAssignment::query()
            ->where('teacher_id', $teacher->id)
            ->where('status', 'Active')
            ->where(
                'school_class_id',
                $student->school_class_id
            )
            ->where(
                'academic_year_id',
                $student->academic_year_id
            )
            ->where(
                'term_id',
                $student->term_id
            )
            ->whereHas('academicYear', function ($query) {
                $query->where('is_current', true);
            })
            ->whereHas('term', function ($query) {
                $query->where('is_current', true);
            })
            ->when(
                $student->stream_id !== null,
                function ($query) use ($student) {
                    $query->where(
                        'stream_id',
                        $student->stream_id
                    );
                },
                function ($query) {
                    $query->whereNull('stream_id');
                }
            )
            ->exists();
    }
}