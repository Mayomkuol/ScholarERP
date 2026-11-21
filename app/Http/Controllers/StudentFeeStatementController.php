<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Http\Request;
use App\Models\School;

class StudentFeeStatementController extends Controller
{
    /**
     * Student list.
     */
    public function index()
    {
        $students = Student::orderBy('admission_no')
            ->paginate(20);

        return view(
            'student_fee_statements.index',
            compact('students')
        );
    }

    /**
     * Display statement.
     */
    public function show(Student $student)
    {
        $student->load([
            'studentFees.payments',
            'studentFees.academicYear',
            'studentFees.term',
        ]);

        return view(
            'student_fee_statements.show',
            compact('student')
        );
    }
}