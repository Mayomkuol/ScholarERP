@extends('layouts.app')

@section('content')

<div class="container-fluid">

    {{-- ============================================================
        PAGE HEADER
    ============================================================ --}}
    <div class="d-flex justify-content-between align-items-center mb-4 no-print">

        <div>
            <h4 class="fw-bold mb-1">
                <i class="bi bi-bar-chart-line me-1"></i>
                Stream Performance Report
            </h4>

            <p class="text-muted mb-0">
                Generate academic performance reports for a selected class stream.
            </p>
        </div>

    </div>


    {{-- ============================================================
        SUCCESS / ERROR MESSAGE
    ============================================================ --}}
    @if(session('error'))

        <div class="alert alert-danger alert-dismissible fade show no-print">

            <i class="bi bi-exclamation-triangle me-1"></i>

            {{ session('error') }}

            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert">
            </button>

        </div>

    @endif


    {{-- ============================================================
        VALIDATION ERRORS
    ============================================================ --}}
    @if($errors->any())

        <div class="alert alert-danger no-print">

            <div class="fw-bold mb-2">
                <i class="bi bi-exclamation-triangle me-1"></i>
                Please correct the following:
            </div>

            <ul class="mb-0">

                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach

            </ul>

        </div>

    @endif


    {{-- ============================================================
        FILTER CARD
    ============================================================ --}}
    <div class="card border-0 shadow-sm mb-4 no-print">

        <div class="card-header bg-white py-3">

            <h5 class="fw-bold mb-0">
                <i class="bi bi-funnel me-1"></i>
                Select Stream
            </h5>

        </div>


        <div class="card-body">

            <form
                method="POST"
                action="{{ route('stream-reports.load') }}"
                id="streamReportForm">

                @csrf


                <div class="row g-3">

                    {{-- EXAMINATION --}}
                    <div class="col-md-4">

                        <label
                            for="exam_id"
                            class="form-label fw-semibold">

                            Examination
                            <span class="text-danger">*</span>

                        </label>


                        <select
                            name="exam_id"
                            id="exam_id"
                            class="form-select"
                            required>

                            <option value="">
                                Select Examination
                            </option>


                            @foreach($exams as $exam)

                                <option
                                    value="{{ $exam->id }}"
                                    data-class-id="{{ $exam->school_class_id }}"
                                    @selected(
                                        old(
                                            'exam_id',
                                            optional($selectedExam)->id
                                        ) == $exam->id
                                    )>

                                    {{ $exam->name }}

                                    @if($exam->schoolClass)
                                        — {{ $exam->schoolClass->name }}
                                    @endif

                                    @if($exam->academicYear)
                                        — {{ $exam->academicYear->name }}
                                    @endif

                                    @if($exam->term)
                                        — {{ $exam->term->name }}
                                    @endif

                                </option>

                            @endforeach

                        </select>


                        <div class="form-text">
                            Selecting an examination automatically selects its class.
                        </div>

                    </div>


                    {{-- SCHOOL CLASS --}}
                    <div class="col-md-4">

                        <label
                            for="school_class_id"
                            class="form-label fw-semibold">

                            School Class
                            <span class="text-danger">*</span>

                        </label>


                        <select
                            name="school_class_id"
                            id="school_class_id"
                            class="form-select"
                            required>

                            <option value="">
                                Select Class
                            </option>


                            @foreach($classes as $class)

                                <option
                                    value="{{ $class->id }}"
                                    @selected(
                                        old(
                                            'school_class_id',
                                            optional($selectedClass)->id
                                        ) == $class->id
                                    )>

                                    {{ $class->name }}

                                </option>

                            @endforeach

                        </select>


                        <div class="form-text">
                            The class must match the selected examination.
                        </div>

                    </div>


                    {{-- STREAM --}}
                    <div class="col-md-4">

                        <label
                            for="stream_id"
                            class="form-label fw-semibold">

                            Stream
                            <span class="text-danger">*</span>

                        </label>


                        <select
                            name="stream_id"
                            id="stream_id"
                            class="form-select"
                            required
                            disabled>

                            <option value="">
                                Select Class First
                            </option>

                        </select>


                        <div class="form-text">
                            Only streams belonging to the selected class are shown.
                        </div>

                    </div>

                </div>


                {{-- ACTION --}}
                <div class="mt-4">

                    <button
                        type="submit"
                        id="generateReportBtn"
                        class="btn btn-primary">

                        <i class="bi bi-bar-chart-fill me-1"></i>

                        Generate Stream Report

                    </button>

                </div>

            </form>

        </div>

    </div>


    {{-- ============================================================
        REPORT
    ============================================================ --}}
    @if($report && $selectedClass && $selectedStream && $selectedExam)

        @php

            /*
            |--------------------------------------------------------------------------
            | ONLY SUBJECTS ALLOCATED TO SELECTED CLASS + STREAM
            |--------------------------------------------------------------------------
            */

            $allocatedSubjectIds =
                \App\Models\SubjectAllocation::query()
                    ->where('school_class_id', $selectedClass->id)
                    ->where('stream_id', $selectedStream->id)
                    ->pluck('subject_id')
                    ->unique()
                    ->values();


            /*
            |--------------------------------------------------------------------------
            | SELECTED STREAM SUBJECTS
            |--------------------------------------------------------------------------
            */

            $streamSubjects =
                \App\Models\Subject::query()
                    ->whereIn('id', $allocatedSubjectIds)
                    ->orderBy('name')
                    ->get();


            /*
            |--------------------------------------------------------------------------
            | STUDENTS
            |--------------------------------------------------------------------------
            */

            $students =
                collect($report['students'] ?? []);


            /*
            |--------------------------------------------------------------------------
            | CLEAN RESULTS
            |--------------------------------------------------------------------------
            */

            $cleanResults = [];


            foreach($students as $result) {

                $total = 0;

                $subjectsAttempted = 0;

                $subjectResults = [];


                foreach($streamSubjects as $subject) {

                    $subjectResult =
                        $result['subjects'][$subject->id]
                        ?? null;


                    /*
                    |--------------------------------------------------------------------------
                    | DEFAULT RESULT
                    |--------------------------------------------------------------------------
                    */

                    if(!$subjectResult) {

                        $subjectResult = [
                            'mark' => null,
                            'grade' => null,
                            'absent' => false,
                        ];

                    }


                    /*
                    |--------------------------------------------------------------------------
                    | STORE RESULT
                    |--------------------------------------------------------------------------
                    */

                    $subjectResults[$subject->id] =
                        $subjectResult;


                    /*
                    |--------------------------------------------------------------------------
                    | TOTAL
                    |--------------------------------------------------------------------------
                    */

                    if(
                        isset($subjectResult['mark']) &&
                        $subjectResult['mark'] !== null
                    ) {

                        $mark =
                            (float) $subjectResult['mark'];

                        $total += $mark;

                        $subjectsAttempted++;

                    }

                }


                /*
                |--------------------------------------------------------------------------
                | MEAN
                |--------------------------------------------------------------------------
                */

                $mean =
                    $subjectsAttempted > 0
                        ? round(
                            $total / $subjectsAttempted,
                            2
                        )
                        : null;


                /*
                |--------------------------------------------------------------------------
                | OVERALL GRADE
                |--------------------------------------------------------------------------
                */

                $overallGrade = null;


                if($mean !== null) {

                    if($mean >= 80) {
                        $overallGrade = 'A';
                    }
                    elseif($mean >= 75) {
                        $overallGrade = 'B+';
                    }
                    elseif($mean >= 70) {
                        $overallGrade = 'B';
                    }
                    elseif($mean >= 65) {
                        $overallGrade = 'B-';
                    }
                    elseif($mean >= 60) {
                        $overallGrade = 'C+';
                    }
                    elseif($mean >= 55) {
                        $overallGrade = 'C';
                    }
                    elseif($mean >= 50) {
                        $overallGrade = 'C-';
                    }
                    elseif($mean >= 45) {
                        $overallGrade = 'D+';
                    }
                    elseif($mean >= 40) {
                        $overallGrade = 'D';
                    }
                    elseif($mean >= 30) {
                        $overallGrade = 'D-';
                    }
                    else {
                        $overallGrade = 'E';
                    }

                }


                /*
                |--------------------------------------------------------------------------
                | CLEAN RESULT
                |--------------------------------------------------------------------------
                */

                $cleanResults[] = [

                    'student' =>
                        $result['student'],

                    'subjects' =>
                        $subjectResults,

                    'total' =>
                        $total,

                    'mean' =>
                        $mean,

                    'grade' =>
                        $overallGrade,

                    'position' =>
                        null,

                ];

            }


            /*
            |--------------------------------------------------------------------------
            | STREAM POSITION
            |--------------------------------------------------------------------------
            */

            usort(
                $cleanResults,
                function($a, $b) {

                    return
                        ($b['total'] ?? 0)
                        <=>
                        ($a['total'] ?? 0);

                }
            );


            $previousTotal = null;

            $currentPosition = 0;


            foreach(
                $cleanResults as $index => &$cleanResult
            ) {

                $currentTotal =
                    (float) ($cleanResult['total'] ?? 0);


                if(
                    $previousTotal === null ||
                    $currentTotal < $previousTotal
                ) {

                    $currentPosition =
                        $index + 1;

                }


                $cleanResult['position'] =
                    $currentPosition;


                $previousTotal =
                    $currentTotal;

            }

            unset($cleanResult);


            /*
            |--------------------------------------------------------------------------
            | STREAM AVERAGE
            |--------------------------------------------------------------------------
            */

            $validMeans =
                collect($cleanResults)
                    ->pluck('mean')
                    ->filter(
                        fn($mean) =>
                            $mean !== null
                    );


            $cleanStreamAverage =
                $validMeans->count() > 0
                    ? round(
                        $validMeans->average(),
                        2
                    )
                    : null;


            /*
            |--------------------------------------------------------------------------
            | SUBJECT PERFORMANCE
            |--------------------------------------------------------------------------
            */

            $cleanSubjectPerformance = [];


            foreach($streamSubjects as $subject) {

                $marksForSubject = [];


                foreach($cleanResults as $cleanResult) {

                    $subjectResult =
                        $cleanResult['subjects'][$subject->id]
                        ?? null;


                    if(
                        $subjectResult &&
                        $subjectResult['mark'] !== null
                    ) {

                        $marksForSubject[] =
                            (float) $subjectResult['mark'];

                    }

                }


                $cleanSubjectPerformance[$subject->id] = [

                    'count' =>
                        count($marksForSubject),

                    'average' =>
                        count($marksForSubject) > 0
                            ? round(
                                array_sum($marksForSubject)
                                / count($marksForSubject),
                                2
                            )
                            : null,

                    'highest' =>
                        count($marksForSubject) > 0
                            ? max($marksForSubject)
                            : null,

                    'lowest' =>
                        count($marksForSubject) > 0
                            ? min($marksForSubject)
                            : null,

                ];

            }


            /*
            |--------------------------------------------------------------------------
            | CLASS LABEL
            |--------------------------------------------------------------------------
            */

            $className =
                strtoupper(
                    trim(
                        (string) $selectedClass->name
                    )
                );


            $isFormOne =
                in_array(
                    $className,
                    [
                        'FORM 1',
                        'FORM ONE',
                        'FORM-1',
                    ],
                    true
                );

        @endphp


        {{-- ============================================================
            REPORT
        ============================================================ --}}
        <div class="card border-0 shadow-sm">


            {{-- ========================================================
                ACTION HEADER
            ========================================================= --}}
            <div class="card-header bg-white no-print">

                <div class="d-flex justify-content-between align-items-center">

                    <div>

                        <h5 class="fw-bold mb-1">

                            {{ $selectedClass->name }}
                            -
                            {{ $selectedStream->name }}

                        </h5>


                        <small class="text-muted">

                            {{ $selectedExam->name }}

                            |

                            {{ $selectedExam->academicYear?->name }}

                            |

                            {{ $selectedExam->term?->name }}

                        </small>

                    </div>


                    <button
                        type="button"
                        onclick="window.print()"
                        class="btn btn-dark">

                        <i class="bi bi-printer me-1"></i>

                        Print Report

                    </button>

                </div>

            </div>


            {{-- ========================================================
                PRINTABLE REPORT
            ========================================================= --}}
            <div
                id="streamReport"
                class="stream-report-document">


                {{-- ====================================================
                    SCHOOL HEADER
                ===================================================== --}}
                <div class="school-header">

                    @if($school?->logo)

                        <img
                            src="{{ asset('storage/' . ltrim($school->logo, '/')) }}"
                            alt="{{ $school->name ?? 'School Logo' }}"
                            class="school-logo">

                    @endif


                    <h2 class="school-name">

                        {{ $school->name ?? 'SCHOOL NAME' }}

                    </h2>


                    @if($school?->motto)

                        <div class="school-motto">

                            "{{ $school->motto }}"

                        </div>

                    @endif


                    @if($school?->address)

                        <div class="school-address">

                            {{ $school->address }}

                        </div>

                    @endif


                    <div class="report-title">

                        STREAM ACADEMIC PERFORMANCE REPORT

                    </div>


                    @if($isFormOne)

                        <div class="report-subtitle">

                            FORM 1 — STREAM SUBJECT PERFORMANCE

                        </div>

                    @endif

                </div>


                {{-- ====================================================
                    REPORT INFORMATION
                ===================================================== --}}
                <div class="report-info-grid">

                    <div>

                        <span>Examination</span>

                        <strong>
                            {{ $selectedExam->name }}
                        </strong>

                    </div>


                    <div>

                        <span>Class</span>

                        <strong>
                            {{ $selectedClass->name }}
                        </strong>

                    </div>


                    <div>

                        <span>Stream</span>

                        <strong>
                            {{ $selectedStream->name }}
                        </strong>

                    </div>


                    <div>

                        <span>Academic Year</span>

                        <strong>
                            {{ $selectedExam->academicYear?->name ?? '—' }}
                        </strong>

                    </div>


                    <div>

                        <span>Term</span>

                        <strong>
                            {{ $selectedExam->term?->name ?? '—' }}
                        </strong>

                    </div>


                    <div>

                        <span>Students</span>

                        <strong>
                            {{ count($cleanResults) }}
                        </strong>

                    </div>


                    <div>

                        <span>Stream Average</span>

                        <strong>

                            @if($cleanStreamAverage !== null)

                                {{ number_format($cleanStreamAverage, 2) }}

                            @else

                                —

                            @endif

                        </strong>

                    </div>


                    <div>

                        <span>Subjects</span>

                        <strong>
                            {{ $streamSubjects->count() }}
                        </strong>

                    </div>

                </div>


               


                {{-- ====================================================
                    STREAM RANKING
                ===================================================== --}}
                <div class="section-title">

                    Stream Ranking

                </div>


                @if($streamSubjects->count())


                    <div class="table-responsive">

                        <table class="table table-bordered report-table">

                            <thead>

                                <tr>

                                    {{-- POSITION --}}
                                    <th class="position-column">
                                        Pos.
                                    </th>


                                    {{-- ADMISSION --}}
                                    <th class="admission-column">
                                        Admission No.
                                    </th>


                                    {{-- STUDENT --}}
                                    <th class="student-column">
                                        Student Name
                                    </th>


                                    {{-- SUBJECTS --}}
                                    @foreach($streamSubjects as $subject)

                                        <th class="subject-column">

                                            <div class="subject-header">

                                                {{ $subject->name }}

                                            </div>

                                            <div class="subject-subheader">

                                                Mark / Grade

                                            </div>

                                        </th>

                                    @endforeach


                                    {{-- TOTAL --}}
                                    <th class="summary-column">
                                        Total
                                    </th>


                                    {{-- MEAN --}}
                                    <th class="summary-column">
                                        Mean
                                    </th>


                                    {{-- OVERALL GRADE --}}
                                    <th class="summary-column">
                                        Grade
                                    </th>

                                </tr>

                            </thead>


                            <tbody>


                                @forelse($cleanResults as $result)

                                    <tr>

                                        {{-- POSITION --}}
                                        <td class="text-center fw-bold">

                                            {{ $result['position'] }}

                                        </td>


                                        {{-- ====================================================
                                            ADMISSION NUMBER

                                            IMPORTANT:
                                            admission_no is used FIRST because your database
                                            has the actual student admission number there.
                                        ===================================================== --}}
                                        <td class="admission-number">

                                            {{ $result['student']->admission_no
                                                ?? $result['student']->admission_number
                                                ?? '—'
                                            }}

                                        </td>


                                        {{-- STUDENT NAME --}}
                                        <td class="student-name">

                                            {{ $result['student']->first_name }}

                                            @if($result['student']->middle_name)

                                                {{ $result['student']->middle_name }}

                                            @endif

                                            {{ $result['student']->last_name }}

                                        </td>


                                        {{-- ====================================================
                                            SUBJECT RESULTS
                                            ONLY MARK + GRADE
                                        ===================================================== --}}
                                        @foreach($streamSubjects as $subject)

                                            @php

                                                $subjectResult =
                                                    $result['subjects'][$subject->id]
                                                    ?? null;

                                            @endphp


                                            <td class="subject-result-cell">


                                                {{-- ABSENT --}}
                                                @if(
                                                    $subjectResult &&
                                                    !empty($subjectResult['absent'])
                                                )

                                                    <div class="result-mark absent">

                                                        ABS

                                                    </div>

                                                    <div class="result-detail">

                                                        —

                                                    </div>


                                                {{-- MARK --}}
                                                @elseif(
                                                    $subjectResult &&
                                                    $subjectResult['mark'] !== null
                                                )

                                                    <div class="result-mark">

                                                        {{ number_format(
                                                            $subjectResult['mark'],
                                                            0
                                                        ) }}

                                                    </div>


                                                    {{-- GRADE --}}
                                                    <div class="result-detail grade">

                                                        {{ $subjectResult['grade'] ?? '—' }}

                                                    </div>


                                                {{-- NO RESULT --}}
                                                @else

                                                    <div class="result-mark">

                                                        —

                                                    </div>

                                                    <div class="result-detail">

                                                        —

                                                    </div>

                                                @endif


                                            </td>

                                        @endforeach


                                        {{-- TOTAL --}}
                                        <td class="text-center fw-bold">

                                            {{ number_format(
                                                $result['total'],
                                                0
                                            ) }}

                                        </td>


                                        {{-- MEAN --}}
                                        <td class="text-center fw-bold">

                                            @if($result['mean'] !== null)

                                                {{ number_format(
                                                    $result['mean'],
                                                    2
                                                ) }}

                                            @else

                                                —

                                            @endif

                                        </td>


                                        {{-- OVERALL GRADE --}}
                                        <td class="text-center fw-bold">

                                            {{ $result['grade'] ?? '—' }}

                                        </td>

                                    </tr>

                                @empty

                                    <tr>

                                        <td
                                            colspan="{{ 6 + $streamSubjects->count() }}"
                                            class="text-center py-4">

                                            No student results available.

                                        </td>

                                    </tr>

                                @endforelse


                            </tbody>


                            {{-- ====================================================
                                STREAM SUBJECT AVERAGES
                            ===================================================== --}}
                            @if(count($cleanResults))

                                <tfoot>

                                    <tr class="stream-average-row">

                                        <th
                                            colspan="3"
                                            class="text-end">

                                            STREAM SUBJECT AVERAGE

                                        </th>


                                        @foreach($streamSubjects as $subject)

                                            @php

                                                $performance =
                                                    $cleanSubjectPerformance[$subject->id]
                                                    ?? null;

                                            @endphp


                                            <th class="text-center">

                                                @if(
                                                    $performance &&
                                                    $performance['average'] !== null
                                                )

                                                    {{ number_format(
                                                        $performance['average'],
                                                        2
                                                    ) }}

                                                @else

                                                    —

                                                @endif

                                            </th>

                                        @endforeach


                                        {{-- TOTAL --}}
                                        <th class="text-center">

                                            —

                                        </th>


                                        {{-- MEAN --}}
                                        <th class="text-center fw-bold">

                                            @if($cleanStreamAverage !== null)

                                                {{ number_format(
                                                    $cleanStreamAverage,
                                                    2
                                                ) }}

                                            @else

                                                —

                                            @endif

                                        </th>


                                        {{-- GRADE --}}
                                        <th class="text-center">

                                            —

                                        </th>

                                    </tr>

                                </tfoot>

                            @endif

                        </table>

                    </div>


                @else

                    <div class="alert alert-warning">

                        <i class="bi bi-exclamation-triangle me-1"></i>

                        No subjects are allocated to

                        <strong>
                            {{ $selectedClass->name }}
                        </strong>

                        —

                        <strong>
                            {{ $selectedStream->name }}
                        </strong>.

                        Please allocate subjects before generating this report.

                    </div>

                @endif


                {{-- ====================================================
                    SUBJECT PERFORMANCE
                ===================================================== --}}
                @if($streamSubjects->count())

                    <div class="section-title mt-5">

                        Subject Performance

                    </div>


                    <table class="table table-bordered performance-table">

                        <thead>

                            <tr>

                                <th>
                                    #
                                </th>

                                <th>
                                    Subject
                                </th>

                                <th>
                                    Students
                                </th>

                                <th>
                                    Average
                                </th>

                                <th>
                                    Highest
                                </th>

                                <th>
                                    Lowest
                                </th>

                            </tr>

                        </thead>


                        <tbody>


                            @foreach($streamSubjects as $index => $subject)

                                @php

                                    $performance =
                                        $cleanSubjectPerformance[$subject->id]
                                        ?? [
                                            'count' => 0,
                                            'average' => null,
                                            'highest' => null,
                                            'lowest' => null,
                                        ];

                                @endphp


                                <tr>

                                    <td class="text-center">

                                        {{ $index + 1 }}

                                    </td>


                                    <td class="fw-semibold">

                                        {{ $subject->name }}

                                    </td>


                                    <td class="text-center">

                                        {{ $performance['count'] }}

                                    </td>


                                    <td class="text-center fw-bold">

                                        @if($performance['average'] !== null)

                                            {{ number_format(
                                                $performance['average'],
                                                2
                                            ) }}

                                        @else

                                            —

                                        @endif

                                    </td>


                                    <td class="text-center">

                                        @if($performance['highest'] !== null)

                                            {{ number_format(
                                                $performance['highest'],
                                                0
                                            ) }}

                                        @else

                                            —

                                        @endif

                                    </td>


                                    <td class="text-center">

                                        @if($performance['lowest'] !== null)

                                            {{ number_format(
                                                $performance['lowest'],
                                                0
                                            ) }}

                                        @else

                                            —

                                        @endif

                                    </td>

                                </tr>

                            @endforeach


                        </tbody>

                    </table>

                @endif


                {{-- ====================================================
                    REPORT SUMMARY
                ===================================================== --}}
                <div class="summary-box">

                    <div>

                        <span>
                            Students
                        </span>

                        <strong>
                            {{ count($cleanResults) }}
                        </strong>

                    </div>


                    <div>

                        <span>
                            Subjects
                        </span>

                        <strong>
                            {{ $streamSubjects->count() }}
                        </strong>

                    </div>


                    <div>

                        <span>
                            Stream Average
                        </span>

                        <strong>

                            @if($cleanStreamAverage !== null)

                                {{ number_format(
                                    $cleanStreamAverage,
                                    2
                                ) }}

                            @else

                                —

                            @endif

                        </strong>

                    </div>

                </div>


                {{-- ====================================================
                    FOOTER
                ===================================================== --}}
                <div class="report-footer">

                    <div>

                        Generated by
                        <strong>ScholarERP</strong>

                    </div>


                    <div>

                        Printed:
                        {{ now()->format('d M Y, H:i') }}

                    </div>

                </div>


            </div>

        </div>

    @endif

</div>


{{-- ==================================================================
    STYLES
================================================================== --}}
<style>

/* ================================================================
   REPORT DOCUMENT
================================================================ */

.stream-report-document {

    background: #fff;

    padding: 30px;

    color: #111;

}


/* ================================================================
   SCHOOL HEADER
================================================================ */

.school-header {

    text-align: center;

    margin-bottom: 20px;

}


.school-logo {

    width: 75px;

    height: 75px;

    object-fit: contain;

    margin-bottom: 8px;

}


.school-name {

    font-size: 24px;

    font-weight: 800;

    margin: 0;

}


.school-motto {

    font-style: italic;

    margin-top: 3px;

}


.school-address {

    font-size: 12px;

    margin-top: 3px;

}


.report-title {
    font-size: 16px;
    font-weight: 800;
    margin-top: 10px;
    padding: 5px 0;
}


.report-subtitle {

    font-size: 12px;

    font-weight: 700;

    margin-top: 5px;

}


/* ================================================================
   INFORMATION GRID
================================================================ */

.report-info-grid {

    display: grid;

    grid-template-columns: repeat(4, 1fr);

    border: 1px solid #222;

    margin-bottom: 15px;

}


.report-info-grid > div {

    padding: 7px;

    border-right: 1px solid #222;

    border-bottom: 1px solid #222;

}


.report-info-grid span {

    display: block;

    font-size: 10px;

    color: #666;

}


.report-info-grid strong {

    display: block;

    margin-top: 2px;

    font-size: 12px;

}


/* ================================================================
   SUBJECT NOTICE
================================================================ */

.allocation-notice {

    background: #f7f7f7;

    padding: 8px;

    margin-bottom: 18px;

    font-size: 11px;

}


/* ================================================================
   SECTION TITLE
================================================================ */

.section-title {

    font-size: 15px;

    font-weight: 800;

    margin-bottom: 8px;

    padding-bottom: 5px;

}


/* ================================================================
   MAIN REPORT TABLE
================================================================ */

.report-table {

    width: 100%;

    border-collapse: collapse;

    font-size: 10px;

}


.report-table th {

    background: #eeeeee;

    text-align: center;

    vertical-align: middle;

    border: 1px solid #222;

    padding: 5px;

}


.report-table td {

    border: 1px solid #222;

    vertical-align: middle;

    padding: 4px;

}


/* ================================================================
   COLUMN SIZES
================================================================ */

.position-column {

    width: 42px;

}


.admission-column {

    width: 85px;

}


.student-column {

    min-width: 150px;

}


.subject-column {

    min-width: 75px;

}


.summary-column {

    width: 55px;

}


/* ================================================================
   SUBJECT HEADER
================================================================ */

.subject-header {

    font-weight: 800;

    text-transform: uppercase;

    font-size: 9px;

}


.subject-subheader {

    font-size: 7px;

    font-weight: normal;

    margin-top: 3px;

}


/* ================================================================
   STUDENT
================================================================ */

.admission-number {

    text-align: center;

    font-weight: 600;

    white-space: nowrap;

}


.student-name {

    font-weight: 600;

    white-space: nowrap;

}


/* ================================================================
   SUBJECT RESULT
================================================================ */

.subject-result-cell {

    text-align: center;

    min-width: 75px;

}


.result-mark {

    font-weight: 800;

    font-size: 12px;

}


.result-mark.absent {

    font-weight: 800;

}


.result-detail {

    display: block;

    margin-top: 2px;

    font-size: 9px;

}


.result-detail.grade {

    font-weight: 800;

}


/* ================================================================
   STREAM AVERAGE
================================================================ */

.stream-average-row th {

    background: #e8e8e8;

    font-weight: 800;

}


/* ================================================================
   SUBJECT PERFORMANCE
================================================================ */

.performance-table {

    width: 100%;

    font-size: 10px;

}


.performance-table th {

    background: #eeeeee;

    text-align: center;

    border: 1px solid #222;

}


.performance-table td {

    border: 1px solid #222;

    padding: 5px;

}


/* ================================================================
   SUMMARY
================================================================ */

.summary-box {

    display: grid;

    grid-template-columns: repeat(3, 1fr);

    border: 1px solid #222;

    margin-top: 20px;

}


.summary-box > div {

    text-align: center;

    padding: 8px;

    border-right: 1px solid #222;

}


.summary-box span {

    display: block;

    font-size: 10px;

    color: #666;

}


.summary-box strong {

    display: block;

    font-size: 14px;

    margin-top: 2px;

}


/* ================================================================
   FOOTER
================================================================ */

.report-footer {

    display: flex;

    justify-content: space-between;

    border-top: 1px solid #999;

    margin-top: 25px;

    padding-top: 8px;

    font-size: 9px;

}


/* ================================================================
   RESPONSIVE
================================================================ */

@media (max-width: 768px) {

    .report-info-grid {

        grid-template-columns: repeat(2, 1fr);

    }


    .stream-report-document {

        padding: 15px;

    }

}


/* ================================================================
   PRINT
================================================================ */

@media print {

    @page {

        size: A4 landscape;

        margin: 8mm;

    }


    html,
    body {

        width: 100%;

        height: auto !important;

        margin: 0 !important;

        padding: 0 !important;

        background: #fff !important;

    }


    .no-print {

        display: none !important;

    }


    nav,
    header,
    footer,
    aside,
    .sidebar,
    .navbar {

        display: none !important;

    }


    #streamReport {

        position: static !important;

        display: block !important;

        width: 100% !important;

        max-width: none !important;

        height: auto !important;

        min-height: 0 !important;

        margin: 0 !important;

        padding: 0 !important;

        overflow: visible !important;

        visibility: visible !important;

    }


    #streamReport * {

        visibility: visible !important;

    }


    .table-responsive {

        display: block !important;

        width: 100% !important;

        max-width: none !important;

        height: auto !important;

        overflow: visible !important;

    }


    /* ============================================================
       MAIN TABLE
    ============================================================ */

    .report-table {

        width: 100% !important;

        max-width: none !important;

        border-collapse: collapse !important;

        table-layout: auto !important;

        font-size: 7px !important;

        page-break-inside: auto !important;

    }


    .report-table thead {

        display: table-header-group !important;

    }


    .report-table tbody {

        display: table-row-group !important;

    }


    .report-table tr {

        page-break-inside: avoid !important;

        break-inside: avoid !important;

    }


    .report-table th,
    .report-table td {

        padding: 2px !important;

        line-height: 1.15 !important;

        vertical-align: middle !important;

        overflow: visible !important;

    }


    /* ============================================================
       SUBJECT COLUMNS
    ============================================================ */

    .subject-column {

        min-width: 45px !important;

        width: auto !important;

    }


    .subject-result-cell {

        min-width: 45px !important;

        width: auto !important;

    }


    /* ============================================================
       STUDENT COLUMNS
    ============================================================ */

    .position-column {

        width: 30px !important;

    }


    .admission-column {

        width: 65px !important;

    }


    .student-column {

        width: 105px !important;

        min-width: 95px !important;

    }


    /* ============================================================
       SUMMARY COLUMNS
    ============================================================ */

    .summary-column {

        width: 45px !important;

    }


    /* ============================================================
       SCHOOL HEADER
    ============================================================ */

    .school-logo {

        width: 55px !important;

        height: 55px !important;

    }


    .school-name {

        font-size: 16px !important;

    }


    .school-motto,
    .school-address {

        font-size: 7px !important;

    }


    .report-title {

        font-size: 10px !important;

        padding: 4px !important;

    }


    .report-subtitle {

        font-size: 8px !important;

    }


    /* ============================================================
       INFORMATION GRID
    ============================================================ */

    .report-info-grid {

        font-size: 7px !important;

        margin-bottom: 8px !important;

    }


    .report-info-grid > div {

        padding: 4px !important;

    }


    .report-info-grid span {

        font-size: 6px !important;

    }


    .report-info-grid strong {

        font-size: 7px !important;

    }



    /* ============================================================
       SECTION TITLES
    ============================================================ */

    .section-title {

        font-size: 9px !important;

        margin-top: 8px !important;

        margin-bottom: 4px !important;

    }


    /* ============================================================
       RESULTS
    ============================================================ */

    .result-mark {

        font-size: 7px !important;

    }


    .result-detail {

        font-size: 6px !important;

        margin-top: 1px !important;

    }


    .subject-header {

        font-size: 7px !important;

    }


    .subject-subheader {

        font-size: 5px !important;

    }


    /* ============================================================
       SUBJECT PERFORMANCE
    ============================================================ */

    .performance-table {

        width: 100% !important;

        font-size: 7px !important;

        page-break-inside: auto !important;

    }


    .performance-table tr {

        page-break-inside: avoid !important;

        break-inside: avoid !important;

    }


    .performance-table th,
    .performance-table td {

        padding: 3px !important;

    }


    /* ============================================================
       SUMMARY
    ============================================================ */

    .summary-box {

        margin-top: 10px !important;

    }


    .summary-box > div {

        padding: 5px !important;

    }


    .summary-box span {

        font-size: 6px !important;

    }


    .summary-box strong {

        font-size: 8px !important;

    }


    /* ============================================================
       FOOTER
    ============================================================ */

    .report-footer {

        font-size: 6px !important;

        margin-top: 10px !important;

        page-break-inside: avoid !important;

    }

}

</style>


{{-- ==================================================================
    JAVASCRIPT
================================================================== --}}
<script>

document.addEventListener(
    'DOMContentLoaded',
    function() {

        const examSelect =
            document.getElementById('exam_id');


        const classSelect =
            document.getElementById('school_class_id');


        const streamSelect =
            document.getElementById('stream_id');


        const generateButton =
            document.getElementById(
                'generateReportBtn'
            );


        if(
            !examSelect ||
            !classSelect ||
            !streamSelect
        ) {

            return;

        }


        /*
        |--------------------------------------------------------------------------
        | Previously selected stream
        |--------------------------------------------------------------------------
        */

        const previousStreamId =
            @json(
                old(
                    'stream_id',
                    optional($selectedStream)->id
                )
            );


        /*
        |--------------------------------------------------------------------------
        | LOAD STREAMS
        |--------------------------------------------------------------------------
        */

        function loadStreams(
            classId,
            selectedStreamId = null
        ) {

            if(!classId) {

                streamSelect.innerHTML = `
                    <option value="">
                        Select Class First
                    </option>
                `;

                streamSelect.disabled = true;

                return;

            }


            streamSelect.disabled = true;


            streamSelect.innerHTML = `
                <option value="">
                    Loading streams...
                </option>
            `;


            fetch(
                "{{ url('/stream-reports/streams') }}/"
                + classId,
                {
                    method: 'GET',

                    headers: {
                        'Accept': 'application/json',

                        'X-Requested-With':
                            'XMLHttpRequest'
                    }
                }
            )


            .then(response => {

                if(!response.ok) {

                    throw new Error(
                        'Unable to load streams.'
                    );

                }

                return response.json();

            })


            .then(streams => {

                streamSelect.innerHTML = `
                    <option value="">
                        Select Stream
                    </option>
                `;


                if(
                    !Array.isArray(streams) ||
                    streams.length === 0
                ) {

                    streamSelect.innerHTML = `
                        <option value="">
                            No streams found
                        </option>
                    `;

                    streamSelect.disabled = true;

                    return;

                }


                streams.forEach(
                    stream => {

                        const option =
                            document.createElement(
                                'option'
                            );


                        option.value =
                            stream.id;


                        option.textContent =
                            stream.name;


                        if(
                            selectedStreamId &&
                            String(selectedStreamId) ===
                            String(stream.id)
                        ) {

                            option.selected = true;

                        }


                        streamSelect.appendChild(
                            option
                        );

                    }
                );


                streamSelect.disabled = false;

            })


            .catch(error => {

                console.error(error);


                streamSelect.innerHTML = `
                    <option value="">
                        Unable to load streams
                    </option>
                `;


                streamSelect.disabled = true;

            });

        }


        /*
        |--------------------------------------------------------------------------
        | SYNC CLASS WITH EXAM
        |--------------------------------------------------------------------------
        */

        function syncClassWithExam() {

            const selectedOption =
                examSelect.options[
                    examSelect.selectedIndex
                ];


            if(
                !selectedOption ||
                !selectedOption.value
            ) {

                return;

            }


            const examClassId =
                selectedOption.dataset.classId;


            if(!examClassId) {

                return;

            }


            classSelect.value =
                examClassId;


            loadStreams(
                examClassId,
                previousStreamId
            );

        }


        /*
        |--------------------------------------------------------------------------
        | EXAMINATION CHANGED
        |--------------------------------------------------------------------------
        */

        examSelect.addEventListener(
            'change',
            function() {

                syncClassWithExam();

            }
        );


        /*
        |--------------------------------------------------------------------------
        | CLASS CHANGED
        |--------------------------------------------------------------------------
        */

        classSelect.addEventListener(
            'change',
            function() {

                const selectedClassId =
                    this.value;


                const selectedExam =
                    examSelect.options[
                        examSelect.selectedIndex
                    ];


                if(
                    selectedExam &&
                    selectedExam.value
                ) {

                    const examClassId =
                        selectedExam.dataset.classId;


                    if(
                        examClassId &&
                        String(examClassId) !==
                        String(selectedClassId)
                    ) {

                        examSelect.value = '';

                    }

                }


                loadStreams(
                    selectedClassId
                );

            }
        );


        /*
        |--------------------------------------------------------------------------
        | FORM SUBMISSION
        |--------------------------------------------------------------------------
        */

        const form =
            document.getElementById(
                'streamReportForm'
            );


        if(form) {

            form.addEventListener(
                'submit',
                function(event) {

                    const selectedExam =
                        examSelect.options[
                            examSelect.selectedIndex
                        ];


                    const selectedClassId =
                        classSelect.value;


                    const selectedStreamId =
                        streamSelect.value;


                    if(
                        !selectedExam ||
                        !selectedExam.value
                    ) {

                        event.preventDefault();

                        alert(
                            'Please select an examination.'
                        );

                        return;

                    }


                    if(!selectedClassId) {

                        event.preventDefault();

                        alert(
                            'Please select a class.'
                        );

                        return;

                    }


                    if(!selectedStreamId) {

                        event.preventDefault();

                        alert(
                            'Please select a stream.'
                        );

                        return;

                    }


                    const examClassId =
                        selectedExam.dataset.classId;


                    if(
                        examClassId &&
                        String(examClassId) !==
                        String(selectedClassId)
                    ) {

                        event.preventDefault();

                        alert(
                            'The selected examination does not belong to the selected class.'
                        );

                        return;

                    }


                    if(generateButton) {

                        generateButton.disabled =
                            true;


                        generateButton.innerHTML = `
                            <span
                                class="spinner-border spinner-border-sm me-1"
                                role="status">
                            </span>

                            Generating Report...
                        `;

                    }

                }
            );

        }


        /*
        |--------------------------------------------------------------------------
        | INITIAL LOAD
        |--------------------------------------------------------------------------
        */

        if(examSelect.value) {

            syncClassWithExam();

        }
        else if(classSelect.value) {

            loadStreams(
                classSelect.value,
                previousStreamId
            );

        }

    }
);

</script>

@endsection