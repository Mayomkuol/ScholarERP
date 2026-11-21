@extends('layouts.app')

@section('content')

<div class="container-fluid">

    {{-- ============================================================
        PAGE HEADER
    ============================================================ --}}
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h3 class="mb-1">
                <i class="bi bi-person-badge"></i>
                Teacher Profile
            </h3>

            <p class="text-muted mb-0">
                View teacher information and teaching assignments.
            </p>
        </div>

        <div class="d-flex gap-2">

            <a href="{{ route('teachers.index') }}"
               class="btn btn-secondary">

                <i class="bi bi-arrow-left"></i>
                Back to Teachers

            </a>

            <a href="{{ route('teachers.edit', $teacher->id) }}"
               class="btn btn-warning">

                <i class="bi bi-pencil"></i>
                Edit Teacher

            </a>

        </div>

    </div>


    {{-- ============================================================
        TEACHER SUMMARY
    ============================================================ --}}
    <div class="card shadow-sm mb-4">

        <div class="card-body">

            <div class="row align-items-center">

                {{-- =================================================
                    TEACHER PHOTO
                ================================================= --}}
                <div class="col-md-2 text-center mb-3 mb-md-0">

                    @if($teacher->photo)

                        <img src="{{ asset('storage/' . $teacher->photo) }}"
                             alt="{{ $teacher->first_name }}"
                             class="rounded-circle img-thumbnail"
                             style="
                                width: 150px;
                                height: 150px;
                                object-fit: cover;
                             ">

                    @else

                        <div class="rounded-circle bg-secondary text-white
                                    d-flex align-items-center justify-content-center
                                    mx-auto"
                             style="
                                width: 150px;
                                height: 150px;
                                font-size: 50px;
                             ">

                            {{ strtoupper(substr($teacher->first_name, 0, 1)) }}

                        </div>

                    @endif

                </div>


                {{-- =================================================
                    BASIC TEACHER INFORMATION
                ================================================= --}}
                <div class="col-md-7">

                    <h3 class="mb-1">

                        {{ $teacher->first_name }}

                        @if($teacher->middle_name)
                            {{ $teacher->middle_name }}
                        @endif

                        {{ $teacher->last_name }}

                    </h3>

                    <p class="text-muted mb-2">

                        {{ $teacher->designation ?? 'Teacher' }}

                    </p>


                    {{-- BADGES --}}

                    <div class="d-flex flex-wrap gap-2">

                        <span class="badge bg-primary">

                            {{ $teacher->employee_no }}

                        </span>


                        @if($teacher->employment_type)

                            <span class="badge bg-info">

                                {{ $teacher->employment_type }}

                            </span>

                        @endif


                        @if($teacher->status)

                            <span class="badge bg-success">

                                <i class="bi bi-check-circle"></i>

                                Active

                            </span>

                        @else

                            <span class="badge bg-danger">

                                <i class="bi bi-x-circle"></i>

                                Inactive

                            </span>

                        @endif

                    </div>

                </div>


                {{-- =================================================
                    SYSTEM ACCOUNT
                ================================================= --}}
                <div class="col-md-3 mt-3 mt-md-0">

                    <div class="border rounded p-3">

                        <h6 class="text-muted mb-3">

                            <i class="bi bi-person-lock"></i>

                            System Account

                        </h6>


                        @if($teacher->user)

                            <div class="fw-bold">

                                {{ $teacher->user->name }}

                            </div>

                            <div class="small text-muted">

                                {{ $teacher->user->email }}

                            </div>


                            <div class="mt-2">

                                @if($teacher->user->status)

                                    <span class="badge bg-success">

                                        Active

                                    </span>

                                @else

                                    <span class="badge bg-danger">

                                        Inactive

                                    </span>

                                @endif

                            </div>

                        @else

                            <span class="badge bg-secondary">

                                No Login Account

                            </span>

                        @endif

                    </div>

                </div>

            </div>

        </div>

    </div>



    {{-- ============================================================
        INFORMATION CARDS
    ============================================================ --}}
    <div class="row">


        {{-- ========================================================
            PERSONAL INFORMATION
        ======================================================== --}}
        <div class="col-lg-6 mb-4">

            <div class="card shadow-sm h-100">

                <div class="card-header">

                    <h5 class="mb-0">

                        <i class="bi bi-person"></i>

                        Personal Information

                    </h5>

                </div>


                <div class="card-body">


                    {{-- Gender --}}

                    <div class="row mb-3">

                        <div class="col-sm-5 text-muted">

                            Gender

                        </div>

                        <div class="col-sm-7 fw-semibold">

                            {{ $teacher->gender ?: '—' }}

                        </div>

                    </div>


                    {{-- Date of Birth --}}

                    <div class="row mb-3">

                        <div class="col-sm-5 text-muted">

                            Date of Birth

                        </div>

                        <div class="col-sm-7 fw-semibold">

                            @if($teacher->date_of_birth)

                                {{ \Carbon\Carbon::parse($teacher->date_of_birth)->format('d F Y') }}

                            @else

                                —

                            @endif

                        </div>

                    </div>


                    {{-- National ID --}}

                    <div class="row mb-3">

                        <div class="col-sm-5 text-muted">

                            National ID

                        </div>

                        <div class="col-sm-7 fw-semibold">

                            {{ $teacher->national_id ?: '—' }}

                        </div>

                    </div>


                    {{-- Phone --}}

                    <div class="row mb-3">

                        <div class="col-sm-5 text-muted">

                            Phone

                        </div>

                        <div class="col-sm-7 fw-semibold">

                            {{ $teacher->phone ?: '—' }}

                        </div>

                    </div>


                    {{-- Email --}}

                    <div class="row mb-3">

                        <div class="col-sm-5 text-muted">

                            Email

                        </div>

                        <div class="col-sm-7 fw-semibold">

                            {{ $teacher->email ?: '—' }}

                        </div>

                    </div>


                    {{-- Address --}}

                    <div class="row">

                        <div class="col-sm-5 text-muted">

                            Address

                        </div>

                        <div class="col-sm-7 fw-semibold">

                            {{ $teacher->address ?: '—' }}

                        </div>

                    </div>

                </div>

            </div>

        </div>



        {{-- ========================================================
            EMPLOYMENT INFORMATION
        ======================================================== --}}
        <div class="col-lg-6 mb-4">

            <div class="card shadow-sm h-100">

                <div class="card-header">

                    <h5 class="mb-0">

                        <i class="bi bi-briefcase"></i>

                        Employment Information

                    </h5>

                </div>


                <div class="card-body">


                    {{-- Employee Number --}}

                    <div class="row mb-3">

                        <div class="col-sm-5 text-muted">

                            Employee No.

                        </div>

                        <div class="col-sm-7 fw-semibold">

                            {{ $teacher->employee_no }}

                        </div>

                    </div>


                    {{-- Department --}}

                    <div class="row mb-3">

                        <div class="col-sm-5 text-muted">

                            Department

                        </div>

                        <div class="col-sm-7 fw-semibold">

                            {{ $teacher->department->name ?? '—' }}

                        </div>

                    </div>


                    {{-- Designation --}}

                    <div class="row mb-3">

                        <div class="col-sm-5 text-muted">

                            Designation

                        </div>

                        <div class="col-sm-7 fw-semibold">

                            {{ $teacher->designation ?: '—' }}

                        </div>

                    </div>


                    {{-- Employment Type --}}

                    <div class="row mb-3">

                        <div class="col-sm-5 text-muted">

                            Employment Type

                        </div>

                        <div class="col-sm-7 fw-semibold">

                            {{ $teacher->employment_type ?: '—' }}

                        </div>

                    </div>


                    {{-- Joining Date --}}

                    <div class="row mb-3">

                        <div class="col-sm-5 text-muted">

                            Joining Date

                        </div>

                        <div class="col-sm-7 fw-semibold">

                            @if($teacher->joining_date)

                                {{ \Carbon\Carbon::parse($teacher->joining_date)->format('d F Y') }}

                            @else

                                —

                            @endif

                        </div>

                    </div>


                    {{-- Qualification --}}

                    <div class="row mb-3">

                        <div class="col-sm-5 text-muted">

                            Qualification

                        </div>

                        <div class="col-sm-7 fw-semibold">

                            {{ $teacher->qualification ?: '—' }}

                        </div>

                    </div>


                    {{-- Specialization --}}

                    <div class="row mb-3">

                        <div class="col-sm-5 text-muted">

                            Specialization

                        </div>

                        <div class="col-sm-7 fw-semibold">

                            {{ $teacher->specialization ?: '—' }}

                        </div>

                    </div>


                    {{-- Salary --}}

                    <div class="row">

                        <div class="col-sm-5 text-muted">

                            Salary

                        </div>

                        <div class="col-sm-7 fw-semibold">

                            @if($teacher->salary !== null)

                                {{ number_format($teacher->salary, 2) }}

                            @else

                                —

                            @endif

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>
{{-- ========================================================= --}}
{{-- CLASS TEACHER ASSIGNMENTS --}}
{{-- ========================================================= --}}

<div class="card shadow-sm mb-4">

    <div class="card-header d-flex justify-content-between align-items-center">

        <h5 class="mb-0">
            <i class="bi bi-people"></i>
            Class Teacher Assignments
        </h5>

        <span class="badge bg-success">
            {{ $teacher->classTeacherAssignments->count() }}
            {{ $teacher->classTeacherAssignments->count() == 1 ? 'Assignment' : 'Assignments' }}
        </span>

    </div>

    <div class="card-body p-0">

        @if($teacher->classTeacherAssignments->count())

            <div class="table-responsive">

                <table class="table table-hover mb-0">

                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Class</th>
                            <th>Stream</th>
                            <th>Academic Year</th>
                            <th>Term</th>
                            <th>Status</th>
                            <th>Assigned Date</th>
                        </tr>
                    </thead>

                    <tbody>

                        @foreach($teacher->classTeacherAssignments as $assignment)

                            <tr>

                                <td>
                                    {{ $loop->iteration }}
                                </td>

                                <td class="fw-semibold">
                                    {{ $assignment->schoolClass?->name ?? '—' }}
                                </td>

                                <td>
                                    {{ $assignment->stream?->name ?? 'Whole Class' }}

                                    @if($assignment->stream?->code)
                                        ({{ $assignment->stream->code }})
                                    @endif
                                </td>

                                <td>
                                    {{ $assignment->academicYear?->name ?? '—' }}
                                </td>

                                <td>
                                    {{ $assignment->term?->name ?? '—' }}
                                </td>

                                <td>

                                    @if($assignment->status === 'Active')

                                        <span class="badge bg-success">
                                            Active
                                        </span>

                                    @else

                                        <span class="badge bg-secondary">
                                            {{ $assignment->status }}
                                        </span>

                                    @endif

                                </td>

                                <td>
                                    {{ $assignment->assigned_at
                                        ? $assignment->assigned_at->format('d F Y')
                                        : '—'
                                    }}
                                </td>

                            </tr>

                        @endforeach

                    </tbody>

                </table>

            </div>

        @else

            <div class="p-4 text-center text-muted">

                <i class="bi bi-people fs-2 d-block mb-2"></i>

                This teacher has no class teacher assignments.

            </div>

        @endif

    </div>

</div>


    {{-- ============================================================
        TEACHING ALLOCATIONS
    ============================================================ --}}
    <div class="card shadow-sm mb-4">

        <div class="card-header d-flex justify-content-between align-items-center">

            <h5 class="mb-0">

                <i class="bi bi-book"></i>

                Teaching Allocations

            </h5>


            <span class="badge bg-primary">

                {{ $teacher->allocations->count() }}

                {{ $teacher->allocations->count() == 1 ? 'Allocation' : 'Allocations' }}

            </span>

        </div>


        <div class="card-body p-0">


            @if($teacher->allocations->count())


                <div class="table-responsive">

                    <table class="table table-hover table-bordered mb-0">

                        <thead class="table-light">

                            <tr>

                                <th style="width: 60px;">
                                    #
                                </th>

                                <th>
                                    Subject
                                </th>

                                <th>
                                    Class
                                </th>

                                <th>
                                    Stream
                                </th>

                                <th>
                                    Academic Year
                                </th>

                                <th>
                                    Term
                                </th>

                            </tr>

                        </thead>


                        <tbody>

                            @foreach($teacher->allocations as $allocation)

                                <tr>

                                    {{-- Number --}}

                                    <td>

                                        {{ $loop->iteration }}

                                    </td>


                                    {{-- Subject --}}

                                    <td>

                                        @if($allocation->subject)

                                            <strong>

                                                {{ $allocation->subject->name }}

                                            </strong>

                                            @if($allocation->subject->code)

                                                <br>

                                                <small class="text-muted">

                                                    {{ $allocation->subject->code }}

                                                </small>

                                            @endif

                                        @else

                                            —

                                        @endif

                                    </td>


                                    {{-- Class --}}

                                    <td>

                                        {{ $allocation->schoolClass->name ?? '—' }}

                                    </td>


                                    {{-- Stream --}}

                                    <td>

                                        @if($allocation->stream)

                                            {{ $allocation->stream->name }}

                                            @if($allocation->stream->code)

                                                <small class="text-muted">

                                                    ({{ $allocation->stream->code }})

                                                </small>

                                            @endif

                                        @else

                                            <span class="text-muted">

                                                All Streams

                                            </span>

                                        @endif

                                    </td>


                                    {{-- Academic Year --}}

                                    <td>

                                        {{ $allocation->academicYear->name ?? '—' }}

                                    </td>


                                    {{-- Term --}}

                                    <td>

                                        {{ $allocation->term->name ?? '—' }}

                                    </td>

                                </tr>

                            @endforeach

                        </tbody>

                    </table>

                </div>


            @else


                {{-- No allocations --}}

                <div class="text-center py-5">

                    <i class="bi bi-book fs-1 text-muted"></i>

                    <h6 class="mt-3">

                        No Teaching Allocations

                    </h6>

                    <p class="text-muted mb-3">

                        This teacher has not been assigned any subjects yet.

                    </p>

                    <a href="{{ route('teacher-allocations.create') }}"
                       class="btn btn-primary">

                        <i class="bi bi-plus-circle"></i>

                        Assign Subject

                    </a>

                </div>


            @endif

        </div>

    </div>


</div>

@endsection