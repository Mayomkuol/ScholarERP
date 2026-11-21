@extends('layouts.app')

@section('content')

<div class="container-fluid">

    {{-- =========================================================
        PAGE HEADER
    ========================================================== --}}

    <div class="d-flex justify-content-between align-items-center mb-4 no-print">

        <div>
            <h4 class="fw-bold mb-1">
                <i class="bi bi-table me-2"></i>
                Class Examination Mark Sheet
            </h4>

            <p class="text-muted mb-0">
                View examination performance for all students in a selected class.
            </p>
        </div>

        @if($selectedExam && $selectedClass && $results->count())

            <button
                type="button"
                class="btn btn-dark"
                onclick="window.print()"
            >
                <i class="bi bi-printer me-1"></i>
                Print Mark Sheet
            </button>

        @endif

    </div>


    {{-- =========================================================
        FILTER CARD
    ========================================================== --}}

    <div class="card border-0 shadow-sm mb-4 no-print">

        <div class="card-header bg-white py-3">

            <h5 class="fw-bold mb-0">
                <i class="bi bi-funnel me-2"></i>
                Select Examination
            </h5>

        </div>

        <div class="card-body">

            <form
                method="POST"
                action="{{ route('class-mark-sheet.load') }}"
            >

                @csrf

                <div class="row g-3">

                    {{-- EXAMINATION --}}
                    <div class="col-md-6">

                        <label class="form-label fw-semibold">
                            Examination
                        </label>

                        <select
                            name="exam_id"
                            class="form-select"
                            required
                        >

                            <option value="">
                                Select Examination
                            </option>

                            @foreach($exams as $exam)

                                <option
                                    value="{{ $exam->id }}"
                                    @selected(
                                        optional($selectedExam)->id == $exam->id
                                    )
                                >
                                    {{ $exam->name }}
                                </option>

                            @endforeach

                        </select>

                    </div>


                    {{-- CLASS --}}
                    <div class="col-md-6">

                        <label class="form-label fw-semibold">
                            School Class
                        </label>

                        <select
                            name="school_class_id"
                            class="form-select"
                            required
                        >

                            <option value="">
                                Select Class
                            </option>

                            @foreach($classes as $class)

                                <option
                                    value="{{ $class->id }}"
                                    @selected(
                                        optional($selectedClass)->id == $class->id
                                    )
                                >
                                    {{ $class->name }}
                                </option>

                            @endforeach

                        </select>

                    </div>

                </div>


                <div class="mt-4">

                    <button
                        type="submit"
                        class="btn btn-primary"
                    >
                        <i class="bi bi-search me-1"></i>
                        Load Mark Sheet
                    </button>

                </div>

            </form>

        </div>

    </div>


    {{-- =========================================================
        MARK SHEET
    ========================================================== --}}

    @if($selectedExam && $selectedClass && $results->count())

        <div class="card border-0 shadow-sm">

            {{-- =================================================
                SCREEN HEADER
            ================================================== --}}

            <div class="card-header bg-white border-0 no-print">

                <div class="d-flex justify-content-between align-items-center">

                    <div>

                        <h5 class="fw-bold mb-1">
                            {{ $selectedClass->name }}
                        </h5>

                        <div class="text-muted small">

                            {{ $selectedExam->name }}

                            @if($selectedExam->academicYear)
                                | {{ $selectedExam->academicYear->name }}
                            @endif

                            @if($selectedExam->term)
                                | {{ $selectedExam->term->name }}
                            @endif

                        </div>

                    </div>


                    <div class="text-end">

                        <div class="small text-muted">
                            Students
                        </div>

                        <strong>
                            {{ $results->count() }}
                        </strong>

                    </div>

                </div>

            </div>


            {{-- =================================================
                PRINTABLE DOCUMENT
            ================================================== --}}

            <div id="markSheet" class="mark-sheet-document">


                {{-- =================================================
                    SCHOOL HEADER
                ================================================== --}}

                <div class="official-school-header">


                    {{-- LOGO --}}

                    <div class="official-logo-area">

                        @if(!empty($school?->logo))

                            @php

                                $logoPath = $school->logo;

                                $logoUrl = \Illuminate\Support\Str::startsWith(
                                    $logoPath,
                                    ['http://', 'https://']
                                )
                                    ? $logoPath
                                    : asset(
                                        'storage/' .
                                        ltrim($logoPath, '/')
                                    );

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


                        @if(
                            !empty($school?->country) &&
                            stripos(
                                $school->address ?? '',
                                $school->country
                            ) === false
                        )

                            <div class="official-school-country">
                                {{ $school->country }}
                            </div>

                        @endif


                        {{-- CONTACT --}}

                        @if(
                            !empty($school?->phone) ||
                            !empty($school?->email) ||
                            !empty($school?->website)
                        )

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

                                    <span class="header-separator">|</span>

                                @endif


                                @if(!empty($school?->email))

                                    <span>
                                        <strong>Email:</strong>
                                        {{ $school->email }}
                                    </span>

                                @endif


                                @if(!empty($school?->website))

                                    <span class="header-separator">|</span>

                                    <span>
                                        <strong>Web:</strong>
                                        {{ $school->website }}
                                    </span>

                                @endif

                            </div>

                        @endif


                        {{-- SCHOOL IDENTIFICATION --}}

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

                                    <span class="header-separator">|</span>

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


                    {{-- OFFICIAL DOCUMENT --}}

                    <div class="official-document-area">

                        <div class="official-document-badge">

                            <div>OFFICIAL</div>
                            <div>EXAMINATION</div>
                            <div>DOCUMENT</div>

                        </div>

                    </div>

                </div>


                {{-- =================================================
                    DOCUMENT TITLE
                ================================================== --}}

                <div class="mark-sheet-title">

                    <h2>
                        CLASS EXAMINATION MARK SHEET
                    </h2>

                    <div class="title-meta">

                        <strong>
                            {{ $selectedClass->name }}
                        </strong>

                        <span>|</span>

                        <strong>
                            {{ $selectedExam->name }}
                        </strong>

                        @if($selectedExam->academicYear)

                            <span>|</span>

                            <strong>
                                {{ $selectedExam->academicYear->name }}
                            </strong>

                        @endif

                        @if($selectedExam->term)

                            <span>|</span>

                            <strong>
                                {{ $selectedExam->term->name }}
                            </strong>

                        @endif

                    </div>

                </div>


                {{-- =================================================
                    CLASS INFORMATION
                ================================================== --}}

                <div class="class-information">

                    <div>
                        <span>Class:</span>
                        <strong>
                            {{ $selectedClass->name }}
                        </strong>
                    </div>

                    <div>
                        <span>Examination:</span>
                        <strong>
                            {{ $selectedExam->name }}
                        </strong>
                    </div>

                    <div>
                        <span>Students:</span>
                        <strong>
                            {{ $results->count() }}
                        </strong>
                    </div>

                    <div>
                        <span>Subjects:</span>
                        <strong>
                            {{ $subjects->count() }}
                        </strong>
                    </div>

                </div>


                {{-- =================================================
                    MARK TABLE
                ================================================== --}}

                <div class="table-wrapper">

                    <table class="mark-sheet-table">

                        <thead>

                            <tr>

                                <th rowspan="2" class="pos-col">
                                    Pos.
                                </th>

                                <th rowspan="2" class="admission-col">
                                    Admission No.
                                </th>

                                <th rowspan="2" class="student-col">
                                    Student Name
                                </th>


                                @foreach($subjects as $subject)

                                    <th
                                        colspan="3"
                                        class="subject-header"
                                    >
                                        {{ $subject->name }}
                                    </th>

                                @endforeach


                                <th rowspan="2" class="total-col">
                                    Total
                                </th>

                                <th rowspan="2" class="mean-col">
                                    Mean
                                </th>

                                <th rowspan="2" class="grade-col">
                                    Grade
                                </th>

                                <th rowspan="2" class="points-col">
                                    Points
                                </th>

                            </tr>


                            <tr>

                                @foreach($subjects as $subject)

                                    <th class="sub-header">
                                        Mark
                                    </th>

                                    <th class="sub-header">
                                        Grade
                                    </th>

                                    <th class="sub-header">
                                        Pts
                                    </th>

                                @endforeach

                            </tr>

                        </thead>


                        <tbody>

                            @foreach($results as $result)

                                <tr>

                                    {{-- POSITION --}}

                                    <td class="text-center fw-bold">
                                        {{ $result['position'] ?? '—' }}
                                    </td>


                                    {{-- ADMISSION NUMBER --}}

                                    <td class="text-center">

                                        {{
                                            $result['student']->admission_number
                                            ?? $result['student']->admission_no
                                            ?? '—'
                                        }}

                                    </td>


                                    {{-- STUDENT NAME --}}

                                    <td class="student-name">

                                        {{ $result['student']->first_name }}

                                        @if(!empty($result['student']->middle_name))
                                            {{ $result['student']->middle_name }}
                                        @endif

                                        {{ $result['student']->last_name }}

                                    </td>


                                    {{-- SUBJECT RESULTS --}}

                                    @foreach($subjects as $subject)

                                        @php

                                            $subjectResult =
                                                $result['subjects'][$subject->id]
                                                ?? null;

                                            $isAbsent =
                                                $subjectResult['absent']
                                                ?? false;

                                            $mark =
                                                $subjectResult['mark']
                                                ?? null;

                                            $subjectGrade =
                                                $subjectResult['grade']
                                                ?? null;

                                            $subjectPoints =
                                                $subjectResult['points']
                                                ?? 0;

                                        @endphp


                                        {{-- MARK --}}

                                        <td class="text-center">

                                            @if($isAbsent)

                                                <strong>ABS</strong>

                                            @elseif($mark !== null)

                                                {{ number_format($mark, 0) }}

                                            @else

                                                —

                                            @endif

                                        </td>


                                        {{-- GRADE --}}

                                        <td class="text-center fw-bold">

                                            @if($subjectGrade)

                                                {{ $subjectGrade }}

                                            @else

                                                —

                                            @endif

                                        </td>


                                        {{-- POINTS --}}

                                        <td class="text-center">

                                            @if(!$isAbsent && $subjectGrade)

                                                {{ number_format($subjectPoints, 0) }}

                                            @else

                                                0

                                            @endif

                                        </td>

                                    @endforeach


                                    {{-- TOTAL --}}

                                    <td class="text-center fw-bold">

                                        {{ number_format($result['total'] ?? 0, 0) }}

                                    </td>


                                    {{-- MEAN --}}

                                    <td class="text-center fw-bold">

                                        @if(($result['mean'] ?? null) !== null)

                                            {{ number_format($result['mean'], 2) }}

                                        @else

                                            —

                                        @endif

                                    </td>


                                    {{-- OVERALL GRADE --}}

                                    <td class="text-center fw-bold">

                                        {{ $result['grade'] ?? '—' }}

                                    </td>


                                    {{-- TOTAL POINTS --}}

                                    <td class="text-center fw-bold">

                                        {{ number_format($result['points'] ?? 0, 0) }}

                                    </td>

                                </tr>

                            @endforeach


                            {{-- =================================================
                                CLASS SUMMARY
                            ================================================== --}}

                            <tr class="class-summary-row">

                                <td colspan="3">
                                    CLASS SUMMARY
                                </td>


                                @foreach($subjects as $subject)

                                    @php

                                        $subjectTotal = 0;
                                        $subjectCount = 0;

                                        foreach($results as $result) {

                                            $subjectResult =
                                                $result['subjects'][$subject->id]
                                                ?? null;

                                            $isAbsent =
                                                $subjectResult['absent']
                                                ?? false;

                                            $mark =
                                                $subjectResult['mark']
                                                ?? null;

                                            if(
                                                !$isAbsent &&
                                                $mark !== null
                                            ) {

                                                $subjectTotal += $mark;
                                                $subjectCount++;

                                            }

                                        }

                                        $subjectMean =
                                            $subjectCount > 0
                                                ? $subjectTotal / $subjectCount
                                                : null;

                                    @endphp


                                    <td
                                        colspan="3"
                                        class="subject-summary"
                                    >

                                        @if($subjectMean !== null)

                                            Avg:
                                            {{ number_format($subjectMean, 2) }}

                                        @else

                                            —

                                        @endif

                                    </td>

                                @endforeach


                                {{-- TOTAL --}}

                                <td class="text-center">

                                    {{ number_format(
                                        $results->sum('total'),
                                        0
                                    ) }}

                                </td>


                                {{-- CLASS MEAN --}}

                                <td class="text-center">

                                    @php

                                        $validMeans =
                                            $results
                                                ->pluck('mean')
                                                ->filter(
                                                    fn($value) =>
                                                        $value !== null
                                                );

                                        $classMean =
                                            $validMeans->count()
                                                ? $validMeans->avg()
                                                : null;

                                    @endphp


                                    @if($classMean !== null)

                                        {{ number_format($classMean, 2) }}

                                    @else

                                        —

                                    @endif

                                </td>


                                {{-- GRADE --}}

                                <td></td>


                                {{-- POINTS --}}

                                <td class="text-center">

                                    {{ number_format(
                                        $results->sum('points'),
                                        0
                                    ) }}

                                </td>

                            </tr>

                        </tbody>

                    </table>

                </div>


                {{-- =================================================
                    LEGEND
                ================================================== --}}

                <div class="legend">

                    <strong>Legend:</strong>

                    <span>
                        ABS = Absent
                    </span>

                    <span>
                        — = No mark entered
                    </span>

                </div>


                {{-- =================================================
                    GRADING SCALE
                ================================================== --}}

                @if($grades->count())

                    <div class="grading-section">

                        <h4>
                            GRADING SCALE
                        </h4>

                        <div class="grading-grid">

                            @foreach($grades as $grade)

                                <div>

                                    <strong
                                        @if(!empty($grade->color))
                                            style="color: {{ $grade->color }};"
                                        @endif
                                    >
                                        {{ $grade->grade }}
                                    </strong>

                                    <span>
                                        {{ number_format($grade->min_mark, 0) }}
                                        –
                                        {{ number_format($grade->max_mark, 0) }}
                                    </span>

                                    <small>
                                        {{ number_format($grade->points, 0) }} pts
                                    </small>

                                </div>

                            @endforeach

                        </div>

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
                            Examination Officer
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

            <div class="card-body text-center py-5">

                <i class="bi bi-table fs-1 text-muted"></i>

                <h5 class="mt-3">
                    Select Examination and Class
                </h5>

                <p class="text-muted mb-0">
                    Select an examination and class above,
                    then click <strong>Load Mark Sheet</strong>.
                </p>

            </div>

        </div>

    @endif

</div>

<style>

/* =============================================================
   GENERAL DOCUMENT
============================================================= */

.mark-sheet-document {
    background: #ffffff;
    padding: 28px;
    color: #111;
    font-family: Arial, Helvetica, sans-serif;
    overflow-x: auto;
}


/* =============================================================
   SCHOOL HEADER
============================================================= */

.official-school-header {
    display: grid;
    grid-template-columns: 95px 1fr 120px;
    align-items: center;

    width: 100%;
    min-height: 100px;

    padding: 10px 12px;
    margin-bottom: 12px;

    border-bottom: 3px double #111;
    background: #fff;
}


/* =============================================================
   LOGO
============================================================= */

.official-logo-area {
    display: flex;
    align-items: center;
    justify-content: center;

    width: 95px;
    height: 82px;
}

.official-school-logo {
    width: 78px;
    height: 78px;

    object-fit: contain;
    display: block;
}

.official-logo-placeholder {
    width: 68px;
    height: 68px;

    border: 1px solid #777;

    display: flex;
    align-items: center;
    justify-content: center;

    font-size: 24px;
    color: #777;
}


/* =============================================================
   SCHOOL INFORMATION
============================================================= */

.official-school-information {
    text-align: center;
    line-height: 1.3;
    padding: 0 15px;
}

.official-school-name {
    font-size: 22px;
    font-weight: 900;

    text-transform: uppercase;
    letter-spacing: .5px;

    line-height: 1.2;
    margin-bottom: 4px;
}

.official-school-motto {
    font-size: 10px;
    font-style: italic;
    font-weight: 700;

    margin-bottom: 4px;
}

.official-school-address,
.official-school-country {
    font-size: 9px;
    font-weight: 600;
}

.official-school-contact {
    display: flex;
    justify-content: center;
    align-items: center;

    flex-wrap: wrap;
    gap: 5px;

    margin-top: 3px;

    font-size: 8px;
}

.official-school-identification {
    display: flex;
    justify-content: center;
    align-items: center;

    flex-wrap: wrap;
    gap: 6px;

    margin-top: 4px;

    font-size: 7.5px;
}

.header-separator {
    color: #555;
}


/* =============================================================
   OFFICIAL DOCUMENT BADGE
============================================================= */

.official-document-area {
    display: flex;
    justify-content: center;
    align-items: center;

    width: 120px;
}

.official-document-badge {
    width: 98px;

    padding: 8px 6px;

    border: 2px solid #222;

    text-align: center;

    font-size: 8px;
    font-weight: 800;

    letter-spacing: .6px;
    line-height: 1.5;

    text-transform: uppercase;
}

.official-document-badge div:first-child {
    font-size: 9px;

    border-bottom: 1px solid #777;

    padding-bottom: 3px;
    margin-bottom: 3px;
}


/* =============================================================
   DOCUMENT TITLE
============================================================= */

.mark-sheet-title {
    text-align: center;

    margin: 12px 0 14px;
}

.mark-sheet-title h2 {
    margin: 0;

    font-size: 17px;
    font-weight: 900;

    letter-spacing: .4px;
    text-decoration: underline;
}

.title-meta {
    display: flex;

    justify-content: center;
    align-items: center;

    flex-wrap: wrap;
    gap: 7px;

    margin-top: 5px;

    font-size: 10px;
}


/* =============================================================
   CLASS INFORMATION
============================================================= */

.class-information {
    display: grid;

    grid-template-columns: repeat(4, 1fr);

    border: 1.5px solid #222;

    margin-bottom: 12px;

    background: #fafafa;
}

.class-information > div {
    padding: 7px 9px;

    border-right: 1px solid #222;

    font-size: 9px;
}

.class-information > div:last-child {
    border-right: none;
}

.class-information span {
    margin-right: 4px;
    color: #444;
}


/* =============================================================
   TABLE WRAPPER
============================================================= */

.table-wrapper {
    width: 100%;
    overflow-x: auto;
}


/* =============================================================
   MAIN MARK SHEET TABLE
============================================================= */

.mark-sheet-table {
    width: 100%;

    border-collapse: collapse;

    table-layout: auto;

    font-size: 9px;

    background: #fff;
}

.mark-sheet-table th,
.mark-sheet-table td {
    border: 1px solid #222;

    padding: 6px 4px;

    vertical-align: middle;
}

.mark-sheet-table thead th {
    background: #e9ecef;

    text-align: center;

    font-weight: 900;

    white-space: nowrap;

    color: #111;
}


/* =============================================================
   SUBJECT HEADERS
============================================================= */

.subject-header {
    min-width: 105px;

    font-size: 8px;

    padding: 7px 4px !important;
}

.sub-header {
    font-size: 8px;

    min-width: 38px;

    background: #f1f3f5 !important;
}


/* =============================================================
   COLUMN WIDTHS
============================================================= */

.pos-col {
    width: 42px;
}

.admission-col {
    width: 90px;
}

.student-col {
    min-width: 155px;
    width: 155px;
}

.total-col {
    width: 58px;
}

.mean-col {
    width: 58px;
}

.grade-col {
    width: 55px;
}

.points-col {
    width: 58px;
}


/* =============================================================
   STUDENT DATA
============================================================= */

.student-name {
    font-weight: 600;

    white-space: nowrap;

    text-align: left;

    padding-left: 7px !important;
}

.mark-sheet-table tbody tr {
    min-height: 28px;
}

.mark-sheet-table tbody tr:nth-child(even) {
    background: #fafafa;
}

.mark-sheet-table tbody tr:hover {
    background: #f1f3f5;
}


/* =============================================================
   ABSENT MARK
============================================================= */

.mark-sheet-table td strong {
    font-weight: 800;
}


/* =============================================================
   SUMMARY ROW
============================================================= */

.class-summary-row {
    background: #e9ecef !important;

    font-weight: 900;

    border-top: 2px solid #111;
}

.class-summary-row td {
    padding-top: 8px;
    padding-bottom: 8px;
}

.subject-summary {
    text-align: center;

    font-size: 8px;

    font-weight: 800;
}


/* =============================================================
   LEGEND
============================================================= */

.legend {
    display: flex;

    align-items: center;

    flex-wrap: wrap;

    gap: 18px;

    margin-top: 10px;

    font-size: 9px;
}

.legend strong {
    font-weight: 800;
}


/* =============================================================
   GRADING SCALE
============================================================= */

.grading-section {
    margin-top: 15px;
}

.grading-section h4 {
    margin: 0 0 6px;

    font-size: 10px;

    font-weight: 900;

    letter-spacing: .3px;
}

.grading-grid {
    display: grid;

    grid-template-columns:
        repeat(
            auto-fit,
            minmax(75px, 1fr)
        );

    border: 1px solid #222;
}

.grading-grid > div {
    text-align: center;

    padding: 6px 3px;

    border-right: 1px solid #222;

    font-size: 8px;

    background: #fafafa;
}

.grading-grid > div:last-child {
    border-right: none;
}

.grading-grid strong {
    display: block;

    font-size: 9px;

    font-weight: 900;
}

.grading-grid span,
.grading-grid small {
    display: block;

    margin-top: 1px;
}


/* =============================================================
   SIGNATURES
============================================================= */

.signature-section {
    display: flex;

    justify-content: space-between;

    gap: 35px;

    margin-top: 42px;

    page-break-inside: avoid;
}

.signature-box {
    width: 30%;

    text-align: center;

    font-size: 9px;
}

.signature-line {
    border-top: 1px solid #111;

    margin-bottom: 5px;
}

.signature-box strong {
    display: block;

    font-weight: 800;
}

.signature-box span {
    display: block;

    margin-top: 3px;

    font-size: 8px;

    color: #555;
}


/* =============================================================
   FOOTER
============================================================= */

.document-footer {
    display: flex;

    justify-content: space-between;

    border-top: 1px solid #aaa;

    margin-top: 18px;

    padding-top: 6px;

    font-size: 8px;

    color: #555;
}


/* =============================================================
   SCREEN RESPONSIVENESS
============================================================= */

@media screen and (max-width: 1200px) {

    .mark-sheet-document {
        padding: 18px;
    }

    .mark-sheet-table {
        min-width: 1200px;
    }

}


/* =============================================================
   PRINT
============================================================= */

@media print {

    @page {
        size: A4 landscape;
        margin: 7mm;
    }

    html,
    body {
        margin: 0 !important;
        padding: 0 !important;

        background: #fff !important;

        -webkit-print-color-adjust: exact !important;
        print-color-adjust: exact !important;
    }


    /* Hide everything except mark sheet */

    body * {
        visibility: hidden;
    }

    #markSheet,
    #markSheet * {
        visibility: visible;
    }

    #markSheet {
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

    .mark-sheet-document {
        padding: 0 !important;

        overflow: visible !important;

        width: 100% !important;
    }


    /* =========================================================
       PRINT SCHOOL HEADER
    ========================================================= */

    .official-school-header {
        grid-template-columns:
            78px
            1fr
            92px;

        min-height: 75px;

        padding: 5px 6px 7px;

        margin-bottom: 6px;

        border-bottom: 2px double #111;
    }

    .official-logo-area {
        width: 78px;
        height: 65px;
    }

    .official-school-logo {
        width: 58px;
        height: 58px;
    }

    .official-logo-placeholder {
        width: 52px;
        height: 52px;

        font-size: 17px;
    }

    .official-school-information {
        padding: 0 8px;
    }

    .official-school-name {
        font-size: 16px;

        letter-spacing: .25px;

        margin-bottom: 2px;
    }

    .official-school-motto {
        font-size: 7.5px;

        margin-bottom: 2px;
    }

    .official-school-address,
    .official-school-country {
        font-size: 6.8px;
    }

    .official-school-contact {
        font-size: 6.3px;

        gap: 3px;

        margin-top: 2px;
    }

    .official-school-identification {
        font-size: 6px;

        margin-top: 2px;

        gap: 4px;
    }

    .official-document-area {
        width: 92px;
    }

    .official-document-badge {
        width: 74px;

        padding: 5px 3px;

        border: 1px solid #222;

        font-size: 5.8px;

        letter-spacing: .4px;
    }

    .official-document-badge div:first-child {
        font-size: 6.5px;

        padding-bottom: 2px;

        margin-bottom: 2px;
    }


    /* =========================================================
       PRINT TITLE
    ========================================================= */

    .mark-sheet-title {
        margin: 6px 0 7px;
    }

    .mark-sheet-title h2 {
        font-size: 11px;

        letter-spacing: .2px;
    }

    .title-meta {
        font-size: 7px;

        gap: 4px;

        margin-top: 3px;
    }


    /* =========================================================
       PRINT CLASS INFORMATION
    ========================================================= */

    .class-information {
        margin-bottom: 6px;

        border: 1px solid #222;
    }

    .class-information > div {
        padding: 4px 5px;

        font-size: 7px;
    }


    /* =========================================================
       PRINT TABLE
    ========================================================= */

    .table-wrapper {
        overflow: visible !important;
    }

    .mark-sheet-table {
        width: 100% !important;

        min-width: 0 !important;

        font-size: 6.7px;

        table-layout: auto;
    }

    .mark-sheet-table th,
    .mark-sheet-table td {
        border: 1px solid #222;

        padding: 3.2px 2px;

        line-height: 1.15;
    }

    .mark-sheet-table thead th {
        background: #e9ecef !important;

        font-weight: 900;
    }


    /* =========================================================
       PRINT SUBJECT HEADERS
    ========================================================= */

    .subject-header {
        min-width: 0;

        font-size: 6.4px;

        padding: 4px 2px !important;
    }

    .sub-header {
        min-width: 0;

        font-size: 6px;

        padding: 3px 1px !important;
    }


    /* =========================================================
       PRINT COLUMN WIDTHS
    ========================================================= */

    .pos-col {
        width: 28px;
    }

    .admission-col {
        width: 62px;
    }

    .student-col {
        min-width: 0;

        width: 120px;
    }

    .total-col,
    .mean-col {
        width: 42px;
    }

    .grade-col {
        width: 38px;
    }

    .points-col {
        width: 42px;
    }


    /* =========================================================
       PRINT STUDENT NAMES
    ========================================================= */

    .student-name {
        white-space: nowrap;

        font-weight: 700;

        padding-left: 4px !important;
    }


    /* =========================================================
       PRINT SUMMARY
    ========================================================= */

    .class-summary-row {
        background: #e9ecef !important;

        font-weight: 900;
    }

    .class-summary-row td {
        padding-top: 4px;

        padding-bottom: 4px;
    }

    .subject-summary {
        font-size: 5.8px;
    }


    /* =========================================================
       PRINT LEGEND
    ========================================================= */

    .legend {
        margin-top: 5px;

        font-size: 6.2px;

        gap: 12px;
    }


    /* =========================================================
       PRINT GRADING SCALE
    ========================================================= */

    .grading-section {
        margin-top: 7px;
    }

    .grading-section h4 {
        font-size: 7px;

        margin-bottom: 3px;
    }

    .grading-grid {
        grid-template-columns:
            repeat(
                {{ max(1, $grades->count()) }},
                1fr
            );

        border: 1px solid #222;
    }

    .grading-grid > div {
        padding: 3px 1px;

        font-size: 5.5px;
    }

    .grading-grid strong {
        font-size: 6.5px;
    }

    .grading-grid span {
        font-size: 5.2px;
    }

    .grading-grid small {
        font-size: 5px;
    }


    /* =========================================================
       PRINT SIGNATURES
    ========================================================= */

    .signature-section {
        margin-top: 20px;

        gap: 25px;

        page-break-inside: avoid;
    }

    .signature-box {
        font-size: 6.5px;
    }

    .signature-line {
        margin-bottom: 4px;
    }

    .signature-box span {
        font-size: 5.8px;

        margin-top: 2px;
    }


    /* =========================================================
       PRINT FOOTER
    ========================================================= */

    .document-footer {
        margin-top: 8px;

        padding-top: 3px;

        font-size: 5.5px;
    }


    /* =========================================================
       PAGE BREAK CONTROL
    ========================================================= */

    .mark-sheet-table tr {
        page-break-inside: avoid;
    }

    .mark-sheet-table thead {
        display: table-header-group;
    }

    .mark-sheet-table tfoot {
        display: table-footer-group;
    }

    .official-school-header,
    .mark-sheet-title,
    .class-information,
    .legend,
    .grading-section,
    .signature-section {
        page-break-inside: avoid;
    }

}
</style>
@endsection