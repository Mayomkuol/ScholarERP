@extends('layouts.app')

@section('title', 'Student Attendance')

@section('content')

<div class="container-fluid">

    <!-- Student Information -->
    <div class="card shadow-sm mb-4">

        <div class="card-header bg-primary text-white d-flex justify-content-between">

            <h4 class="mb-0">
                Student Attendance Profile
            </h4>

            <div>

                <a href="{{ route('attendance.index') }}" class="btn btn-light btn-sm">
                    <i class="bi bi-arrow-left"></i>
                    Back
                </a>

                <button onclick="window.print()" class="btn btn-success btn-sm">
                    <i class="bi bi-printer"></i>
                    Print
                </button>

            </div>

        </div>

        <div class="card-body">

            <div class="row">

                <div class="col-md-3">
                    <strong>Admission No</strong><br>
                    {{ $student->admission_no }}
                </div>

                <div class="col-md-3">
                    <strong>Student Name</strong><br>
                    {{ $student->first_name }}
                    {{ $student->middle_name }}
                    {{ $student->last_name }}
                </div>

                <div class="col-md-2">
                    <strong>Class</strong><br>
                    {{ optional($student->schoolClass)->name }}
                </div>

                <div class="col-md-2">
                    <strong>Stream</strong><br>
                    {{ optional($student->stream)->name ?? '-' }}
                </div>

                <div class="col-md-2">
                    <strong>Academic Year</strong><br>
                    {{ optional($student->academicYear)->name }}
                </div>

            </div>

        </div>

    </div>

    @php

        $present = $attendance->where('status','Present')->count();
        $absent = $attendance->where('status','Absent')->count();
        $late = $attendance->where('status','Late')->count();
        $excused = $attendance->where('status','Excused')->count();
        $sick = $attendance->where('status','Sick')->count();

        $total = $attendance->total();

        $percentage = $total > 0
            ? round(($present / $total) * 100,2)
            : 0;

    @endphp

    <!-- Summary -->

    <div class="row mb-4">

        <div class="col-md-2">

            <div class="card border-success text-center shadow-sm">

                <div class="card-body">

                    <h6>Present</h6>

                    <h3 class="text-success">
                        {{ $present }}
                    </h3>

                </div>

            </div>

        </div>

        <div class="col-md-2">

            <div class="card border-danger text-center shadow-sm">

                <div class="card-body">

                    <h6>Absent</h6>

                    <h3 class="text-danger">
                        {{ $absent }}
                    </h3>

                </div>

            </div>

        </div>

        <div class="col-md-2">

            <div class="card border-warning text-center shadow-sm">

                <div class="card-body">

                    <h6>Late</h6>

                    <h3 class="text-warning">
                        {{ $late }}
                    </h3>

                </div>

            </div>

        </div>

        <div class="col-md-2">

            <div class="card border-info text-center shadow-sm">

                <div class="card-body">

                    <h6>Excused</h6>

                    <h3 class="text-info">
                        {{ $excused }}
                    </h3>

                </div>

            </div>

        </div>

        <div class="col-md-2">

            <div class="card border-secondary text-center shadow-sm">

                <div class="card-body">

                    <h6>Sick</h6>

                    <h3>
                        {{ $sick }}
                    </h3>

                </div>

            </div>

        </div>

        <div class="col-md-2">

            <div class="card border-primary text-center shadow-sm">

                <div class="card-body">

                    <h6>Attendance %</h6>

                    <h3 class="text-primary">

                        {{ $percentage }}%

                    </h3>

                </div>

            </div>

        </div>

    </div>

    <!-- Attendance History -->

    <div class="card shadow-sm">

        <div class="card-header bg-light">

            <h5 class="mb-0">

                Attendance History

            </h5>

        </div>

        <div class="card-body">

            <div class="table-responsive">

                <table class="table table-bordered table-hover align-middle">

                    <thead class="table-dark">

                        <tr>

                            <th>#</th>
                            <th>Date</th>
                            <th>Day</th>
                            <th>Academic Year</th>
                            <th>Term</th>
                            <th>Class</th>
                            <th>Stream</th>
                            <th>Status</th>
                            <th>Recorded By</th>

                        </tr>

                    </thead>

                    <tbody>

                    @forelse($attendance as $record)

                        <tr>

                            <td>

                                {{ $loop->iteration + ($attendance->firstItem() - 1) }}

                            </td>

                            <td>

                                {{ \Carbon\Carbon::parse($record->attendance_date)->format('d M Y') }}

                            </td>

                            <td>

                                {{ \Carbon\Carbon::parse($record->attendance_date)->format('l') }}

                            </td>

                            <td>

                                {{ optional($record->academicYear)->name }}

                            </td>

                            <td>

                                {{ optional($record->term)->name }}

                            </td>

                            <td>

                                {{ optional($record->schoolClass)->name }}

                            </td>

                            <td>

                                {{ optional($record->stream)->name ?? '-' }}

                            </td>

                            <td>

                                @php

                                    $badge = match($record->status){

                                        'Present' => 'success',

                                        'Absent' => 'danger',

                                        'Late' => 'warning',

                                        'Excused' => 'info',

                                        'Sick' => 'secondary',

                                        default => 'dark'

                                    };

                                @endphp

                                <span class="badge bg-{{ $badge }}">

                                    {{ $record->status }}

                                </span>

                            </td>

                            <td>

                                {{ optional($record->user)->name }}

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td colspan="9" class="text-center py-4">

                                No attendance records found.

                            </td>

                        </tr>

                    @endforelse

                    </tbody>

                </table>

            </div>

            <div class="mt-3">

                {{ $attendance->links() }}

            </div>

        </div>

    </div>

</div>

@endsection