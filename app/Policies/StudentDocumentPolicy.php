<?php

namespace App\Policies;

use App\Models\ClassTeacherAssignment;
use App\Models\Student;
use App\Models\StudentDocument;
use App\Models\User;

class StudentDocumentPolicy
{
    /**
     * View documents belonging to a student.
     */
    public function view(
    User $user,
    StudentDocument $document
): bool {
    $student = $document->student;

    if (!$student) {
        return false;
    }

    return $this->canAccessStudent($user, $student);
}

/**
 * View the document list belonging to a student.
 */
public function viewStudent(
    User $user,
    Student $student
): bool {
    return $this->canAccessStudent($user, $student);
}

    /**
     * Upload a document for a student.
     */
    public function create(
        User $user,
        Student $student
    ): bool {
        return $this->canAccessStudent($user, $student);
    }

    /**
     * Delete a student's document.
     */
    public function delete(
        User $user,
        StudentDocument $document
    ): bool {
        /*
         * The document must actually belong to a student.
         */
        $student = $document->student;

        if (!$student) {
            return false;
        }

        /*
         * Only Super Admin and Registrar
         * can permanently delete documents.
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
     * Determine whether the user can access
     * the student's records.
     */
    protected function canAccessStudent(
        User $user,
        Student $student
    ): bool {
        /*
         * Super Admin has unrestricted access.
         */
        if ($user->hasRole('Super Admin')) {
            return true;
        }

        /*
         * Registrar can access student records.
         */
        if ($user->hasRole('Registrar')) {
            return true;
        }

        /*
         * Other users must be linked to a teacher.
         */
        $teacher = $user->teacher;

        if (!$teacher) {
            return false;
        }

        /*
         * Teacher must be the active class teacher
         * for this student's current class, stream,
         * academic year and term.
         */
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
            ->whereHas(
                'academicYear',
                fn ($query) =>
                    $query->where('is_current', true)
            )
            ->whereHas(
                'term',
                fn ($query) =>
                    $query->where('is_current', true)
            )
            ->when(
                $student->stream_id !== null,
                fn ($query) =>
                    $query->where(
                        'stream_id',
                        $student->stream_id
                    ),
                fn ($query) =>
                    $query->whereNull('stream_id')
            )
            ->exists();
    }
}