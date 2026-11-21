<?php

namespace App\Http\Controllers;

use App\Models\Exam;
use App\Models\ExamMark;
use App\Models\Student;
use App\Models\StudentResult;
use Illuminate\Http\Request;

class ReportCardController extends Controller
{
    /**
     * Report Card List
     */
    public function index()
    {
        $students = Student::orderBy('admission_no')->get();

        $exams = Exam::orderBy('name')->get();

        return view('report-cards.index', compact(
            'students',
            'exams'
        ));
    }

    /**
     * Display Student Report Card
     */
    public function show(Student $student, Exam $exam)
    {
        $marks = ExamMark::with('subject')
            ->where('exam_id', $exam->id)
            ->where('student_id', $student->id)
            ->orderBy('subject_id')
            ->get();

        $result = StudentResult::where('exam_id', $exam->id)
            ->where('student_id', $student->id)
            ->first();

        if (!$result) {

            return redirect()
                ->route('report-cards.index')
                ->with(
                    'error',
                    'Results have not been generated for this exam.'
                );

        }

        return view('report-cards.show', compact(
            'student',
            'exam',
            'marks',
            'result'
        ));
    }

    /**
     * Printable Report Card
     */
    public function print(Student $student, Exam $exam)
    {
        $marks = ExamMark::with('subject')
            ->where('exam_id', $exam->id)
            ->where('student_id', $student->id)
            ->orderBy('subject_id')
            ->get();

        $result = StudentResult::where('exam_id', $exam->id)
            ->where('student_id', $student->id)
            ->first();

        if (!$result) {

            return redirect()
                ->route('report-cards.index')
                ->with(
                    'error',
                    'Results have not been generated for this exam.'
                );

        }

        return view('report-cards.print', compact(
            'student',
            'exam',
            'marks',
            'result'
        ));
    }
}