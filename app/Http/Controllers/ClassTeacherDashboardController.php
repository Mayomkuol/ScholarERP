<?php

namespace App\Http\Controllers;

use App\Models\ClassTeacherAssignment;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ClassTeacherDashboardController extends Controller
{
    /**
     * Display the logged-in teacher's class dashboard.
     */
    public function index(Request $request): View
    {
        /*
        |--------------------------------------------------------------------------
        | Get authenticated user
        |--------------------------------------------------------------------------
        */

        $user = $request->user();

        /*
        |--------------------------------------------------------------------------
        | Ensure the account is linked to a teacher
        |--------------------------------------------------------------------------
        */

        $teacher = $user->teacher;

        if (!$teacher) {
            abort(
                403,
                'Your user account is not linked to a teacher record.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Get the teacher's current active class assignment
        |--------------------------------------------------------------------------
        |
        | IMPORTANT:
        | We never accept teacher_id, class_id, stream_id, academic_year_id,
        | or term_id from the URL or request.
        |
        | Everything is derived from the authenticated teacher's own record.
        |
        */

        $assignment = ClassTeacherAssignment::query()
            ->with([
                'schoolClass',
                'stream',
                'academicYear',
                'term',
            ])
            ->where('teacher_id', $teacher->id)
            ->where('status', 'Active')
            ->whereHas('academicYear', function ($query) {
                $query->where('is_current', true);
            })
            ->whereHas('term', function ($query) {
                $query->where('is_current', true);
            })
            ->first();

        /*
        |--------------------------------------------------------------------------
        | No current class assignment
        |--------------------------------------------------------------------------
        */

        if (!$assignment) {
            return view(
                'class-teacher-dashboard.index',
                [
                    'teacher' => $teacher,
                    'assignment' => null,
                    'students' => collect(),
                    'totalStudents' => 0,
                    'maleStudents' => 0,
                    'femaleStudents' => 0,
                ]
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Load students belonging ONLY to this assignment
        |--------------------------------------------------------------------------
        |
        | Security boundary:
        |
        | teacher
        |     ↓
        | class teacher assignment
        |     ↓
        | academic year
        |     ↓
        | term
        |     ↓
        | class
        |     ↓
        | stream
        |
        | This prevents a class teacher from seeing students from another
        | class, stream, academic year, or term.
        |
        */

        $studentsQuery = Student::query()
            ->with([
                'schoolClass',
                'stream',
            ])
            ->where('school_class_id', $assignment->school_class_id)
            ->where('academic_year_id', $assignment->academic_year_id)
            ->where('term_id', $assignment->term_id)
            ->where('status', 'Active');

        /*
        |--------------------------------------------------------------------------
        | Stream restriction
        |--------------------------------------------------------------------------
        |
        | If the class teacher is assigned to a specific stream, only students
        | in that stream are visible.
        |
        | If the assignment has no stream, only students without a stream are
        | visible.
        |
        */

        if ($assignment->stream_id !== null) {
            $studentsQuery->where(
                'stream_id',
                $assignment->stream_id
            );
        } else {
            $studentsQuery->whereNull('stream_id');
        }

        /*
        |--------------------------------------------------------------------------
        | Retrieve students
        |--------------------------------------------------------------------------
        */

        $students = $studentsQuery
            ->orderBy('first_name')
            ->orderBy('last_name')
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Student statistics
        |--------------------------------------------------------------------------
        */

        $totalStudents = $students->count();

        $maleStudents = $students
            ->where('gender', 'Male')
            ->count();

        $femaleStudents = $students
            ->where('gender', 'Female')
            ->count();

        /*
        |--------------------------------------------------------------------------
        | Display dashboard
        |--------------------------------------------------------------------------
        */

        return view(
            'class-teacher-dashboard.index',
            compact(
                'teacher',
                'assignment',
                'students',
                'totalStudents',
                'maleStudents',
                'femaleStudents'
            )
        );
    }
}