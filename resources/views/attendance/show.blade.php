@extends('layouts.app')

@section('content')

<div class="container-fluid">

    {{-- Page Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h4 class="fw-bold mb-1">
                Attendance Details
            </h4>

            <p class="text-muted mb-0">
                View the attendance record for this student.
            </p>
        </div>

        <div class="d-flex gap-2">

            <a href="{{ route('attendance.index') }}"
               class="btn btn-secondary">

                <i class="bi bi-arrow-left"></i>
                Back

            </a>

            @can('attendance.edit')

                <a href="{{ route('attendance.edit', $attendance) }}"
                   class="btn btn-primary">

                    <i class="bi bi-pencil"></i>
                    Edit

                </a>

            @endcan

        </div>

    </div>


    {{-- Student Information --}}
    <div class="card shadow-sm mb-4">

        <div class="card-header bg-primary text-white">

            <h5 class="mb-0">
                <i class="bi bi-person"></i>
                Student Information
            </h5>

        </div>

        <div class="card-body">

            <div class="row">

                <div class="col-md-3 mb-3">

                    <small class="text-muted">
                        Admission Number
                    </small>

                    <div class="fw-bold">
                        {{ $attendance->student->admission_no ?? '-' }}
                    </div>

                </div>


                <div class="col-md-5 mb-3">

                    <small class="text-muted">
                        Student Name
                    </small>

                    <div class="fw-bold">

                        {{ $attendance->student->first_name ?? '' }}

                        {{ $attendance->student->middle_name ?? '' }}

                        {{ $attendance->student->last_name ?? '' }}

                    </div>

                </div>


                <div class="col-md-2 mb-3">

                    <small class="text-muted">
                        Class
                    </small>

                    <div class="fw-bold">
                        {{ $attendance->schoolClass->name ?? '-' }}
                    </div>

                </div>


                <div class="col-md-2 mb-3">

                    <small class="text-muted">
                        Stream
                    </small>

                    <div class="fw-bold">
                        {{ $attendance->stream->name ?? '-' }}
                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- Attendance Information --}}
    <div class="card shadow-sm">

        <div class="card-header">

            <h5 class="mb-0">
                Attendance Information
            </h5>

        </div>

        <div class="card-body">

            <div class="row">

                <div class="col-md-3 mb-4">

                    <small class="text-muted">
                        Academic Year
                    </small>

                    <div class="fw-bold">
                        {{ $attendance->academicYear->name ?? '-' }}
                    </div>

                </div>


                <div class="col-md-3 mb-4">

                    <small class="text-muted">
                        Term
                    </small>

                    <div class="fw-bold">
                        {{ $attendance->term->name ?? '-' }}
                    </div>

                </div>


                <div class="col-md-3 mb-4">

                    <small class="text-muted">
                        Attendance Date
                    </small>

                    <div class="fw-bold">

                        {{ $attendance->attendance_date
                            ? \Carbon\Carbon::parse($attendance->attendance_date)->format('d M Y')
                            : '-' }}

                    </div>

                </div>


                <div class="col-md-3 mb-4">

                    <small class="text-muted">
                        Status
                    </small>

                    <div>

                        @php

                            $badgeClass = match($attendance->status) {

                                'Present' => 'bg-success',

                                'Absent' => 'bg-danger',

                                'Late' => 'bg-warning text-dark',

                                'Excused' => 'bg-info text-dark',

                                'Sick' => 'bg-secondary',

                                default => 'bg-secondary',

                            };

                        @endphp

                        <span class="badge {{ $badgeClass }} fs-6">

                            {{ $attendance->status }}

                        </span>

                    </div>

                </div>


                <div class="col-md-4 mb-4">

                    <small class="text-muted">
                        Arrival Time
                    </small>

                    <div class="fw-bold">
                        {{ $attendance->arrival_time ?? 'Not recorded' }}
                    </div>

                </div>


                <div class="col-md-8 mb-4">

                    <small class="text-muted">
                        Remarks
                    </small>

                    <div class="fw-bold">
                        {{ $attendance->remarks ?? 'No remarks' }}
                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- Recording Information --}}
    <div class="card shadow-sm mt-4">

        <div class="card-body">

            <div class="row">

                <div class="col-md-6">

                    <small class="text-muted">
                        Recorded By
                    </small>

                    <div class="fw-bold">
                        {{ $attendance->user->name ?? 'System' }}
                    </div>

                </div>


                <div class="col-md-3">

                    <small class="text-muted">
                        Created
                    </small>

                    <div>

                        {{ $attendance->created_at
                            ? $attendance->created_at->format('d M Y H:i')
                            : '-' }}

                    </div>

                </div>


                <div class="col-md-3">

                    <small class="text-muted">
                        Last Updated
                    </small>

                    <div>

                        {{ $attendance->updated_at
                            ? $attendance->updated_at->format('d M Y H:i')
                            : '-' }}

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>

@endsection