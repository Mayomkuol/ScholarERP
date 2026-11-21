@extends('layouts.app')

@section('content')

<div class="container-fluid py-4">

    {{-- ========================================================= --}}
    {{-- PAGE HEADER --}}
    {{-- ========================================================= --}}

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h3 class="fw-bold mb-1">
                <i class="bi bi-journal-check me-2"></i>
                Examination Marks
            </h3>

            <p class="text-muted mb-0">
                View, manage and print examination results.
            </p>
        </div>

        <a href="{{ route('exam-marks.create') }}"
           class="btn btn-primary">

            <i class="bi bi-plus-circle me-1"></i>
            Enter Examination Marks

        </a>

    </div>


    {{-- ========================================================= --}}
    {{-- SUCCESS MESSAGE --}}
    {{-- ========================================================= --}}

    @if(session('success'))

        <div class="alert alert-success alert-dismissible fade show">

            <i class="bi bi-check-circle me-2"></i>

            {{ session('success') }}

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert">
            </button>

        </div>

    @endif


    {{-- ========================================================= --}}
    {{-- ERROR MESSAGE --}}
    {{-- ========================================================= --}}

    @if($errors->any())

        <div class="alert alert-danger">

            <strong>
                <i class="bi bi-exclamation-triangle me-1"></i>
                Please correct the following:
            </strong>

            <ul class="mb-0 mt-2">

                @foreach($errors->all() as $error)

                    <li>{{ $error }}</li>

                @endforeach

            </ul>

        </div>

    @endif


    {{-- ========================================================= --}}
    {{-- FILTER CARD --}}
    {{-- ========================================================= --}}

    <div class="card shadow-sm border-0 mb-4">

        <div class="card-header bg-white py-3">

            <h5 class="fw-bold mb-0">

                <i class="bi bi-funnel me-2"></i>

                Filter Examination Marks

            </h5>

        </div>

        <div class="card-body">

            <form method="GET"
                  action="{{ route('exam-marks.index') }}">

                <div class="row g-3">

                    {{-- CLASS --}}
                    <div class="col-md-4">

                        <label class="form-label fw-semibold">
                            School Class
                        </label>

                        <select name="school_class_id"
                                class="form-select">

                            <option value="">
                                All Classes
                            </option>

                            @foreach($schoolClasses as $schoolClass)

                                <option value="{{ $schoolClass->id }}"
                                    @selected(
                                        request('school_class_id') == $schoolClass->id
                                    )>

                                    {{ $schoolClass->name }}

                                </option>

                            @endforeach

                        </select>

                    </div>


                    {{-- EXAMINATION --}}
                    <div class="col-md-4">

                        <label class="form-label fw-semibold">
                            Examination
                        </label>

                        <select name="exam_id"
                                class="form-select">

                            <option value="">
                                All Examinations
                            </option>

                            @foreach($exams as $exam)

                                <option value="{{ $exam->id }}"
                                    @selected(
                                        request('exam_id') == $exam->id
                                    )>

                                    {{ $exam->name }}

                                </option>

                            @endforeach

                        </select>

                    </div>


                    {{-- SUBJECT --}}
                    <div class="col-md-4">

                        <label class="form-label fw-semibold">
                            Subject
                        </label>

                        <select name="subject_id"
                                class="form-select">

                            <option value="">
                                All Subjects
                            </option>

                            @foreach($subjects as $subject)

                                <option value="{{ $subject->id }}"
                                    @selected(
                                        request('subject_id') == $subject->id
                                    )>

                                    {{ $subject->name }}

                                </option>

                            @endforeach

                        </select>

                    </div>

                </div>


                {{-- FILTER BUTTONS --}}
                <div class="d-flex gap-2 mt-4">

                    <button type="submit"
                            class="btn btn-primary">

                        <i class="bi bi-search me-1"></i>
                        Load Results

                    </button>

                    <a href="{{ route('exam-marks.index') }}"
                       class="btn btn-outline-secondary">

                        <i class="bi bi-arrow-clockwise me-1"></i>
                        Reset

                    </a>

                    <a href="{{ route('exam-marks.create') }}"
                       class="btn btn-success ms-auto">

                        <i class="bi bi-pencil-square me-1"></i>
                        Enter Marks

                    </a>

                </div>

            </form>

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- GROUP MARKS BY CLASS --}}
    {{-- ========================================================= --}}

    @php

        $marksByClass = $examMarks
            ->filter(function ($mark) {

                return $mark->student &&
                       $mark->student->schoolClass;

            })
            ->groupBy(function ($mark) {

                return $mark->student->schoolClass->id;

            });

    @endphp


    {{-- ========================================================= --}}
    {{-- RESULTS --}}
    {{-- ========================================================= --}}

    @if($marksByClass->isNotEmpty())

        @foreach($schoolClasses as $schoolClass)

            @php

                $classMarks = $marksByClass->get(
                    $schoolClass->id,
                    collect()
                );

            @endphp


            @if($classMarks->isNotEmpty())


                {{-- ================================================= --}}
                {{-- CLASS RESULT CARD --}}
                {{-- ================================================= --}}

                <div class="card shadow-sm border-0 mb-5"
                     id="class-results-{{ $schoolClass->id }}">


                    {{-- CLASS HEADER --}}
                    <div class="card-header bg-white border-bottom py-3">

                        <div class="d-flex justify-content-between align-items-center">

                            <div>

                                <h5 class="fw-bold mb-1">

                                    <i class="bi bi-mortarboard-fill me-2"></i>

                                    {{ $schoolClass->name }}

                                </h5>

                                <small class="text-muted">

                                    {{ $classMarks
                                        ->pluck('student_id')
                                        ->unique()
                                        ->count()
                                    }}

                                    student(s)

                                    &nbsp;·&nbsp;

                                    {{ $classMarks->count() }}

                                    mark record(s)

                                </small>

                            </div>


                            {{-- PRINT --}}
                            <button type="button"
                                    class="btn btn-outline-dark"
                                    onclick="printClassResults('{{ $schoolClass->id }}')">

                                <i class="bi bi-printer me-1"></i>
                                Print Results

                            </button>

                        </div>

                    </div>


                    {{-- ================================================= --}}
                    {{-- PRINT AREA --}}
                    {{-- ================================================= --}}

                    <div class="card-body"
                         id="print-area-{{ $schoolClass->id }}">


                        {{-- PRINT HEADER --}}
                        <div class="print-header text-center mb-4">

                            <h2 class="fw-bold mb-1">
                                EXAMINATION RESULTS
                            </h2>

                            <h4 class="mb-2">
                                {{ $schoolClass->name }}
                            </h4>

                            @if(request('exam_id'))

                                @php
                                    $printExam = $exams->firstWhere(
                                        'id',
                                        request('exam_id')
                                    );
                                @endphp

                                @if($printExam)

                                    <p class="mb-1">
                                        Examination:
                                        <strong>
                                            {{ $printExam->name }}
                                        </strong>
                                    </p>

                                @endif

                            @endif

                            <p class="small mb-0">
                                Printed:
                                {{ now()->format('d M Y H:i') }}
                            </p>

                        </div>


                        {{-- ================================================= --}}
                        {{-- SUMMARY --}}
                        {{-- ================================================= --}}

                        <div class="row g-3 mb-4">

                            {{-- CLASS --}}
                            <div class="col-md-3">

                                <div class="border rounded p-3 h-100">

                                    <small class="text-muted">
                                        Class
                                    </small>

                                    <h5 class="fw-bold mb-0">

                                        {{ $schoolClass->name }}

                                    </h5>

                                </div>

                            </div>


                            {{-- STUDENTS --}}
                            <div class="col-md-3">

                                <div class="border rounded p-3 h-100">

                                    <small class="text-muted">
                                        Students
                                    </small>

                                    <h5 class="fw-bold mb-0">

                                        {{ $classMarks
                                            ->pluck('student_id')
                                            ->unique()
                                            ->count()
                                        }}

                                    </h5>

                                </div>

                            </div>


                            {{-- EXAMINATIONS --}}
                            <div class="col-md-3">

                                <div class="border rounded p-3 h-100">

                                    <small class="text-muted">
                                        Examinations
                                    </small>

                                    <h5 class="fw-bold mb-0">

                                        {{ $classMarks
                                            ->pluck('exam_id')
                                            ->unique()
                                            ->count()
                                        }}

                                    </h5>

                                </div>

                            </div>


                            {{-- SUBJECTS --}}
                            <div class="col-md-3">

                                <div class="border rounded p-3 h-100">

                                    <small class="text-muted">
                                        Subjects
                                    </small>

                                    <h5 class="fw-bold mb-0">

                                        {{ $classMarks
                                            ->pluck('subject_id')
                                            ->unique()
                                            ->count()
                                        }}

                                    </h5>

                                </div>

                            </div>

                        </div>


                        {{-- ================================================= --}}
                        {{-- RESULTS TABLE --}}
                        {{-- ================================================= --}}

                        <div class="table-responsive">

                            <table class="table table-bordered table-hover align-middle">

                                <thead class="table-light">

                                    <tr>

                                        <th width="50">
                                            #
                                        </th>

                                        <th>
                                            Admission No.
                                        </th>

                                        <th>
                                            Student
                                        </th>

                                        <th>
                                            Stream
                                        </th>

                                        <th>
                                            Examination
                                        </th>

                                        <th>
                                            Subject
                                        </th>

                                        <th class="text-center">
                                            Marks
                                        </th>

                                        <th class="text-center">
                                            Grade
                                        </th>

                                        <th class="text-center">
                                            Points
                                        </th>

                                        <th>
                                            Remarks
                                        </th>

                                    </tr>

                                </thead>


                                <tbody>

                                    @foreach($classMarks as $index => $mark)

                                        @php

                                            $student = $mark->student;

                                            $stream = $student?->stream;

                                        @endphp

                                        <tr>

                                            {{-- NUMBER --}}
                                            <td>
                                                {{ $index + 1 }}
                                            </td>


                                           {{-- ADMISSION NUMBER --}}
<td class="fw-semibold">

    {{ $student?->admission_no ?? '—' }}

</td>

                                            {{-- STUDENT --}}
                                            <td class="fw-semibold">

                                                {{ trim(
                                                    ($student?->first_name ?? '') . ' ' .
                                                    ($student?->middle_name ?? '') . ' ' .
                                                    ($student?->last_name ?? '')
                                                ) ?: '—' }}

                                            </td>


                                            {{-- STREAM --}}
                                            <td>

                                                {{ $stream?->name ?? '—' }}

                                            </td>


                                            {{-- EXAMINATION --}}
                                            <td>

                                                {{ $mark->exam?->name ?? '—' }}

                                            </td>


                                            {{-- SUBJECT --}}
                                            <td>

                                                {{ $mark->subject?->name ?? '—' }}

                                            </td>


                                            {{-- MARKS --}}
                                            <td class="text-center fw-bold">

                                                {{ $mark->marks !== null
                                                    ? number_format((float) $mark->marks, 2)
                                                    : '—'
                                                }}

                                            </td>


                                            {{-- GRADE --}}
                                            <td class="text-center fw-semibold">

                                                {{ $mark->grade ?? '—' }}

                                            </td>


                                            {{-- POINTS --}}
                                            <td class="text-center">

                                                {{ $mark->points !== null
                                                    ? number_format((float) $mark->points, 2)
                                                    : '—'
                                                }}

                                            </td>


                                            {{-- REMARKS --}}
                                            <td>

                                                {{ $mark->remarks ?? '—' }}

                                            </td>

                                        </tr>

                                    @endforeach

                                </tbody>

                            </table>

                        </div>


                        {{-- ================================================= --}}
                        {{-- FOOTER --}}
                        {{-- ================================================= --}}

                        <div class="mt-3 text-muted small">

                            Showing

                            <strong>
                                {{ $classMarks->count() }}
                            </strong>

                            mark record(s) for

                            <strong>
                                {{ $schoolClass->name }}
                            </strong>.

                        </div>


                        {{-- ================================================= --}}
                        {{-- SIGNATURES --}}
                        {{-- ================================================= --}}

                        <div class="signature-section mt-5">

                            <div class="row">

                                <div class="col-4">

                                    <div class="border-top pt-2">
                                        Class Teacher
                                    </div>

                                </div>

                                <div class="col-4">

                                    <div class="border-top pt-2">
                                        Head of Department
                                    </div>

                                </div>

                                <div class="col-4">

                                    <div class="border-top pt-2">
                                        Principal / Head Teacher
                                    </div>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>

            @endif

        @endforeach


    @else

        {{-- ========================================================= --}}
        {{-- NO RESULTS --}}
        {{-- ========================================================= --}}

        <div class="card shadow-sm border-0">

            <div class="card-body text-center py-5">

                <i class="bi bi-clipboard-x fs-1 text-muted"></i>

                <h5 class="mt-3">
                    No examination marks found
                </h5>

                <p class="text-muted mb-4">

                    No marks match the selected filters.

                </p>

                <a href="{{ route('exam-marks.create') }}"
                   class="btn btn-primary">

                    <i class="bi bi-plus-circle me-1"></i>

                    Enter Examination Marks

                </a>

            </div>

        </div>

    @endif

</div>


{{-- ========================================================= --}}
{{-- PRINT JAVASCRIPT --}}
{{-- ========================================================= --}}

<script>

function printClassResults(classId)
{
    const printArea =
        document.getElementById('print-area-' + classId);

    if (!printArea) {

        alert('Print area not found.');

        return;
    }


    const printWindow = window.open(
        '',
        '_blank',
        'width=1200,height=800'
    );


    if (!printWindow) {

        alert(
            'Please allow pop-ups in your browser to print results.'
        );

        return;
    }


    printWindow.document.write(`

        <!DOCTYPE html>

        <html>

        <head>

            <title>
                Examination Results
            </title>

            <meta charset="UTF-8">

            <style>

                * {
                    box-sizing: border-box;
                }

                body {

                    font-family:
                        Arial,
                        Helvetica,
                        sans-serif;

                    padding: 20px;

                    color: #000;

                    background: #fff;
                }

                h1,
                h2,
                h3,
                h4,
                h5 {

                    margin-top: 0;

                }

                .text-center {
                    text-align: center;
                }

                .fw-bold {
                    font-weight: 700;
                }

                .fw-semibold {
                    font-weight: 600;
                }

                .mb-0 {
                    margin-bottom: 0;
                }

                .mb-1 {
                    margin-bottom: 5px;
                }

                .mb-2 {
                    margin-bottom: 10px;
                }

                .mb-4 {
                    margin-bottom: 20px;
                }

                .mt-3 {
                    margin-top: 15px;
                }

                .mt-5 {
                    margin-top: 40px;
                }

                .small {
                    font-size: 11px;
                }

                .text-muted {
                    color: #555;
                }

                .border {
                    border: 1px solid #ccc;
                }

                .border-top {
                    border-top: 1px solid #000;
                }

                .rounded {
                    border-radius: 5px;
                }

                .p-3 {
                    padding: 15px;
                }

                .pt-2 {
                    padding-top: 10px;
                }

                .row {

                    display: flex;

                    gap: 15px;

                }

                .col-md-3 {

                    width: 25%;

                }

                .col-4 {

                    width: 33.333%;

                }

                table {

                    width: 100%;

                    border-collapse: collapse;

                    margin-top: 15px;

                }

                th,
                td {

                    border: 1px solid #000;

                    padding: 7px;

                    font-size: 10px;

                }

                th {

                    background: #f2f2f2;

                    font-weight: bold;

                }

                .signature-section {

                    margin-top: 60px;

                }

                @page {

                    size: A4 landscape;

                    margin: 10mm;

                }

                @media print {

                    body {

                        padding: 0;

                    }

                }

            </style>

        </head>


        <body>

            ${printArea.innerHTML}

        </body>

        </html>

    `);


    printWindow.document.close();

    printWindow.focus();


    setTimeout(function () {

        printWindow.print();

        printWindow.close();

    }, 500);

}

</script>


{{-- ========================================================= --}}
{{-- PAGE STYLING --}}
{{-- ========================================================= --}}

<style>

.print-header,
.signature-section {

    display: none;

}

@media print {

    .print-header,
    .signature-section {

        display: block;

    }

}

</style>

@endsection