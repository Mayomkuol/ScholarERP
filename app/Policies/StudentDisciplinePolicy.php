<?php

namespace App\Policies;

use App\Models\ClassTeacherAssignment;
use App\Models\Student;
use App\Models\StudentDiscipline;
use App\Models\User;

class StudentDisciplinePolicy
{
    /**
     * View the discipline records list for a student.
     */
    public function viewAny(
        User $user,
        Student $student
    ): bool {
        return $this->canAccessStudent($user, $student);
    }

    /**
     * View a discipline record.
     */
    public function view(
        User $user,
        Student $student,
        StudentDiscipline $discipline
    ): bool {
        // Prevent IDOR: record must belong to the requested student.
        if ($discipline->student_id !== $student->id) {
            return false;
        }

        return $this->canAccessStudent($user, $student);
    }

    /**
     * Create a discipline record.
     */
    public function create(
        User $user,
        Student $student
    ): bool {
        return $this->canAccessStudent($user, $student);
    }

    /**
     * Update a discipline record.
     */
    public function update(
        User $user,
        Student $student,
        StudentDiscipline $discipline
    ): bool {
        if ($discipline->student_id !== $student->id) {
            return false;
        }

        return $this->canAccessStudent($user, $student);
    }

    /**
     * Delete a discipline record.
     */
    public function delete(
        User $user,
        Student $student,
        StudentDiscipline $discipline
    ): bool {
        if ($discipline->student_id !== $student->id) {
            return false;
        }

        // Only Super Admin can delete discipline records.
        return $user->hasRole('Super Admin');
    }

    /**
     * Check whether the user can access the student's discipline records.
     */
    private function canAccessStudent(
        User $user,
        Student $student
    ): bool {
        // Super Admin and Registrar can access all student records.
        if (
            $user->hasRole('Super Admin') ||
            $user->hasRole('Registrar')
        ) {
            return true;
        }

        // Only authorized teachers can access assigned students.
        if (!$user->hasRole('Teacher')) {
            return false;
        }

        // Find the teacher profile linked to this login account.
        $teacher = $user->teacher;

        // Inactive or missing teacher profiles must not have access.
        if (!$teacher || !$teacher->status) {
            return false;
        }

        // Check for an active class-wide or matching stream assignment.
        return ClassTeacherAssignment::query()
            ->where('teacher_id', $teacher->id)
            ->where('school_class_id', $student->school_class_id)
            ->where('academic_year_id', $student->academic_year_id)
            ->where('term_id', $student->term_id)
            ->active()
            ->where(function ($query) use ($student) {
                $query->whereNull('stream_id');

                if ($student->stream_id !== null) {
                    $query->orWhere('stream_id', $student->stream_id);
                }
            })
            ->exists();
    }
}
