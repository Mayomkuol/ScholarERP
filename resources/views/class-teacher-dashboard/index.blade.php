@extends('layouts.app')

@section('content')

<div class="container-fluid">

    {{-- =========================================================
        PAGE HEADER
    ========================================================== --}}

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h4 class="fw-bold mb-1">
                Class Teacher Dashboard
            </h4>

            <p class="text-muted mb-0">
                Manage and monitor your assigned class.
            </p>
        </div>

        @if($assignment)
            <span class="badge bg-success px-3 py-2">
                Active Class Teacher
            </span>
        @endif

    </div>


    {{-- =========================================================
        NO ASSIGNMENT
    ========================================================== --}}

    @if(!$assignment)

        <div class="card border-0 shadow-sm">

            <div class="card-body text-center py-5">

                <div class="mb-3">
                    <i class="bi bi-person-x fs-1 text-muted"></i>
                </div>

                <h5 class="fw-bold">
                    No Class Assigned
                </h5>

                <p class="text-muted mb-0">
                    You currently do not have an active class teacher
                    assignment for the current academic year and term.
                </p>

            </div>

        </div>

    @else

        {{-- =====================================================
            WELCOME / ASSIGNMENT INFORMATION
        ====================================================== --}}

        <div class="card border-0 shadow-sm mb-4">

            <div class="card-body">

                <div class="row align-items-center">

                    {{-- Teacher --}}

                    <div class="col-md-4 mb-3 mb-md-0">

                        <div class="d-flex align-items-center">

                            @if($teacher->photo)

                                <img
                                    src="{{ asset('storage/' . $teacher->photo) }}"
                                    alt="{{ $teacher->first_name }}"
                                    class="rounded-circle me-3"
                                    width="65"
                                    height="65"
                                    style="object-fit: cover;"
                                >

                            @else

                                <div
                                    class="rounded-circle bg-light d-flex align-items-center justify-content-center me-3"
                                    style="width:65px;height:65px;"
                                >
                                    <i class="bi bi-person fs-3 text-muted"></i>
                                </div>

                            @endif

                            <div>

                                <div class="text-muted small">
                                    Class Teacher
                                </div>

                                <h5 class="fw-bold mb-0">

                                    {{ $teacher->first_name }}

                                    @if($teacher->middle_name)
                                        {{ $teacher->middle_name }}
                                    @endif

                                    {{ $teacher->last_name }}

                                </h5>

                                <small class="text-muted">
                                    {{ $teacher->employee_no }}
                                </small>

                            </div>

                        </div>

                    </div>


                    {{-- Class --}}

                    <div class="col-md-2 mb-3 mb-md-0">

                        <div class="text-muted small">
                            Class
                        </div>

                        <h5 class="fw-bold mb-0">
                            {{ $assignment->schoolClass->name }}
                        </h5>

                    </div>


                    {{-- Stream --}}

                    <div class="col-md-2 mb-3 mb-md-0">

                        <div class="text-muted small">
                            Stream
                        </div>

                        <h5 class="fw-bold mb-0">

                            @if($assignment->stream)
                                {{ $assignment->stream->name }}
                            @else
                                Whole Class
                            @endif

                        </h5>

                    </div>


                    {{-- Academic Year --}}

                    <div class="col-md-2 mb-3 mb-md-0">

                        <div class="text-muted small">
                            Academic Year
                        </div>

                        <h6 class="fw-bold mb-0">
                            {{ $assignment->academicYear->name }}
                        </h6>

                        <small class="text-muted">
                            {{ $assignment->term->name }}
                        </small>

                    </div>


                    {{-- Status --}}

                    <div class="col-md-2 text-md-end">

                        <span class="badge bg-success">
                            {{ $assignment->status }}
                        </span>

                    </div>

                </div>

            </div>

        </div>


        {{-- =====================================================
            STUDENT STATISTICS
        ====================================================== --}}

        <div class="row g-4 mb-4">

            {{-- Total Students --}}

            <div class="col-xl-4 col-md-6">

                <div class="card border-0 shadow-sm h-100">

                    <div class="card-body">

                        <div class="d-flex justify-content-between align-items-center">

                            <div>

                                <div class="text-muted small mb-1">
                                    Total Students
                                </div>

                                <h3 class="fw-bold mb-0">
                                    {{ $totalStudents }}
                                </h3>

                            </div>

                            <div class="bg-primary bg-opacity-10 rounded-circle p-3">

                                <i class="bi bi-people fs-4 text-primary"></i>

                            </div>

                        </div>

                    </div>

                </div>

            </div>


            {{-- Male Students --}}

            <div class="col-xl-4 col-md-6">

                <div class="card border-0 shadow-sm h-100">

                    <div class="card-body">

                        <div class="d-flex justify-content-between align-items-center">

                            <div>

                                <div class="text-muted small mb-1">
                                    Male Students
                                </div>

                                <h3 class="fw-bold mb-0">
                                    {{ $maleStudents }}
                                </h3>

                            </div>

                            <div class="bg-info bg-opacity-10 rounded-circle p-3">

                                <i class="bi bi-gender-male fs-4 text-info"></i>

                            </div>

                        </div>

                    </div>

                </div>

            </div>


            {{-- Female Students --}}

            <div class="col-xl-4 col-md-6">

                <div class="card border-0 shadow-sm h-100">

                    <div class="card-body">

                        <div class="d-flex justify-content-between align-items-center">

                            <div>

                                <div class="text-muted small mb-1">
                                    Female Students
                                </div>

                                <h3 class="fw-bold mb-0">
                                    {{ $femaleStudents }}
                                </h3>

                            </div>

                            <div class="bg-danger bg-opacity-10 rounded-circle p-3">

                                <i class="bi bi-gender-female fs-4 text-danger"></i>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- =====================================================
            QUICK ACTIONS
        ====================================================== --}}

        <div class="card border-0 shadow-sm mb-4">

            <div class="card-header bg-white border-0 py-3">

                <h5 class="fw-bold mb-0">
                    Quick Actions
                </h5>

            </div>

            <div class="card-body">

                <div class="row g-3">

                    {{-- Students --}}

                    <div class="col-lg-3 col-md-6">

                        <a
                            href="{{ url('/students') }}"
                            class="text-decoration-none"
                        >

                            <div class="border rounded p-3 h-100">

                                <i class="bi bi-people fs-3 text-primary"></i>

                                <h6 class="fw-bold mt-2 mb-1">
                                The List of  Students
                                </h6>

                                <small class="text-muted">
                                    View students.
                                </small>

                            </div>

                        </a>

                    </div>


                    {{-- Attendance --}}

                    <div class="col-lg-3 col-md-6">

                        <a
                            href="{{ url('/attendance') }}"
                            class="text-decoration-none"
                        >

                            <div class="border rounded p-3 h-100">

                                <i class="bi bi-calendar-check fs-3 text-success"></i>

                                <h6 class="fw-bold mt-2 mb-1">
                                    Attendance
                                </h6>

                                <small class="text-muted">
                                    Record and monitor student attendance.
                                </small>

                            </div>

                        </a>

                    </div>


                    {{-- Performance --}}

                    <div class="col-lg-3 col-md-6">

                        <a
                            href="{{ url('/class-mark-sheet') }}"
                            class="text-decoration-none"
                        >

                            <div class="border rounded p-3 h-100">

                                <i class="bi bi-bar-chart fs-3 text-warning"></i>

                                <h6 class="fw-bold mt-2 mb-1">
                                    Performance
                                </h6>

                                <small class="text-muted">
                                    Monitor academic performance.
                                </small>

                            </div>

                        </a>

                    </div>


                    {{-- Reports --}}

                    <div class="col-lg-3 col-md-6">


   <a href="{{ route('student-report-card.index') }}"
   class="nav-link">



                            <div class="border rounded p-3 h-100">

                                <i class="bi bi-file-earmark-text fs-3 text-danger"></i>

                                <h6 class="fw-bold mt-2 mb-1">
                                    Reports
                                </h6>

                                <small class="text-muted">
                                    View student academic reports.
                                </small>

                            </div>

                        </a>

                    </div>

                </div>

            </div>

        </div>


        {{-- =====================================================
            STUDENT LIST
        ====================================================== --}}

        <div class="card border-0 shadow-sm">

            <div class="card-header bg-white border-0 py-3">

                <div class="d-flex justify-content-between align-items-center">

                    <div>

                        <h5 class="fw-bold mb-1">
                            My Students
                        </h5>

                        <small class="text-muted">
                            Students currently assigned to
                            {{ $assignment->schoolClass->name }}
                            @if($assignment->stream)
                                - {{ $assignment->stream->name }}
                            @endif
                        </small>

                    </div>

                    <span class="badge bg-primary">
                        {{ $totalStudents }} Students
                    </span>

                </div>

            </div>


            <div class="card-body p-0">

                @if($students->count())

                    <div class="table-responsive">

                        <table class="table table-hover align-middle mb-0">

                            <thead class="table-light">

                                <tr>

                                    <th class="ps-4">
                                        #
                                    </th>

                                    <th>
                                        Admission No.
                                    </th>

                                    <th>
                                        Student Name
                                    </th>

                                    <th>
                                        Gender
                                    </th>

                                    <th>
                                        Class
                                    </th>

                                    <th>
                                        Stream
                                    </th>

                                    <th>
                                        Status
                                    </th>

                                </tr>

                            </thead>

                            <tbody>

                                @foreach($students as $student)

                                    <tr>

                                        <td class="ps-4">
                                            {{ $loop->iteration }}
                                        </td>

                                        <td>
                                            {{ $student->admission_no ?? '—' }}
                                        </td>

                                        <td>

                                            <div class="fw-semibold">

                                                {{ $student->first_name }}

                                                @if($student->middle_name)
                                                    {{ $student->middle_name }}
                                                @endif

                                                {{ $student->last_name }}

                                            </div>

                                        </td>

                                        <td>

                                            @if($student->gender === 'Male')

                                                <span class="badge bg-info">
                                                    Male
                                                </span>

                                            @elseif($student->gender === 'Female')

                                                <span class="badge bg-danger">
                                                    Female
                                                </span>

                                            @else

                                                <span class="text-muted">
                                                    —
                                                </span>

                                            @endif

                                        </td>

                                        <td>
                                            {{ $student->schoolClass->name ?? '—' }}
                                        </td>

                                        <td>
                                            {{ $student->stream->name ?? 'Whole Class' }}
                                        </td>

                                        <td>

                                            <span class="badge bg-success">
                                                Active
                                            </span>

                                        </td>

                                    </tr>

                                @endforeach

                            </tbody>

                        </table>

                    </div>

                @else

                    <div class="text-center py-5">

                        <i class="bi bi-people fs-1 text-muted"></i>

                        <h6 class="fw-bold mt-3">
                            No Students Found
                        </h6>

                        <p class="text-muted mb-0">
                            There are currently no active students assigned
                            to this class and stream.
                        </p>

                    </div>

                @endif

            </div>

        </div>

    @endif

</div>

@endsection