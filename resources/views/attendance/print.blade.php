<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1"
    >

    <title>
        {{ $reportTitle }} - ScholarERP
    </title>


    <style>

        /* =========================================================
           GLOBAL
        ========================================================== */

        * {
            box-sizing: border-box;
        }

        html,
        body {
            margin: 0;
            padding: 0;
        }

        body {
            background: #f3f4f6;
            color: #222;
            font-family: Arial, Helvetica, sans-serif;
            font-size: 12px;
        }


        /* =========================================================
           PAGE
        ========================================================== */

        .page {
            width: 100%;
            max-width: 1600px;
            margin: 0 auto;
            padding: 25px;
        }


        /* =========================================================
           ACTION BUTTONS
        ========================================================== */

        .actions {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
        }

        .button {
            display: inline-block;
            padding: 9px 16px;
            border: 1px solid #333;
            border-radius: 4px;
            background: #333;
            color: #fff;
            text-decoration: none;
            cursor: pointer;
            font-size: 13px;
            font-family: Arial, Helvetica, sans-serif;
        }

        .button:hover {
            background: #111;
        }

        .button-light {
            background: #fff;
            color: #333;
        }

        .button-light:hover {
            background: #f1f1f1;
        }


        /* =========================================================
           REPORT
        ========================================================== */

        .report {
            background: #fff;
            padding: 25px;
            border: 1px solid #ddd;
        }


        /* =========================================================
           SCHOOL HEADER
        ========================================================== */

        .school-header {
            text-align: center;
            border-bottom: 2px solid #222;
            padding-bottom: 12px;
            margin-bottom: 15px;
        }

        .school-logo {
            max-width: 100px;
            max-height: 70px;
            object-fit: contain;
            margin-bottom: 5px;
        }

        .school-name {
            margin: 0;
            font-size: 24px;
            font-weight: 700;
        }

        .school-motto {
            margin: 3px 0;
            font-size: 12px;
            font-style: italic;
        }

        .school-contact {
            font-size: 11px;
            color: #555;
        }

        .report-title {
            margin-top: 10px;
            font-size: 19px;
            font-weight: 700;
            text-transform: uppercase;
        }


        /* =========================================================
           REPORT INFORMATION
        ========================================================== */

        .info-grid {
            display: grid;
            grid-template-columns: repeat(6, 1fr);
            gap: 7px;
            margin-bottom: 15px;
        }

        .info-box {
            border: 1px solid #ccc;
            padding: 7px 8px;
        }

        .info-label {
            display: block;
            font-size: 9px;
            text-transform: uppercase;
            color: #777;
            margin-bottom: 2px;
        }

        .info-value {
            font-size: 11px;
            font-weight: 700;
        }


        /* =========================================================
           PERIOD
        ========================================================== */

        .period {
            text-align: center;
            font-weight: 700;
            margin-bottom: 15px;
            font-size: 12px;
        }


        /* =========================================================
           SUMMARY
        ========================================================== */

        .summary {
            display: grid;
            grid-template-columns: repeat(7, 1fr);
            gap: 7px;
            margin-bottom: 18px;
        }

        .summary-box {
            border: 1px solid #ccc;
            padding: 8px;
            text-align: center;
        }

        .summary-label {
            display: block;
            font-size: 9px;
            text-transform: uppercase;
            color: #666;
            margin-bottom: 2px;
        }

        .summary-value {
            font-size: 17px;
            font-weight: 700;
        }


        /* =========================================================
           TABLE
        ========================================================== */

        .table-wrapper {
            width: 100%;
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 10px;
        }

        th,
        td {
            border: 1px solid #888;
            padding: 5px 4px;
            vertical-align: middle;
        }

        thead th {
            background: #eeeeee;
            font-weight: 700;
            text-align: center;
            white-space: nowrap;
        }

        tbody td {
            text-align: center;
        }

        tbody tr:nth-child(even) {
            background: #fafafa;
        }


        /* =========================================================
           TABLE COLUMNS
        ========================================================== */

        .number-column {
            width: 28px;
            min-width: 28px;
        }

        .admission-column {
            width: 80px;
            min-width: 80px;
            white-space: nowrap;
        }

        .student-column {
            min-width: 150px;
            text-align: left !important;
            white-space: nowrap;
        }

        .date-column {
            width: 35px;
            min-width: 35px;
            text-align: center;
        }

        .summary-column {
            width: 35px;
            min-width: 35px;
            font-weight: 700;
            text-align: center;
        }

        .percentage-column {
            width: 50px;
            min-width: 50px;
            font-weight: 700;
            text-align: center;
        }


        /* =========================================================
           DATE HEADER
        ========================================================== */

        .date-number {
            display: block;
            font-size: 11px;
            font-weight: 700;
        }

        .date-day {
            display: block;
            font-size: 8px;
            color: #666;
            text-transform: uppercase;
        }


        /* =========================================================
           STATUS
        ========================================================== */

        .status-code {
            font-weight: 700;
            font-size: 11px;
        }

        .empty {
            color: #aaa;
        }


        /* =========================================================
           LEGEND
        ========================================================== */

        .legend {
            display: flex;
            justify-content: center;
            gap: 18px;
            flex-wrap: wrap;
            border: 1px solid #ccc;
            margin-top: 12px;
            padding: 7px;
            font-size: 10px;
        }

        .legend-code {
            font-weight: 700;
            margin-right: 3px;
        }


        /* =========================================================
           FOOTER
        ========================================================== */

        .footer {
            display: flex;
            justify-content: space-between;
            border-top: 1px solid #999;
            margin-top: 20px;
            padding-top: 8px;
            color: #666;
            font-size: 9px;
        }


        /* =========================================================
           NO RECORDS
        ========================================================== */

        .no-records {
            text-align: center;
            padding: 35px;
            border: 1px solid #ccc;
            color: #777;
        }


        /* =========================================================
           PRINT
        ========================================================== */

        @media print {

            /* Hide buttons */
            .actions,
            .no-print {
                display: none !important;
            }


            /* Page */
            html,
            body {
                background: #fff !important;
                margin: 0;
                padding: 0;
            }

            body {
                font-size: 9px;
            }

            .page {
                width: 100%;
                max-width: none;
                margin: 0;
                padding: 0;
            }

            .report {
                width: 100%;
                border: none;
                padding: 0;
                margin: 0;
            }


            /* School header */
            .school-header {
                padding-bottom: 7px;
                margin-bottom: 8px;
                border-bottom: 2px solid #222;
            }

            .school-logo {
                max-height: 50px;
                max-width: 80px;
                margin-bottom: 3px;
            }

            .school-name {
                font-size: 19px;
            }

            .school-motto {
                font-size: 9px;
                margin: 2px 0;
            }

            .school-contact {
                font-size: 8px;
            }

            .report-title {
                font-size: 14px;
                margin-top: 5px;
            }


            /* Information */
            .info-grid {
                grid-template-columns: repeat(6, 1fr);
                gap: 3px;
                margin-bottom: 8px;
            }

            .info-box {
                padding: 4px;
            }

            .info-label {
                font-size: 7px;
            }

            .info-value {
                font-size: 8px;
            }


            /* Period */
            .period {
                font-size: 9px;
                margin-bottom: 8px;
            }


            /* Summary */
            .summary {
                grid-template-columns: repeat(7, 1fr);
                gap: 3px;
                margin-bottom: 8px;
            }

            .summary-box {
                padding: 4px;
            }

            .summary-label {
                font-size: 7px;
            }

            .summary-value {
                font-size: 11px;
            }


            /* Table */
            .table-wrapper {
                width: 100%;
                overflow: visible !important;
            }

            table {
                width: 100%;
                font-size: 7px;
                border-collapse: collapse;
            }

            th,
            td {
                padding: 3px 2px;
                border: 1px solid #888;
            }

            thead {
                display: table-header-group;
            }

            tbody {
                display: table-row-group;
            }

            tr {
                page-break-inside: avoid;
                break-inside: avoid;
            }


            /* Columns */
            .number-column {
                width: 20px;
                min-width: 20px;
            }

            .admission-column {
                width: 55px;
                min-width: 55px;
            }

            .student-column {
                min-width: 105px;
            }

            .date-column {
                width: 25px;
                min-width: 25px;
            }

            .summary-column {
                width: 27px;
                min-width: 27px;
            }

            .percentage-column {
                width: 35px;
                min-width: 35px;
            }


            /* Date */
            .date-number {
                font-size: 8px;
            }

            .date-day {
                font-size: 6px;
            }


            /* Status */
            .status-code {
                font-size: 8px;
            }


            /* Legend */
            .legend {
                margin-top: 6px;
                padding: 4px;
                gap: 10px;
                font-size: 7px;
            }


            /* Footer */
            .footer {
                margin-top: 8px;
                padding-top: 4px;
                font-size: 7px;
            }


            /* Page setup */
            @page {
                size: A4 landscape;
                margin: 7mm;
            }
        }


        /* =========================================================
           MOBILE
        ========================================================== */

        @media screen and (max-width: 900px) {

            .page {
                padding: 10px;
            }

            .report {
                padding: 12px;
            }

            .info-grid {
                grid-template-columns: repeat(2, 1fr);
            }

            .summary {
                grid-template-columns: repeat(3, 1fr);
            }

            .actions {
                flex-direction: column;
                gap: 8px;
                align-items: stretch;
            }

        }

    </style>

</head>


<body>


<div class="page">


    {{-- =========================================================
         ACTION BUTTONS
    ========================================================== --}}

    <div class="actions">

        <a
            href="{{ route('attendance.index') }}"
            class="button button-light"
        >
            ← Back to Attendance
        </a>


        <button
            type="button"
            id="printReportButton"
            class="button no-print"
        >
            🖨 Print Report
        </button>

    </div>


    {{-- =========================================================
         REPORT
    ========================================================== --}}

    <div class="report">


        {{-- =====================================================
             SCHOOL HEADER
        ====================================================== --}}

        @php

            $school = \App\Models\School::first();

        @endphp


        <div class="school-header">


            {{-- SCHOOL LOGO --}}

            @if($school?->logo)

                <img
                    src="{{ asset('storage/' . $school->logo) }}"
                    alt="School Logo"
                    class="school-logo"
                >

            @endif


            {{-- SCHOOL NAME --}}

            <h1 class="school-name">

                {{ $school?->name ?? 'ScholarERP' }}

            </h1>


            {{-- SCHOOL MOTTO --}}

            @if($school?->motto)

                <div class="school-motto">

                    {{ $school->motto }}

                </div>

            @endif


            {{-- SCHOOL CONTACT --}}

            <div class="school-contact">

                @if($school?->address)

                    {{ $school->address }}

                @endif


                @if($school?->phone)

                    @if($school?->address)
                        |
                    @endif

                    Tel:
                    {{ $school->phone }}

                @endif


                @if($school?->email)

                    @if($school?->address || $school?->phone)
                        |
                    @endif

                    {{ $school->email }}

                @endif

            </div>


            {{-- REPORT TITLE --}}

            <div class="report-title">

                {{ $reportTitle }}

            </div>

        </div>


        {{-- =====================================================
             REPORT INFORMATION
        ====================================================== --}}

        <div class="info-grid">


            <div class="info-box">

                <span class="info-label">
                    Academic Year
                </span>

                <span class="info-value">

                    {{ $year?->name ?? 'All Years' }}

                </span>

            </div>


            <div class="info-box">

                <span class="info-label">
                    Term
                </span>

                <span class="info-value">

                    {{ $term?->name ?? 'All Terms' }}

                </span>

            </div>


            <div class="info-box">

                <span class="info-label">
                    Class
                </span>

                <span class="info-value">

                    {{ $class?->name ?? '—' }}

                </span>

            </div>


            <div class="info-box">

                <span class="info-label">
                    Stream
                </span>

                <span class="info-value">

                    {{ $stream?->name ?? 'All Streams' }}

                </span>

            </div>


            <div class="info-box">

                <span class="info-label">
                    Start Date
                </span>

                <span class="info-value">

                    {{ $startDate->format('d/m/Y') }}

                </span>

            </div>


            <div class="info-box">

                <span class="info-label">
                    End Date
                </span>

                <span class="info-value">

                    {{ $endDate->format('d/m/Y') }}

                </span>

            </div>

        </div>


        {{-- =====================================================
             PERIOD
        ====================================================== --}}

        <div class="period">

            @if($reportType === 'daily')

                Attendance for
                {{ $startDate->format('l, d F Y') }}

            @elseif($reportType === 'weekly')

                Week:
                {{ $startDate->format('d F Y') }}
                -
                {{ $endDate->format('d F Y') }}

            @elseif($reportType === 'monthly')

                Month:
                {{ $startDate->format('F Y') }}

            @else

                Period:
                {{ $startDate->format('d F Y') }}
                -
                {{ $endDate->format('d F Y') }}

            @endif

        </div>


        {{-- =====================================================
             SUMMARY
        ====================================================== --}}

        <div class="summary">


            <div class="summary-box">

                <span class="summary-label">
                    Students
                </span>

                <span class="summary-value">
                    {{ $students->count() }}
                </span>

            </div>


            <div class="summary-box">

                <span class="summary-label">
                    Present
                </span>

                <span class="summary-value">
                    {{ $stats['present'] }}
                </span>

            </div>


            <div class="summary-box">

                <span class="summary-label">
                    Absent
                </span>

                <span class="summary-value">
                    {{ $stats['absent'] }}
                </span>

            </div>


            <div class="summary-box">

                <span class="summary-label">
                    Late
                </span>

                <span class="summary-value">
                    {{ $stats['late'] }}
                </span>

            </div>


            <div class="summary-box">

                <span class="summary-label">
                    Excused
                </span>

                <span class="summary-value">
                    {{ $stats['excused'] }}
                </span>

            </div>


            <div class="summary-box">

                <span class="summary-label">
                    Sick
                </span>

                <span class="summary-value">
                    {{ $stats['sick'] }}
                </span>

            </div>


            <div class="summary-box">

                <span class="summary-label">
                    Attendance Rate
                </span>

                <span class="summary-value">
                    {{ $attendanceRate }}%
                </span>

            </div>

        </div>


        {{-- =====================================================
             STUDENT ATTENDANCE TABLE
        ====================================================== --}}

        @if($students->count())


            <div class="table-wrapper">

                <table>

                    <thead>

                        <tr>

                            <th
                                rowspan="2"
                                class="number-column"
                            >
                                #
                            </th>


                            <th
                                rowspan="2"
                                class="admission-column"
                            >
                                Admission No.
                            </th>


                            <th
                                rowspan="2"
                                class="student-column"
                            >
                                Student Name
                            </th>


                            @foreach($dates as $date)

                                <th
                                    rowspan="2"
                                    class="date-column"
                                >

                                    <span class="date-number">
                                        {{ $date->format('d') }}
                                    </span>

                                    <span class="date-day">
                                        {{ $date->format('D') }}
                                    </span>

                                </th>

                            @endforeach


                            <th colspan="6">
                                Summary
                            </th>

                        </tr>


                        <tr>

                            <th class="summary-column">
                                P
                            </th>

                            <th class="summary-column">
                                A
                            </th>

                            <th class="summary-column">
                                L
                            </th>

                            <th class="summary-column">
                                E
                            </th>

                            <th class="summary-column">
                                S
                            </th>

                            <th class="percentage-column">
                                %
                            </th>

                        </tr>

                    </thead>


                    <tbody>


                        @foreach($students as $student)


                            @php

                                $studentStat =
                                    $studentStats[$student->id]
                                    ?? [
                                        'present' => 0,
                                        'absent' => 0,
                                        'late' => 0,
                                        'excused' => 0,
                                        'sick' => 0,
                                        'recorded' => 0,
                                        'rate' => 0,
                                    ];


                                $studentName =
                                    trim(
                                        ($student->first_name ?? '') .
                                        ' ' .
                                        ($student->middle_name ?? '') .
                                        ' ' .
                                        ($student->last_name ?? '')
                                    );

                            @endphp


                            <tr>


                                {{-- NUMBER --}}

                                <td>
                                    {{ $loop->iteration }}
                                </td>


                                {{-- ADMISSION NUMBER --}}

                                <td>
                                    {{ $student->admission_no ?? '—' }}
                                </td>


                                {{-- STUDENT NAME --}}

                                <td class="student-column">
                                    {{ $studentName ?: 'Unnamed Student' }}
                                </td>


                                {{-- DAILY ATTENDANCE --}}

                                @foreach($dates as $date)


                                    @php

                                        $key =
                                            $student->id .
                                            '_' .
                                            $date->format('Y-m-d');


                                        $attendance =
                                            $attendanceMap[$key]
                                            ?? null;


                                        $status =
                                            strtolower(
                                                trim(
                                                    $attendance?->status ?? ''
                                                )
                                            );

                                    @endphp


                                    <td>

                                        @if($status === 'present')

                                            <span class="status-code">
                                                P
                                            </span>

                                        @elseif($status === 'absent')

                                            <span class="status-code">
                                                A
                                            </span>

                                        @elseif($status === 'late')

                                            <span class="status-code">
                                                L
                                            </span>

                                        @elseif($status === 'excused')

                                            <span class="status-code">
                                                E
                                            </span>

                                        @elseif($status === 'sick')

                                            <span class="status-code">
                                                S
                                            </span>

                                        @else

                                            <span class="status-code empty">
                                                —
                                            </span>

                                        @endif

                                    </td>

                                @endforeach


                                {{-- PRESENT --}}

                                <td class="summary-column">
                                    {{ $studentStat['present'] }}
                                </td>


                                {{-- ABSENT --}}

                                <td class="summary-column">
                                    {{ $studentStat['absent'] }}
                                </td>


                                {{-- LATE --}}

                                <td class="summary-column">
                                    {{ $studentStat['late'] }}
                                </td>


                                {{-- EXCUSED --}}

                                <td class="summary-column">
                                    {{ $studentStat['excused'] }}
                                </td>


                                {{-- SICK --}}

                                <td class="summary-column">
                                    {{ $studentStat['sick'] }}
                                </td>


                                {{-- PERCENTAGE --}}

                                <td class="percentage-column">
                                    {{ $studentStat['rate'] }}%
                                </td>


                            </tr>

                        @endforeach


                    </tbody>

                </table>

            </div>


        @else


            <div class="no-records">

                No students were found for the selected
                class and stream.

            </div>

        @endif


        {{-- =====================================================
             LEGEND
        ====================================================== --}}

        <div class="legend">

            <span>
                <span class="legend-code">P</span>
                Present
            </span>

            <span>
                <span class="legend-code">A</span>
                Absent
            </span>

            <span>
                <span class="legend-code">L</span>
                Late
            </span>

            <span>
                <span class="legend-code">E</span>
                Excused
            </span>

            <span>
                <span class="legend-code">S</span>
                Sick
            </span>

            <span>
                <span class="legend-code">—</span>
                No Record
            </span>

        </div>


        {{-- =====================================================
             FOOTER
        ====================================================== --}}

        <div class="footer">

            <div>
                ScholarERP — Attendance Management
            </div>

            <div>
                Generated:
                {{ now()->format('d/m/Y H:i') }}
            </div>

            <div>
                Students:
                {{ $students->count() }}
            </div>

        </div>


    </div>

</div>


{{-- =============================================================
     PRINT JAVASCRIPT
============================================================== --}}

<script>

    document.addEventListener('DOMContentLoaded', function () {

        const printButton =
            document.getElementById('printReportButton');


        if (!printButton) {
            console.error(
                'Print button was not found.'
            );

            return;
        }


        printButton.addEventListener('click', function () {

            window.print();

        });

    });

</script>


</body>

</html>