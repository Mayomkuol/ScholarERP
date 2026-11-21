@extends('layouts.app')

@section('content')

<div class="container py-3">

    {{-- =========================================================
        PAGE HEADER
    ========================================================== --}}
    <div class="d-flex justify-content-between align-items-center mb-3 no-print">

        <div>
            <h4 class="fw-bold mb-1">
                Student Report Card
            </h4>

            <small class="text-muted">
                Generate an individual student's examination report card.
            </small>
        </div>

    </div>


    {{-- =========================================================
        FILTER CARD
    ========================================================== --}}
    <div class="card border-0 shadow-sm mb-3 no-print report-filter-card">

        <div class="card-header bg-white border-bottom py-2 px-3">

            <div class="d-flex align-items-center gap-2">

                <i class="bi bi-person-vcard text-primary"></i>

                <span class="fw-semibold">
                    Select Student
                </span>

            </div>

        </div>


        <div class="card-body p-3">

            <form
                method="POST"
                action="{{ route('student-report-card.load') }}"
                id="reportCardForm"
            >

                @csrf

                <div class="row g-3 align-items-end">

                    {{-- =================================================
                        EXAMINATION
                    ================================================== --}}
                    <div class="col-lg-4 col-md-6">

                        <label
                            for="exam_id"
                            class="form-label small fw-semibold mb-1"
                        >
                            Examination <span class="text-danger">*</span>
                        </label>

                        <select
                            name="exam_id"
                            id="exam_id"
                            class="form-select form-select-sm"
                            required
                        >

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
                                    )
                                >

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

                        <div class="form-text small">
                            Select an examination. Its class will be selected automatically.
                        </div>

                    </div>


                    {{-- =================================================
                        SCHOOL CLASS
                    ================================================== --}}
                    <div class="col-lg-4 col-md-6">

                        <label
                            for="school_class_id"
                            class="form-label small fw-semibold mb-1"
                        >
                            School Class <span class="text-danger">*</span>
                        </label>

                        <select
                            name="school_class_id"
                            id="school_class_id"
                            class="form-select form-select-sm"
                            required
                        >

                            <option value="">
                                Select Examination First
                            </option>

                            @foreach($classes as $class)

                                <option
                                    value="{{ $class->id }}"
                                    @selected(
                                        old(
                                            'school_class_id',
                                            optional($selectedClass)->id
                                        ) == $class->id
                                    )
                                >
                                    {{ $class->name }}
                                </option>

                            @endforeach

                        </select>

                        <div class="form-text small">
                            The selected class must belong to the examination.
                        </div>

                    </div>


                    {{-- =================================================
                        STUDENT
                    ================================================== --}}
                    <div class="col-lg-4 col-md-12">

                        <label
                            for="student_id"
                            class="form-label small fw-semibold mb-1"
                        >
                            Student <span class="text-danger">*</span>
                        </label>

                        <select
                            name="student_id"
                            id="student_id"
                            class="form-select form-select-sm"
                            required
                            disabled
                        >

                            <option value="">
                                Select Examination First
                            </option>

                        </select>

                    </div>

                </div>


                {{-- =================================================
                    STATUS MESSAGE
                ================================================== --}}
                <div
                    id="selectionInfo"
                    class="alert alert-light border mt-3 mb-0 py-2 px-3 small d-none"
                >

                    <i class="bi bi-info-circle me-1"></i>

                    <span id="selectionInfoText"></span>

                </div>


                {{-- =================================================
                    ACTIONS
                ================================================== --}}
                <div class="d-flex align-items-center gap-2 mt-3">

                    <button
                        type="submit"
                        id="generateButton"
                        class="btn btn-primary btn-sm"
                        disabled
                    >

                        <i class="bi bi-file-earmark-text me-1"></i>

                        Load Report Card

                    </button>


                    <button
                        type="button"
                        id="resetButton"
                        class="btn btn-outline-secondary btn-sm"
                    >

                        <i class="bi bi-arrow-counterclockwise me-1"></i>

                        Reset

                    </button>


                    <span
                        id="studentLoading"
                        class="text-muted small d-none"
                    >

                        <span
                            class="spinner-border spinner-border-sm me-1"
                            role="status"
                            aria-hidden="true"
                        ></span>

                        Loading students...

                    </span>

                </div>

            </form>

        </div>

    </div>


    {{-- =========================================================
        SESSION ERROR
    ========================================================== --}}
    @if(session('error'))

        <div class="alert alert-danger alert-dismissible fade show no-print">

            <i class="bi bi-exclamation-triangle me-1"></i>

            {{ session('error') }}

            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert"
            ></button>

        </div>

    @endif


    {{-- =========================================================
        VALIDATION ERRORS
    ========================================================== --}}
    @if($errors->any())

        <div class="alert alert-danger no-print">

            <div class="fw-bold mb-2">

                <i class="bi bi-exclamation-triangle me-1"></i>

                Please correct the following:

            </div>

            <ul class="mb-0">

                @foreach($errors->all() as $error)

                    <li>
                        {{ $error }}
                    </li>

                @endforeach

            </ul>

        </div>

    @endif


    {{-- =========================================================
        REPORT CARD
    ========================================================== --}}
    @if($report)

        <div class="card border-0 shadow-sm">

            {{-- =====================================================
                SCREEN ACTION BAR
            ====================================================== --}}
            <div class="card-header bg-white border-bottom no-print py-2 px-3">

                <div class="d-flex justify-content-between align-items-center">

                    <div>

                        <div class="fw-bold">
                            {{ $report['student']->full_name }}
                        </div>

                        <small class="text-muted">

                            {{
                                $report['student']->admission_number
                                ?: $report['student']->admission_no
                                ?: 'No Admission No'
                            }}

                        </small>

                    </div>


                    <button
                        type="button"
                        class="btn btn-dark btn-sm"
                        onclick="window.print()"
                    >

                        <i class="bi bi-printer me-1"></i>

                        Print

                    </button>

                </div>

            </div>


            {{-- =====================================================
                PRINTABLE REPORT
            ====================================================== --}}
            <div id="reportCard" class="report-document">

                {{-- =================================================
                    SCHOOL HEADER
                ================================================== --}}
                <div class="official-school-header">

                    {{-- LOGO --}}
                    <div class="official-logo-area">

                        @if(!empty($school?->logo))

                            @php

                                $logoPath = $school->logo;

                                if (
                                    \Illuminate\Support\Str::startsWith(
                                        $logoPath,
                                        ['http://', 'https://']
                                    )
                                ) {

                                    $logoUrl = $logoPath;

                                } else {

                                    $logoUrl = asset(
                                        'storage/' . ltrim($logoPath, '/')
                                    );

                                }

                            @endphp

                            <img
                                src="{{ $logoUrl }}"
                                alt="{{ $school->name ?? 'School Logo' }}"
                                class="official-school-logo"
                            >

                        @else

                            <div class="official-logo-placeholder">

                                <i class="bi bi-building"></i>

                            </div>

                        @endif

                    </div>


                    {{-- SCHOOL INFORMATION --}}
                    <div class="official-school-information">

                        <div class="official-school-name">
                            {{ $school->name ?? 'SCHOOL NAME' }}
                        </div>


                        @if(!empty($school?->motto))

                            <div class="official-school-motto">
                                "{{ $school->motto }}"
                            </div>

                        @endif


                        @if(!empty($school?->address))

                            <div class="official-school-address">
                                {{ $school->address }}
                            </div>

                        @endif


                        @if(!empty($school?->country))

                            <div class="official-school-country">
                                {{ $school->country }}
                            </div>

                        @endif


                        <div class="official-school-contact">

                            @if(!empty($school?->phone))

                                <span>
                                    <strong>Tel:</strong>
                                    {{ $school->phone }}
                                </span>

                            @endif


                            @if(
                                !empty($school?->phone) &&
                                !empty($school?->email)
                            )

                                <span class="header-separator">
                                    |
                                </span>

                            @endif


                            @if(!empty($school?->email))

                                <span>
                                    <strong>Email:</strong>
                                    {{ $school->email }}
                                </span>

                            @endif

                        </div>


                        @if(
                            !empty($school?->code) ||
                            !empty($school?->registration_no)
                        )

                            <div class="official-school-identification">

                                @if(!empty($school?->code))

                                    <span>
                                        <strong>School Code:</strong>
                                        {{ $school->code }}
                                    </span>

                                @endif


                                @if(
                                    !empty($school?->code) &&
                                    !empty($school?->registration_no)
                                )

                                    <span class="header-separator">
                                        |
                                    </span>

                                @endif


                                @if(!empty($school?->registration_no))

                                    <span>
                                        <strong>Reg. No:</strong>
                                        {{ $school->registration_no }}
                                    </span>

                                @endif

                            </div>

                        @endif

                    </div>


                    {{-- OFFICIAL BADGE --}}
                    <div class="official-document-area">

                        <div class="official-document-badge">

                            <div>OFFICIAL</div>
                            <div>EXAMINATION</div>
                            <div>DOCUMENT</div>

                        </div>

                    </div>

                </div>


                {{-- =================================================
                    TITLE
                ================================================== --}}
                <div class="report-title">

                    <h2>
                        STUDENT EXAMINATION REPORT CARD
                    </h2>

                </div>


                {{-- =================================================
                    EXAMINATION INFORMATION
                ================================================== --}}
                <div class="exam-information">

                    <div>

                        <span>
                            Examination:
                        </span>

                        <strong>
                            {{ $selectedExam?->name ?? '—' }}
                        </strong>

                    </div>


                    <div>

                        <span>
                            Class:
                        </span>

                        <strong>
                            {{ $selectedClass?->name ?? '—' }}
                        </strong>

                    </div>


                    <div>

                        <span>
                            Academic Year:
                        </span>

                        <strong>
                            {{ $selectedExam?->academicYear?->name ?? '—' }}
                        </strong>

                    </div>


                    <div>

                        <span>
                            Term:
                        </span>

                        <strong>
                            {{ $selectedExam?->term?->name ?? '—' }}
                        </strong>

                    </div>

                </div>


                {{-- =================================================
                    STUDENT INFORMATION
                ================================================== --}}
                <div class="student-information">

                    <div>

                        <span>
                            Student Name:
                        </span>

                        <strong>
                            {{ $report['student']->full_name }}
                        </strong>

                    </div>


                    <div>

                        <span>
                            Admission No:
                        </span>

                        <strong>

                            {{
                                $report['student']->admission_number
                                ?: $report['student']->admission_no
                                ?: '—'
                            }}

                        </strong>

                    </div>


                    <div>

                        <span>
                            Gender:
                        </span>

                        <strong>
                            {{ $report['student']->gender ?? '—' }}
                        </strong>

                    </div>

                </div>


                {{-- =================================================
                    STREAM INFORMATION
                ================================================== --}}
                @if(!empty($report['stream']))

                    <div class="stream-information">

                        <span>
                            Stream:
                        </span>

                        <strong>
                            {{ $report['stream']->name }}
                        </strong>

                        @if(!empty($report['stream']->code))

                            <span class="stream-code">
                                ({{ $report['stream']->code }})
                            </span>

                        @endif

                    </div>

                @endif


                {{-- =================================================
                    SUBJECT RESULTS
                ================================================== --}}
                <table class="report-table">

                    <thead>

                        <tr>

                            <th>#</th>

                            <th>
                                Subject
                            </th>

                            <th>
                                Mark
                            </th>

                            <th>
                                Grade
                            </th>

                            <th>
                                Points
                            </th>

                            <th>
                                Remarks
                            </th>

                        </tr>

                    </thead>


                    <tbody>

                        @forelse(
                            $report['subjects']
                            as $index => $subject
                        )

                            @php

                                $subjectResult =
                                    $report['subjectResults'][$subject->id]
                                    ?? null;

                            @endphp

                            <tr>

                                <td class="text-center">
                                    {{ $index + 1 }}
                                </td>


                                <td class="subject-name">
                                    {{ $subject->name }}
                                </td>


                                <td class="text-center">

                                    @if(
                                        $subjectResult &&
                                        !empty($subjectResult['absent'])
                                    )

                                        <strong>
                                            ABS
                                        </strong>

                                    @elseif(
                                        $subjectResult &&
                                        $subjectResult['mark'] !== null
                                    )

                                        {{ number_format(
                                            $subjectResult['mark'],
                                            0
                                        ) }}

                                    @else

                                        —

                                    @endif

                                </td>


                                <td class="text-center fw-bold">

                                    {{ $subjectResult['grade'] ?? '—' }}

                                </td>


                                <td class="text-center">

                                    @if(
                                        $subjectResult &&
                                        isset($subjectResult['points']) &&
                                        empty($subjectResult['absent'])
                                    )

                                        {{ $subjectResult['points'] }}

                                    @else

                                        —

                                    @endif

                                </td>


                                <td>

                                    {{ $subjectResult['remarks'] ?? '—' }}

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td
                                    colspan="6"
                                    class="text-center py-3"
                                >

                                    No subjects are allocated to this
                                    class/stream.

                                </td>

                            </tr>

                        @endforelse


                        {{-- TOTAL --}}
                        <tr class="summary-row">

                            <td colspan="2">
                                TOTAL
                            </td>

                            <td class="text-center">

                                {{ number_format(
                                    $report['total'] ?? 0,
                                    0
                                ) }}

                            </td>

                            <td></td>

                            <td class="text-center">

                                {{ number_format(
                                    $report['points'] ?? 0,
                                    0
                                ) }}

                            </td>

                            <td></td>

                        </tr>


                        {{-- MEAN --}}
                        <tr class="summary-row">

                            <td colspan="2">
                                MEAN
                            </td>

                            <td class="text-center">

                                @if(
                                    isset($report['mean']) &&
                                    $report['mean'] !== null
                                )

                                    {{ number_format(
                                        $report['mean'],
                                        2
                                    ) }}

                                @else

                                    —

                                @endif

                            </td>


                            <td class="text-center">

                                <strong>
                                    {{ $report['grade'] ?? '—' }}
                                </strong>

                            </td>

                            <td></td>

                            <td></td>

                        </tr>

                    </tbody>

                </table>


                {{-- =================================================
                    PERFORMANCE SUMMARY
                ================================================== --}}
                <div class="performance-summary">

                    <div>

                        <span>
                            Total Marks
                        </span>

                        <strong>

                            {{ number_format(
                                $report['total'] ?? 0,
                                0
                            ) }}

                        </strong>

                    </div>


                    <div>

                        <span>
                            Mean
                        </span>

                        <strong>

                            @if(
                                isset($report['mean']) &&
                                $report['mean'] !== null
                            )

                                {{ number_format(
                                    $report['mean'],
                                    2
                                ) }}

                            @else

                                —

                            @endif

                        </strong>

                    </div>


                    <div>

                        <span>
                            Overall Grade
                        </span>

                        <strong>
                            {{ $report['grade'] ?? '—' }}
                        </strong>

                    </div>


                    <div>

                        <span>
                            Total Points
                        </span>

                        <strong>

                            {{ number_format(
                                $report['points'] ?? 0,
                                0
                            ) }}

                        </strong>

                    </div>


                    <div>

                        <span>
                            Position
                        </span>

                        <strong>

                            @if(!empty($report['position']))

                                {{ $report['position'] }}
                                /
                                {{ $report['classSize'] }}

                            @else

                                —

                            @endif

                        </strong>

                    </div>

                </div>


                {{-- =================================================
                    CLASS STATISTICS
                ================================================== --}}
                <div class="class-statistics">

                    <div>

                        <span>
                            Class Average
                        </span>

                        <strong>

                            @if(
                                isset($report['classAverage']) &&
                                $report['classAverage'] !== null
                            )

                                {{ number_format(
                                    $report['classAverage'],
                                    2
                                ) }}

                            @else

                                —

                            @endif

                        </strong>

                    </div>


                    <div>

                        <span>
                            Highest Total
                        </span>

                        <strong>

                            @if(
                                isset($report['classHighest']) &&
                                $report['classHighest'] !== null
                            )

                                {{ number_format(
                                    $report['classHighest'],
                                    0
                                ) }}

                            @else

                                —

                            @endif

                        </strong>

                    </div>


                    <div>

                        <span>
                            Lowest Total
                        </span>

                        <strong>

                            @if(
                                isset($report['classLowest']) &&
                                $report['classLowest'] !== null
                            )

                                {{ number_format(
                                    $report['classLowest'],
                                    0
                                ) }}

                            @else

                                —

                            @endif

                        </strong>

                    </div>


                    <div>

                        <span>
                            Class Students
                        </span>

                        <strong>
                            {{ $report['classSize'] ?? 0 }}
                        </strong>

                    </div>


                    <div>

                        <span>
                            Stream Students
                        </span>

                        <strong>
                            {{ $report['streamSize'] ?? 0 }}
                        </strong>

                    </div>

                </div>


                {{-- =================================================
                    GRADE REMARKS
                ================================================== --}}
                @if(!empty($report['gradeRemarks']))

                    <div class="overall-remarks">

                        <strong>
                            Overall Remarks:
                        </strong>

                        {{ $report['gradeRemarks'] }}

                    </div>

                @endif


                {{-- =================================================
                    SIGNATURES
                ================================================== --}}
                <div class="signature-section">

                    <div class="signature-box">

                        <div class="signature-line"></div>

                        <strong>
                            Class Teacher
                        </strong>

                        <span>
                            Signature & Date
                        </span>

                    </div>


                    <div class="signature-box">

                        <div class="signature-line"></div>

                        <strong>
                            Principal / Head Teacher
                        </strong>

                        <span>
                            Signature & Date
                        </span>

                    </div>

                </div>


                {{-- =================================================
                    FOOTER
                ================================================== --}}
                <div class="document-footer">

                    <span>
                        Generated by
                        <strong>ScholarERP</strong>
                    </span>

                    <span>
                        Printed:
                        {{ now()->format('d M Y, H:i') }}
                    </span>

                </div>

            </div>

        </div>

    @else

        {{-- =========================================================
            EMPTY STATE
        ========================================================== --}}
        <div class="card border-0 shadow-sm no-print">

            <div class="card-body text-center py-4">

                <i class="bi bi-file-earmark-person fs-2 text-muted"></i>

                <h6 class="mt-2 mb-1">
                    Select a student
                </h6>

                <small class="text-muted">

                    Select an examination, class and student,
                    then click Load Report Card.

                </small>

            </div>

        </div>

    @endif

</div>


{{-- =============================================================
    STYLES
============================================================= --}}
<style>

.report-filter-card {
    max-width: 1100px;
    margin-left: auto;
    margin-right: auto;
}


/* =============================================================
   REPORT DOCUMENT
============================================================= */

.report-document {
    background: #fff;
    padding: 30px;
}


/* =============================================================
   SCHOOL HEADER
============================================================= */

.official-school-header {
    display: grid;
    grid-template-columns: 90px 1fr 110px;
    align-items: center;
    width: 100%;
    min-height: 85px;
    padding: 8px 10px 9px;
    margin-bottom: 8px;
    border-bottom: 3px double #111;
    background: #fff;
}


.official-logo-area {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 90px;
    height: 75px;
}


.official-school-logo {
    width: 72px;
    height: 72px;
    object-fit: contain;
    display: block;
}


.official-logo-placeholder {
    width: 65px;
    height: 65px;
    border: 1px solid #777;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 22px;
    color: #777;
}


.official-school-information {
    text-align: center;
    line-height: 1.25;
    padding: 0 10px;
}


.official-school-name {
    font-size: 20px;
    font-weight: 900;
    text-transform: uppercase;
    letter-spacing: .4px;
    line-height: 1.15;
    margin-bottom: 3px;
}


.official-school-motto {
    font-size: 9px;
    font-style: italic;
    font-weight: 700;
    margin-bottom: 3px;
}


.official-school-address,
.official-school-country {
    font-size: 8px;
    font-weight: 600;
}


.official-school-contact {
    display: flex;
    justify-content: center;
    align-items: center;
    flex-wrap: wrap;
    gap: 4px;
    margin-top: 2px;
    font-size: 7.5px;
}


.official-school-identification {
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 5px;
    margin-top: 3px;
    font-size: 7px;
}


.header-separator {
    color: #555;
    padding: 0 2px;
}


.official-document-area {
    display: flex;
    justify-content: center;
    align-items: center;
}


.official-document-badge {
    width: 92px;
    padding: 7px 5px;
    border: 1px solid #222;
    text-align: center;
    font-size: 7px;
    font-weight: 800;
    letter-spacing: .5px;
    line-height: 1.45;
}


.official-document-badge div:first-child {
    font-size: 8px;
    border-bottom: 1px solid #777;
    padding-bottom: 2px;
    margin-bottom: 2px;
}


/* =============================================================
   TITLE
============================================================= */

.report-title {
    text-align: center;
    margin: 10px 0;
}


.report-title h2 {
    margin: 0;
    font-size: 14px;
    font-weight: 800;
    text-decoration: underline;
}


/* =============================================================
   EXAM INFORMATION
============================================================= */

.exam-information {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    border: 1px solid #222;
    margin-bottom: 10px;
}


.exam-information > div {
    padding: 6px;
    border-right: 1px solid #222;
    font-size: 9px;
}


.exam-information > div:last-child {
    border-right: none;
}


/* =============================================================
   STUDENT INFORMATION
============================================================= */

.student-information {
    display: grid;
    grid-template-columns: 2fr 1fr 1fr;
    border: 1px solid #222;
    margin-bottom: 8px;
}


.student-information > div {
    padding: 7px;
    border-right: 1px solid #222;
    font-size: 9px;
}


.student-information > div:last-child {
    border-right: none;
}


.student-information span,
.exam-information span {
    margin-right: 4px;
}


/* =============================================================
   STREAM
============================================================= */

.stream-information {
    border: 1px solid #222;
    padding: 6px 7px;
    margin-bottom: 10px;
    font-size: 9px;
}


.stream-information span {
    margin-right: 4px;
}


.stream-code {
    margin-left: 3px;
    color: #555;
}


/* =============================================================
   TABLE
============================================================= */

.report-table {
    width: 100%;
    border-collapse: collapse;
    font-size: 9px;
}


.report-table th,
.report-table td {
    border: 1px solid #222;
    padding: 6px 5px;
    vertical-align: middle;
}


.report-table th {
    background: #eee;
    font-weight: 800;
    text-align: center;
}


.subject-name {
    font-weight: 600;
}


.summary-row {
    font-weight: 800;
    background: #f5f5f5;
}


/* =============================================================
   PERFORMANCE SUMMARY
============================================================= */

.performance-summary {
    display: grid;
    grid-template-columns: repeat(5, 1fr);
    margin-top: 12px;
    border: 1px solid #222;
}


.performance-summary > div {
    text-align: center;
    padding: 7px;
    border-right: 1px solid #222;
}


.performance-summary > div:last-child {
    border-right: none;
}


.performance-summary span,
.class-statistics span {
    display: block;
    font-size: 8px;
}


.performance-summary strong,
.class-statistics strong {
    display: block;
    font-size: 12px;
    margin-top: 2px;
}


/* =============================================================
   CLASS STATISTICS
============================================================= */

.class-statistics {
    display: grid;
    grid-template-columns: repeat(5, 1fr);
    margin-top: 8px;
    border: 1px solid #222;
}


.class-statistics > div {
    text-align: center;
    padding: 7px;
    border-right: 1px solid #222;
}


.class-statistics > div:last-child {
    border-right: none;
}


/* =============================================================
   OVERALL REMARKS
============================================================= */

.overall-remarks {
    margin-top: 10px;
    padding: 7px 9px;
    border: 1px solid #222;
    font-size: 9px;
}


/* =============================================================
   SIGNATURES
============================================================= */

.signature-section {
    display: flex;
    justify-content: space-between;
    margin-top: 35px;
    page-break-inside: avoid;
}


.signature-box {
    width: 35%;
    text-align: center;
    font-size: 9px;
}


.signature-line {
    border-top: 1px solid #111;
    margin-bottom: 4px;
}


.signature-box strong {
    display: block;
}


.signature-box span {
    display: block;
    font-size: 8px;
    margin-top: 2px;
}


/* =============================================================
   FOOTER
============================================================= */

.document-footer {
    display: flex;
    justify-content: space-between;
    border-top: 1px solid #aaa;
    margin-top: 20px;
    padding-top: 5px;
    font-size: 7.5px;
    color: #555;
}


/* =============================================================
   PRINT
============================================================= */

@media print {

    @page {
        size: A4 portrait;
        margin: 10mm;
    }


    html,
    body {
        margin: 0 !important;
        padding: 0 !important;
        background: #fff !important;
    }


    body * {
        visibility: hidden;
    }


    #reportCard,
    #reportCard * {
        visibility: visible;
    }


    #reportCard {
        position: absolute;
        left: 0;
        top: 0;
        width: 100%;
        margin: 0 !important;
        padding: 0 !important;
    }


    .no-print {
        display: none !important;
    }


    .report-document {
        padding: 0 !important;
        box-shadow: none !important;
    }


    .report-title {
        margin: 6px 0;
    }


    .report-title h2 {
        font-size: 11px;
    }


    .exam-information > div,
    .student-information > div,
    .stream-information {
        padding: 4px;
        font-size: 7.5px;
    }


    .report-table {
        font-size: 8px;
    }


    .report-table th,
    .report-table td {
        padding: 4px;
    }


    .performance-summary > div,
    .class-statistics > div {
        padding: 5px;
    }


    .performance-summary span,
    .class-statistics span {
        font-size: 7px;
    }


    .performance-summary strong,
    .class-statistics strong {
        font-size: 10px;
    }


    .signature-section {
        margin-top: 25px;
    }


    .document-footer {
        margin-top: 12px;
        font-size: 6.5px;
    }


    .report-table tr {
        page-break-inside: avoid;
    }

}


/* =============================================================
   MOBILE
============================================================= */

@media (max-width: 768px) {

    .container {
        padding-left: 10px;
        padding-right: 10px;
    }


    .exam-information {
        grid-template-columns: 1fr 1fr;
    }


    .student-information {
        grid-template-columns: 1fr;
    }


    .student-information > div {
        border-right: none;
        border-bottom: 1px solid #222;
    }


    .student-information > div:last-child {
        border-bottom: none;
    }


    .performance-summary,
    .class-statistics {
        grid-template-columns: 1fr 1fr;
    }


    .performance-summary > div,
    .class-statistics > div {
        border-bottom: 1px solid #222;
    }


    .official-school-header {
        grid-template-columns: 65px 1fr 75px;
    }


    .official-logo-area {
        width: 65px;
    }


    .official-school-logo {
        width: 52px;
        height: 52px;
    }


    .official-document-badge {
        width: 65px;
        font-size: 6px;
    }


    .official-school-name {
        font-size: 14px;
    }

}

</style>


{{-- =============================================================
    JAVASCRIPT
============================================================= --}}
<script>

document.addEventListener('DOMContentLoaded', function () {

    const examSelect =
        document.getElementById('exam_id');

    const classSelect =
        document.getElementById('school_class_id');

    const studentSelect =
        document.getElementById('student_id');

    const generateButton =
        document.getElementById('generateButton');

    const resetButton =
        document.getElementById('resetButton');

    const loading =
        document.getElementById('studentLoading');

    const selectionInfo =
        document.getElementById('selectionInfo');

    const selectionInfoText =
        document.getElementById('selectionInfoText');

    const form =
        document.getElementById('reportCardForm');


    if (
        !examSelect ||
        !classSelect ||
        !studentSelect ||
        !generateButton ||
        !form
    ) {
        return;
    }


    /*
    |--------------------------------------------------------------------------
    | Preserve selected student after POST
    |--------------------------------------------------------------------------
    */

    const initialStudentId =
        @json(
            old(
                'student_id',
                $report['student']->id ?? null
            )
        );


    /*
    |--------------------------------------------------------------------------
    | Show information
    |--------------------------------------------------------------------------
    */

    function showInfo(message) {

        if (!selectionInfo || !selectionInfoText) {
            return;
        }

        selectionInfoText.textContent = message;

        selectionInfo.classList.remove('d-none');
    }


    /*
    |--------------------------------------------------------------------------
    | Hide information
    |--------------------------------------------------------------------------
    */

    function hideInfo() {

        if (!selectionInfo) {
            return;
        }

        selectionInfo.classList.add('d-none');
    }


    /*
    |--------------------------------------------------------------------------
    | Get class belonging to selected examination
    |--------------------------------------------------------------------------
    */

    function getExamClassId() {

        const selectedOption =
            examSelect.options[
                examSelect.selectedIndex
            ];

        if (!selectedOption) {
            return '';
        }

        return selectedOption.dataset.classId || '';
    }


    /*
    |--------------------------------------------------------------------------
    | Reset student dropdown
    |--------------------------------------------------------------------------
    */

    function resetStudents(
        message = 'Select Examination First'
    ) {

        studentSelect.innerHTML = '';

        const option =
            document.createElement('option');

        option.value = '';
        option.textContent = message;

        studentSelect.appendChild(option);

        studentSelect.disabled = true;

        updateGenerateButton();
    }


    /*
    |--------------------------------------------------------------------------
    | Update generate button
    |--------------------------------------------------------------------------
    */

    function updateGenerateButton() {

        const examId =
            examSelect.value;

        const classId =
            classSelect.value;

        const studentId =
            studentSelect.value;

        const examClassId =
            getExamClassId();


        const valid =
            examId &&
            classId &&
            studentId &&
            examClassId &&
            String(examClassId) ===
            String(classId);


        generateButton.disabled =
            !valid;
    }


    /*
    |--------------------------------------------------------------------------
    | Synchronize class with examination
    |--------------------------------------------------------------------------
    */

    function synchronizeClassWithExam() {

        const examId =
            examSelect.value;


        if (!examId) {

            classSelect.value = '';

            classSelect.disabled = true;

            resetStudents(
                'Select Examination First'
            );

            hideInfo();

            return false;
        }


        const examClassId =
            getExamClassId();


        if (!examClassId) {

            classSelect.value = '';

            classSelect.disabled = true;

            resetStudents(
                'Unable to determine examination class'
            );

            showInfo(
                'The selected examination does not have a valid class.'
            );

            return false;
        }


        /*
        |--------------------------------------------------------------------------
        | Find matching class
        |--------------------------------------------------------------------------
        */

        const matchingClass =
            Array.from(
                classSelect.options
            ).find(function (option) {

                return String(option.value) ===
                    String(examClassId);

            });


        if (!matchingClass) {

            classSelect.value = '';

            classSelect.disabled = true;

            resetStudents(
                'Class is not available'
            );

            showInfo(
                'You are not authorized to access the class assigned to this examination.'
            );

            return false;
        }


        /*
        |--------------------------------------------------------------------------
        | IMPORTANT
        |
        | Do NOT disable the class field.
        |
        | Disabled HTML fields are not submitted with POST.
        |--------------------------------------------------------------------------
        */

        classSelect.value =
            examClassId;

        classSelect.disabled =
            false;


        showInfo(
            'Class automatically selected: ' +
            matchingClass.textContent.trim()
        );


        return true;
    }


    /*
    |--------------------------------------------------------------------------
    | Load students by class
    |--------------------------------------------------------------------------
    */

    function loadStudents(classId) {

        if (!classId) {

            resetStudents();

            return;
        }


        studentSelect.innerHTML = `
            <option value="">
                Loading students...
            </option>
        `;

        studentSelect.disabled = true;

        generateButton.disabled = true;


        if (loading) {

            loading.classList.remove(
                'd-none'
            );
        }


        const url =
            "{{ route(
                'student-report-card.students-by-class',
                ['schoolClass' => '__SCHOOL_CLASS__']
            ) }}"
            .replace(
                '__SCHOOL_CLASS__',
                encodeURIComponent(classId)
            );


        fetch(
            url,
            {
                method: 'GET',

                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            }
        )

        .then(function (response) {

            if (!response.ok) {

                throw new Error(
                    'Unable to load students. HTTP ' +
                    response.status
                );
            }

            return response.json();
        })

        .then(function (students) {

            studentSelect.innerHTML = '';


            if (
                !Array.isArray(students) ||
                students.length === 0
            ) {

                resetStudents(
                    'No active students found'
                );

                showInfo(
                    'No active students were found in the selected class.'
                );

                return;
            }


            /*
            |--------------------------------------------------------------------------
            | Default student option
            |--------------------------------------------------------------------------
            */

            const defaultOption =
                document.createElement('option');

            defaultOption.value = '';

            defaultOption.textContent =
                'Select Student';

            studentSelect.appendChild(
                defaultOption
            );


            /*
            |--------------------------------------------------------------------------
            | Add students
            |--------------------------------------------------------------------------
            */

            students.forEach(
                function (student) {

                    const option =
                        document.createElement('option');


                    option.value =
                        student.id;


                    const admissionNumber =
                        student.admission_number ||
                        student.admission_no ||
                        'No Admission No';


                    let fullName = '';


                    if (student.full_name) {

                        fullName =
                            student.full_name;

                    } else if (student.name) {

                        fullName =
                            student.name;

                    } else {

                        fullName = [

                            student.first_name,

                            student.middle_name,

                            student.last_name

                        ]
                        .filter(Boolean)
                        .join(' ');
                    }


                    let streamName = '';


                    if (student.stream) {

                        if (
                            typeof student.stream ===
                            'string'
                        ) {

                            streamName =
                                student.stream;

                        } else if (
                            student.stream.name
                        ) {

                            streamName =
                                student.stream.name;
                        }
                    }


                    let displayName =
                        admissionNumber +
                        ' - ' +
                        (
                            fullName ||
                            'Unnamed Student'
                        );


                    if (streamName) {

                        displayName +=
                            ' — ' +
                            streamName;
                    }


                    option.textContent =
                        displayName;


                    /*
                    |--------------------------------------------------------------------------
                    | Restore selected student
                    |--------------------------------------------------------------------------
                    */

                    if (
                        initialStudentId &&
                        String(student.id) ===
                        String(initialStudentId)
                    ) {

                        option.selected = true;
                    }


                    studentSelect.appendChild(
                        option
                    );

                }
            );


            studentSelect.disabled =
                false;


            const selectedClassOption =
                classSelect.options[
                    classSelect.selectedIndex
                ];


            const selectedClassName =
                selectedClassOption
                    ? selectedClassOption.textContent.trim()
                    : 'selected class';


            showInfo(
                students.length +
                ' active student' +
                (
                    students.length === 1
                        ? ''
                        : 's'
                ) +
                ' available in ' +
                selectedClassName +
                '.'
            );


            updateGenerateButton();
        })

        .catch(function (error) {

            console.error(
                'Student loading error:',
                error
            );


            studentSelect.innerHTML = `
                <option value="">
                    Unable to load students
                </option>
            `;


            studentSelect.disabled =
                true;


            generateButton.disabled =
                true;


            showInfo(
                'Unable to load students. Please refresh the page and try again.'
            );
        })

        .finally(function () {

            if (loading) {

                loading.classList.add(
                    'd-none'
                );
            }
        });
    }


    /*
    |--------------------------------------------------------------------------
    | Examination changed
    |--------------------------------------------------------------------------
    */

    examSelect.addEventListener(
        'change',
        function () {

            generateButton.disabled =
                true;


            resetStudents(
                'Loading students...'
            );


            const valid =
                synchronizeClassWithExam();


            if (!valid) {
                return;
            }


            loadStudents(
                classSelect.value
            );
        }
    );


    /*
    |--------------------------------------------------------------------------
    | Class changed
    |--------------------------------------------------------------------------
    |
    | Examination controls the class.
    |
    | If somebody manually changes the class, an incompatible
    | examination is cleared.
    |--------------------------------------------------------------------------
    */

    classSelect.addEventListener(
        'change',
        function () {

            const selectedClassId =
                classSelect.value;

            const examClassId =
                getExamClassId();


            if (
                examClassId &&
                selectedClassId &&
                String(examClassId) !==
                String(selectedClassId)
            ) {

                examSelect.value = '';

                resetStudents(
                    'Select Examination First'
                );

                showInfo(
                    'The examination was cleared because it belongs to a different class. Please select an examination for the selected class.'
                );

                updateGenerateButton();

                return;
            }


            if (selectedClassId) {

                loadStudents(
                    selectedClassId
                );

            } else {

                resetStudents(
                    'Select Examination First'
                );
            }
        }
    );


    /*
    |--------------------------------------------------------------------------
    | Student changed
    |--------------------------------------------------------------------------
    */

    studentSelect.addEventListener(
        'change',
        function () {

            updateGenerateButton();
        }
    );


    /*
    |--------------------------------------------------------------------------
    | Reset button
    |--------------------------------------------------------------------------
    */

    if (resetButton) {

        resetButton.addEventListener(
            'click',
            function () {

                window.location.href =
                    "{{ route('student-report-card.index') }}";
            }
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Form submission
    |--------------------------------------------------------------------------
    */

    form.addEventListener(
        'submit',
        function (event) {

            const examId =
                examSelect.value;

            const examClassId =
                getExamClassId();

            const selectedClassId =
                classSelect.value;

            const selectedStudentId =
                studentSelect.value;


            /*
            |--------------------------------------------------------------------------
            | Examination required
            |--------------------------------------------------------------------------
            */

            if (!examId) {

                event.preventDefault();

                alert(
                    'Please select an examination.'
                );

                return;
            }


            /*
            |--------------------------------------------------------------------------
            | Class required
            |--------------------------------------------------------------------------
            */

            if (!selectedClassId) {

                event.preventDefault();

                alert(
                    'Please select a class.'
                );

                return;
            }


            /*
            |--------------------------------------------------------------------------
            | Examination/class compatibility
            |--------------------------------------------------------------------------
            */

            if (
                !examClassId ||
                String(examClassId) !==
                String(selectedClassId)
            ) {

                event.preventDefault();

                alert(
                    'The selected examination does not belong to the selected class.'
                );

                synchronizeClassWithExam();

                return;
            }


            /*
            |--------------------------------------------------------------------------
            | Student required
            |--------------------------------------------------------------------------
            */

            if (!selectedStudentId) {

                event.preventDefault();

                alert(
                    'Please select a student.'
                );

                return;
            }


            /*
            |--------------------------------------------------------------------------
            | IMPORTANT
            |
            | Ensure class is enabled before POST.
            |--------------------------------------------------------------------------
            */

            classSelect.disabled =
                false;


            /*
            |--------------------------------------------------------------------------
            | Prevent double-click/double submission
            |--------------------------------------------------------------------------
            */

            generateButton.disabled =
                true;


            generateButton.innerHTML = `
                <span
                    class="spinner-border spinner-border-sm me-1"
                    role="status"
                    aria-hidden="true"
                ></span>
                Generating Report...
            `;
        }
    );


    /*
    |--------------------------------------------------------------------------
    | INITIAL PAGE LOAD
    |--------------------------------------------------------------------------
    */

    if (examSelect.value) {

        const valid =
            synchronizeClassWithExam();


        if (
            valid &&
            classSelect.value
        ) {

            loadStudents(
                classSelect.value
            );
        }

    } else {

        classSelect.value = '';

        classSelect.disabled = true;

        resetStudents(
            'Select Examination First'
        );
    }

});

</script>

@endsection