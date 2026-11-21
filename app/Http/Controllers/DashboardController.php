<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Teacher;
use App\Models\SchoolClass;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Display the main ScholarERP dashboard.
     */
    public function index()
    {
        /*
        |--------------------------------------------------------------------------
        | Core School Statistics
        |--------------------------------------------------------------------------
        */

        $students = Student::count();

        $teachers = Teacher::count();

        $classes = SchoolClass::count();


        /*
        |--------------------------------------------------------------------------
        | Subjects
        |--------------------------------------------------------------------------
        |
        | We intentionally do not query a Subject model here until its exact
        | structure is confirmed in the project. This prevents the dashboard
        | from breaking because of a model/table mismatch.
        |
        */

        $subjects = 0;

        if (class_exists(\App\Models\Subject::class)) {
            $subjects = \App\Models\Subject::count();
        }


        /*
        |--------------------------------------------------------------------------
        | Students By Class
        |--------------------------------------------------------------------------
        |
        | Used by the enrollment distribution chart.
        |
        */

        $studentsByClass = Student::select(
                'school_class_id',
                DB::raw('COUNT(*) as total')
            )
            ->whereNotNull('school_class_id')
            ->groupBy('school_class_id')
            ->with('schoolClass:id,name')
            ->get();


        /*
        |--------------------------------------------------------------------------
        | Student Class Chart Labels
        |--------------------------------------------------------------------------
        */

        $studentClassLabels = $studentsByClass
            ->map(function ($item) {
                return $item->schoolClass
                    ? $item->schoolClass->name
                    : 'Unassigned';
            })
            ->values()
            ->toArray();


        /*
        |--------------------------------------------------------------------------
        | Student Class Chart Data
        |--------------------------------------------------------------------------
        */

        $studentClassData = $studentsByClass
            ->pluck('total')
            ->values()
            ->toArray();


        /*
        |--------------------------------------------------------------------------
        | Assigned / Unassigned Students
        |--------------------------------------------------------------------------
        */

        $assignedStudents = Student::whereNotNull('school_class_id')->count();

        $unassignedStudents = Student::whereNull('school_class_id')->count();


        /*
        |--------------------------------------------------------------------------
        | School Information
        |--------------------------------------------------------------------------
        |
        | School::first() is used because ScholarERP currently stores the
        | current school setup in the school settings record.
        |
        */

        $school = null;

        if (class_exists(\App\Models\School::class)) {
            $school = \App\Models\School::first();
        }


        /*
        |--------------------------------------------------------------------------
        | Academic Session Information
        |--------------------------------------------------------------------------
        */

        $academicYear = $school?->current_academic_year ?? null;

        $currentTerm = $school?->current_term ?? null;


        /*
        |--------------------------------------------------------------------------
        | Current Date Information
        |--------------------------------------------------------------------------
        */

        $today = now();

        $currentDate = $today->format('l, d F Y');

        $currentMonth = $today->format('F Y');


        /*
        |--------------------------------------------------------------------------
        | Dashboard View
        |--------------------------------------------------------------------------
        */

        return view('dashboard.index', compact(
            'students',
            'teachers',
            'classes',
            'subjects',
            'studentClassLabels',
            'studentClassData',
            'assignedStudents',
            'unassignedStudents',
            'school',
            'academicYear',
            'currentTerm',
            'currentDate',
            'currentMonth'
        ));
    }
}
