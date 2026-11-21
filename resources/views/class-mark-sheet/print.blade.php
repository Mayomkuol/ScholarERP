<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <title>
        {{ $school->name ?? 'School' }} - Examination Mark Sheet
    </title>

    <style>

        @page {
            size: A4 landscape;
            margin: 10mm;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            padding: 0;
            font-family: Arial, Helvetica, sans-serif;
            font-size: 9px;
            color: #000;
            background: #fff;
        }

        .sheet {
            width: 100%;
            max-width: 277mm;
            margin: 0 auto;
        }


        /* =====================================================
           SCHOOL HEADER
        ===================================================== */

        .school-header {
            width: 100%;
            margin-bottom: 7px;
            border-bottom: 2px solid #000;
            padding-bottom: 7px;
        }

        .school-header-table {
            width: 100%;
            border-collapse: collapse;
        }

        .school-logo-cell {
            width: 18%;
            text-align: center;
            vertical-align: middle;
        }

        .school-info-cell {
            width: 64%;
            text-align: center;
            vertical-align: middle;
        }

        .school-right-cell {
            width: 18%;
            text-align: center;
            vertical-align: middle;
        }

        .school-logo {
            width: 70px;
            height: 70px;
            object-fit: contain;
        }

        .school-logo-placeholder {
            width: 70px;
            height: 70px;
            border: 1px solid #999;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 8px;
            color: #777;
        }

        .school-name {
            font-size: 20px;
            font-weight: bold;
            text-transform: uppercase;
            line-height: 1.2;
            margin-bottom: 3px;
        }

        .school-motto {
            font-size: 10px;
            font-style: italic;
            font-weight: bold;
            margin-bottom: 3px;
        }

        .school-address {
            font-size: 8px;
            margin-bottom: 2px;
        }

        .school-contact {
            font-size: 8px;
            line-height: 1.4;
        }

        .school-code {
            font-size: 7px;
            color: #555;
            margin-top: 3px;
        }

        .official-document {
            font-size: 8px;
            font-weight: bold;
            text-transform: uppercase;
            border: 1px solid #555;
            padding: 5px;
            display: inline-block;
            line-height: 1.4;
        }


        /* =====================================================
           TITLE
        ===================================================== */

        .title {
            text-align: center;
            font-size: 13px;
            font-weight: bold;
            text-transform: uppercase;
            margin-top: 6px;
            margin-bottom: 2px;
            text-decoration: underline;
        }

        .subtitle {
            text-align: center;
            font-size: 8px;
            margin-bottom: 7px;
        }


        /* =====================================================
           DETAILS
        ===================================================== */

        .details {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 7px;
        }

        .details td {
            border: 1px solid #999;
            padding: 4px 5px;
        }

        .details .label {
            font-weight: bold;
            background: #f3f3f3;
            width: 10%;
        }


        /* =====================================================
           MARK TABLE
        ===================================================== */

        .marks-table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
        }

        .marks-table th,
        .marks-table td {
            border: 1px solid #333;
            padding: 3px 2px;
            text-align: center;
            vertical-align: middle;
        }

        .marks-table th {
            background: #e9ecef;
            font-weight: bold;
        }

        .marks-table .student-name {
            text-align: left;
            width: 22%;
        }

        .marks-table .adm-no {
            width: 11%;
        }

        .marks-table .subject {
            width: auto;
        }

        .marks-table .total {
            width: 6%;
        }

        .marks-table .mean {
            width: 6%;
        }

        .marks-table .grade {
            width: 5%;
        }

        .marks-table .points {
            width: 6%;
        }

        .marks-table .position {
            width: 6%;
        }

        .marks-table tbody tr {
            height: 24px;
        }

        .bold {
            font-weight: bold;
        }


        /* =====================================================
           GRADE COLOURS
        ===================================================== */

        .grade-badge {
            font-weight: bold;
        }


        /* =====================================================
           SUMMARY
        ===================================================== */

        .summary {
            margin-top: 6px;
            font-size: 8px;
        }

        .summary span {
            margin-right: 18px;
        }


        /* =====================================================
           GRADING SCALE
        ===================================================== */

        .grading-section {
            margin-top: 8px;
        }

        .grading-title {
            font-size: 9px;
            font-weight: bold;
            text-transform: uppercase;
            margin-bottom: 4px;
        }

        .grading-table {
            width: 100%;
            border-collapse: collapse;
        }

        .grading-table th,
        .grading-table td {
            border: 1px solid #555;
            padding: 3px 4px;
            text-align: center;
        }

        .grading-table th {
            background: #e9ecef;
            font-weight: bold;
        }

        .grading-table td.grade-cell {
            font-weight: bold;
        }


        /* =====================================================
           SIGNATURES
        ===================================================== */

        .signatures {
            width: 100%;
            margin-top: 15px;
            border-collapse: collapse;
        }

        .signatures td {
            width: 33.33%;
            padding-top: 8px;
            text-align: center;
        }

        .signature-line {
            border-top: 1px solid #000;
            width: 150px;
            margin: 18px auto 4px;
        }


        /* =====================================================
           FOOTER
        ===================================================== */

        .footer {
            margin-top: 8px;
            padding-top: 4px;
            border-top: 1px solid #999;
            text-align: center;
            font-size: 7px;
            color: #555;
        }


        /* =====================================================
           PRINT BUTTON
        ===================================================== */

        .print-button {
            position: fixed;
            top: 15px;
            right: 15px;
            padding: 8px 14px;
            border: none;
            background: #0d6efd;
            color: #fff;
            border-radius: 4px;
            cursor: pointer;
            font-size: 12px;
        }


        /* =====================================================
           PRINT
        ===================================================== */

        @media print {

            .print-button {
                display: none;
            }

            body {
                font-size: 8px;
            }

            .school-name {
                font-size: 17px;
            }

            .school-motto {
                font-size: 9px;
            }

            .school-logo {
                width: 60px;
                height: 60px;
            }

            .school-logo-placeholder {
                width: 60px;
                height: 60px;
            }

            .title {
                font-size: 11px;
            }

            .marks-table th,
            .marks-table td {
                padding: 2px;
            }

            .grading-table th,
            .grading-table td {
                padding: 2px;
            }

            .signatures {
                margin-top: 10px;
            }
        }

    </style>

</head>


<body>


@php

    /*
    |--------------------------------------------------------------------------
    | SCHOOL
    |--------------------------------------------------------------------------
    */

    $school = $school ?? \App\Models\School::first();


    /*
    |--------------------------------------------------------------------------
    | NORMALIZE CONTROLLER VARIABLES
    |--------------------------------------------------------------------------
    |
    | The controller uses:
    |
    |   $exam
    |   $schoolClass
    |
    | The view therefore uses those variables consistently.
    |
    */

    $exam = $exam ?? null;

    $schoolClass = $schoolClass ?? null;

    $stream = $stream ?? null;


    /*
    |--------------------------------------------------------------------------
    | ACADEMIC YEAR / TERM
    |--------------------------------------------------------------------------
    */

    $academicYearName = $academicYearName
        ?? ($exam?->academicYear?->name
        ?? $exam?->academicYear?->year
        ?? '—');


    $termName = $termName
        ?? ($exam?->term?->name ?? '—');


    /*
    |--------------------------------------------------------------------------
    | GRADING SCALE
    |--------------------------------------------------------------------------
    |
    | IMPORTANT:
    | Grades are loaded from the Grade Settings table.
    |
    */

    $grades = $grades
        ?? \App\Models\Grade::query()
            ->orderByDesc('min_mark')
            ->get();

@endphp



<button
    type="button"
    class="print-button"
    onclick="window.print()"
>
    Print Mark Sheet
</button>



<div class="sheet">


    {{-- =====================================================
         SCHOOL HEADER
    ===================================================== --}}

    <div class="school-header">

        <table class="school-header-table">

            <tr>


                {{-- LOGO --}}

                <td class="school-logo-cell">

                    @if($school && !empty($school->logo))

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
                                    'storage/' .
                                    ltrim($logoPath, '/')
                                );

                            }

                        @endphp


                        <img
                            src="{{ $logoUrl }}"
                            alt="School Logo"
                            class="school-logo"
                        >

                    @else

                        <div class="school-logo-placeholder">
                            SCHOOL LOGO
                        </div>

                    @endif

                </td>



                {{-- SCHOOL INFORMATION --}}

                <td class="school-info-cell">

                    <div class="school-name">
                        {{ $school->name ?? 'SCHOOL NAME' }}
                    </div>


                    @if(!empty($school?->motto))

                        <div class="school-motto">
                            "{{ $school->motto }}"
                        </div>

                    @endif


                    @if(!empty($school?->address))

                        <div class="school-address">

                            {{ $school->address }}

                            @if(!empty($school->country))
                                , {{ $school->country }}
                            @endif

                        </div>

                    @endif


                    <div class="school-contact">

                        @if(!empty($school?->phone))

                            <strong>Tel:</strong>
                            {{ $school->phone }}

                        @endif


                        @if(
                            !empty($school?->phone) &&
                            !empty($school?->email)
                        )

                            &nbsp; | &nbsp;

                        @endif


                        @if(!empty($school?->email))

                            <strong>Email:</strong>
                            {{ $school->email }}

                        @endif


                        @if(!empty($school?->website))

                            &nbsp; | &nbsp;

                            <strong>Web:</strong>
                            {{ $school->website }}

                        @endif

                    </div>


                    @if(!empty($school?->code))

                        <div class="school-code">

                            School Code:
                            {{ $school->code }}

                        </div>

                    @endif


                    @if(!empty($school?->registration_no))

                        <div class="school-code">

                            Reg. No:
                            {{ $school->registration_no }}

                        </div>

                    @endif

                </td>



                {{-- OFFICIAL LABEL --}}

                <td class="school-right-cell">

                    <div class="official-document">

                        OFFICIAL<br>
                        SCHOOL<br>
                        DOCUMENT

                    </div>

                </td>

            </tr>

        </table>



        {{-- TITLE --}}

        <div class="title">

            CLASS EXAMINATION MARK SHEET

        </div>


        <div class="subtitle">

            {{ $schoolClass?->name ?? '—' }}

            &nbsp; | &nbsp;

            {{ $exam?->name ?? '—' }}

            &nbsp; | &nbsp;

            {{ $academicYearName }}

            &nbsp; | &nbsp;

            {{ $termName }}

            @if($stream)

                &nbsp; | &nbsp;

                Stream:
                {{ $stream->name }}

            @endif

        </div>

    </div>



    {{-- =====================================================
         EXAMINATION DETAILS
    ===================================================== --}}

    <table class="details">

        <tr>

            <td class="label">
                Class
            </td>

            <td>
                {{ $schoolClass?->name ?? '—' }}
            </td>


            <td class="label">
                Examination
            </td>

            <td>
                {{ $exam?->name ?? '—' }}
            </td>


            <td class="label">
                Stream
            </td>

            <td>
                {{ $stream?->name ?? 'All Streams' }}
            </td>


            <td class="label">
                Students
            </td>

            <td>
                {{ $results->count() }}
            </td>


            <td class="label">
                Subjects
            </td>

            <td>
                {{ $subjects->count() }}
            </td>

        </tr>


        <tr>

            <td class="label">
                Academic Year
            </td>

            <td>
                {{ $academicYearName }}
            </td>


            <td class="label">
                Term
            </td>

            <td>
                {{ $termName }}
            </td>


            <td class="label">
                Date
            </td>

            <td>
                {{ now()->format('d M Y') }}
            </td>


            <td class="label">
                Status
            </td>

            <td colspan="3">
                Official
            </td>

        </tr>

    </table>



    {{-- =====================================================
         MARK SHEET
    ===================================================== --}}

    <table class="marks-table">

        <thead>

            <tr>

                <th rowspan="2">
                    #
                </th>


                <th rowspan="2" class="adm-no">
                    Adm No
                </th>


                <th rowspan="2" class="student-name">
                    Student Name
                </th>


                @if($subjects->isNotEmpty())

                    <th colspan="{{ $subjects->count() }}">
                        SUBJECTS
                    </th>

                @endif


                <th rowspan="2" class="total">
                    Total
                </th>


                <th rowspan="2" class="mean">
                    Mean
                </th>


                <th rowspan="2" class="grade">
                    Grade
                </th>


                <th rowspan="2" class="points">
                    Points
                </th>


                <th rowspan="2" class="position">
                    Position
                </th>

            </tr>


            <tr>

                @foreach($subjects as $subject)

                    <th class="subject">

                        {{ $subject->name }}

                    </th>

                @endforeach

            </tr>

        </thead>



        <tbody>

            @forelse($results as $index => $result)

                <tr>


                    {{-- NUMBER --}}

                    <td>
                        {{ $index + 1 }}
                    </td>



                    {{-- ADMISSION NUMBER --}}

                    <td>

                        {{ $result['student']->admission_number
                            ?? $result['student']->admission_no
                            ?? '—'
                        }}

                    </td>



                    {{-- STUDENT NAME --}}

                    <td class="student-name bold">

                        {{ $result['student']->first_name }}

                        @if(!empty($result['student']->middle_name))

                            {{ $result['student']->middle_name }}

                        @endif

                        {{ $result['student']->last_name }}

                    </td>



                    {{-- SUBJECT MARKS --}}

                    @foreach($subjects as $subject)

                        @php

                            $subjectResult =
                                $result['subjects'][$subject->id]
                                ?? null;

                        @endphp


                        <td>

                            @if(
                                $subjectResult &&
                                ($subjectResult['absent'] ?? false)
                            )

                                ABS

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

                    @endforeach



                    {{-- TOTAL --}}

                    <td class="bold">

                        {{ number_format(
                            $result['total'] ?? 0,
                            0
                        ) }}

                    </td>



                    {{-- MEAN --}}

                    <td class="bold">

                        @if($result['mean'] !== null)

                            {{ number_format(
                                $result['mean'],
                                2
                            ) }}

                        @else

                            —

                        @endif

                    </td>



                    {{-- GRADE --}}

                    <td class="bold">

                        @if(!empty($result['grade']))

                            @php

                                $resultGrade = $grades->firstWhere(
                                    'grade',
                                    $result['grade']
                                );

                            @endphp

                            <span
                                class="grade-badge"
                                @if($resultGrade?->color)
                                    style="color: {{ $resultGrade->color }};"
                                @endif
                            >

                                {{ $result['grade'] }}

                            </span>

                        @else

                            —

                        @endif

                    </td>



                    {{-- POINTS --}}

                    <td>

                        {{ number_format(
                            $result['points'] ?? 0,
                            0
                        ) }}

                    </td>



                    {{-- POSITION --}}

                    <td class="bold">

                        {{ $result['position'] ?? '—' }}

                    </td>

                </tr>

            @empty

                <tr>

                    <td
                        colspan="{{ 8 + $subjects->count() }}"
                        style="padding: 15px;"
                    >

                        No students found for the selected
                        examination/class/stream.

                    </td>

                </tr>

            @endforelse

        </tbody>

    </table>



    {{-- =====================================================
         SUMMARY
    ===================================================== --}}

    <div class="summary">

        <span>

            <strong>Students:</strong>

            {{ $results->count() }}

        </span>


        <span>

            <strong>Class:</strong>

            {{ $schoolClass?->name ?? '—' }}

        </span>


        @if($stream)

            <span>

                <strong>Stream:</strong>

                {{ $stream->name }}

            </span>

        @endif


        <span>

            <strong>Examination:</strong>

            {{ $exam?->name ?? '—' }}

        </span>


        <span>

            <strong>Academic Year:</strong>

            {{ $academicYearName }}

        </span>


        <span>

            <strong>Term:</strong>

            {{ $termName }}

        </span>

    </div>



    {{-- =====================================================
         GRADING SCALE
    ===================================================== --}}

    @if($grades->isNotEmpty())

        <div class="grading-section">

            <div class="grading-title">

                Grading Scale

            </div>


            <table class="grading-table">

                <thead>

                    <tr>

                        <th>
                            Grade
                        </th>

                        <th>
                            Minimum
                        </th>

                        <th>
                            Maximum
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

                    @foreach($grades as $grade)

                        <tr>

                            <td
                                class="grade-cell"
                                @if($grade->color)
                                    style="color: {{ $grade->color }};"
                                @endif
                            >

                                {{ $grade->grade }}

                            </td>


                            <td>

                                {{ number_format(
                                    $grade->min_mark,
                                    2
                                ) }}

                            </td>


                            <td>

                                {{ number_format(
                                    $grade->max_mark,
                                    2
                                ) }}

                            </td>


                            <td>

                                {{ number_format(
                                    $grade->points,
                                    0
                                ) }}

                            </td>


                            <td>

                                {{ $grade->remarks ?? '—' }}

                            </td>

                        </tr>

                    @endforeach

                </tbody>

            </table>

        </div>

    @endif



    {{-- =====================================================
         SIGNATURES
    ===================================================== --}}

    <table class="signatures">

        <tr>

            <td>

                <div class="signature-line"></div>

                <strong>
                    Class Teacher
                </strong>

                <br>

                Signature & Date

            </td>


            <td>

                <div class="signature-line"></div>

                <strong>
                    Examination Officer
                </strong>

                <br>

                Signature & Date

            </td>


            <td>

                <div class="signature-line"></div>

                <strong>
                    Principal / Head Teacher
                </strong>

                <br>

                Signature & Date

            </td>

        </tr>

    </table>



    {{-- =====================================================
         FOOTER
    ===================================================== --}}

    <div class="footer">

        {{ $school->name ?? 'School' }}

        &nbsp; | &nbsp;

        Examination Mark Sheet

        &nbsp; | &nbsp;

        Generated by ScholarERP

        &nbsp; | &nbsp;

        Printed:
        {{ now()->format('d M Y, H:i') }}

    </div>


</div>


</body>
</html>