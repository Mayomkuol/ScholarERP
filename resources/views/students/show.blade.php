@extends('layouts.app')

@section('title','Student Profile')

@section('content')

<div class="container-fluid">

    {{-- Header --}}
    <div class="card shadow-sm border-0 mb-4">

        <div class="card-body">

            <div class="row align-items-center">

                <div class="col-lg-2 text-center">

                    @if($student->photo)

                        <img src="{{ asset('storage/'.$student->photo) }}"
                             class="rounded-circle img-thumbnail shadow"
                             style="width:170px;height:170px;object-fit:cover;">

                    @else

                        <img src="https://ui-avatars.com/api/?name={{ urlencode($student->full_name) }}&size=170"
                             class="rounded-circle img-thumbnail shadow">

                    @endif

                </div>

                <div class="col-lg-7">

                    <h2 class="fw-bold">

                        {{ $student->full_name }}

                    </h2>

                    <p class="mb-1">

                        <strong>Admission No:</strong>

                        {{ $student->admission_no }}

                    </p>

                    <p class="mb-1">

                        <strong>Class:</strong>

                        {{ optional($student->schoolClass)->name }}

                        {{ optional($student->stream)->name }}

                    </p>

                    <p class="mb-0">

                        <strong>Academic Year:</strong>

                        {{ optional($student->academicYear)->name }}

                    </p>

                </div>

                <div class="col-lg-3 text-end">

                    <span class="badge bg-success fs-6">

                        {{ $student->status }}

                    </span>

                    <br><br>

                    <a href="{{ route('students.edit',$student) }}"
                       class="btn btn-warning btn-sm">

                        <i class="bi bi-pencil"></i>

                        Edit

                    </a>

                   <a href="{{ route('students.documents.index', $student) }}"
   class="btn btn-primary btn-sm">

    <i class="bi bi-folder"></i>

    Documents

</a>

                    <button onclick="window.print()"
                            class="btn btn-success btn-sm">

                        <i class="bi bi-printer"></i>

                        Print

                    </button>

                </div>

            </div>

        </div>

    </div>



    {{-- Statistics Cards --}}

    <div class="row mb-4">

        <div class="col-md-3">

            <div class="card border-0 shadow-sm">

                <div class="card-body">

                    <h6 class="text-muted">

                        Attendance

                    </h6>

                    <h2>

                        {{ $student->attendance_percentage }}%

                    </h2>

                </div>

            </div>

        </div>

        <div class="col-md-3">

            <div class="card border-0 shadow-sm">

                <div class="card-body">

                    <h6 class="text-muted">

                        Documents

                    </h6>

                    <h2>

                        {{ $student->documents->count() }}

                    </h2>

                </div>

            </div>

        </div>

        <div class="col-md-3">

            <div class="card border-0 shadow-sm">

                <div class="card-body">

                    <h6 class="text-muted">

                        Discipline Cases

                    </h6>

                    <h2>

                        {{ $student->disciplines->count() }}

                    </h2>

                </div>

            </div>

        </div>

        <div class="col-md-3">

            <div class="card border-0 shadow-sm">

                <div class="card-body">

                    <h6 class="text-muted">

                        Promotions

                    </h6>

                    <h2>

                        {{ $student->promotions->count() }}

                    </h2>

                </div>

            </div>

        </div>

    </div>



    {{-- Tabs --}}

    <div class="card shadow-sm">

        <div class="card-header bg-white">

            <ul class="nav nav-pills card-header-pills" role="tablist">

                <li class="nav-item">
                    <button class="nav-link active"
                            data-bs-toggle="tab"
                            data-bs-target="#overview">
                        Overview
                    </button>
                </li>

                <li class="nav-item">
                    <button class="nav-link"
                            data-bs-toggle="tab"
                            data-bs-target="#academic">
                        Academic
                    </button>
                </li>

                <li class="nav-item">
                    <button class="nav-link"
                            data-bs-toggle="tab"
                            data-bs-target="#parents">
                        Parents
                    </button>
                </li>

                <li class="nav-item">
                    <button class="nav-link"
                            data-bs-toggle="tab"
                            data-bs-target="#guardians">
                        Guardian
                    </button>
                </li>

                <li class="nav-item">
                    <button class="nav-link"
                            data-bs-toggle="tab"
                            data-bs-target="#medical">
                        Medical
                    </button>
                </li>

                <li class="nav-item">
                    <button class="nav-link"
                            data-bs-toggle="tab"
                            data-bs-target="#documents">
                        Documents
                    </button>
                </li>

                <li class="nav-item">
                    <button class="nav-link"
                            data-bs-toggle="tab"
                            data-bs-target="#attendance">
                        Attendance
                    </button>
                </li>

                <li class="nav-item">
                    <button class="nav-link"
                            data-bs-toggle="tab"
                            data-bs-target="#discipline">
                        Discipline
                    </button>
                </li>

                <li class="nav-item">
                    <button class="nav-link"
                            data-bs-toggle="tab"
                            data-bs-target="#promotions">
                        Promotions
                    </button>
                </li>

                <li class="nav-item">
                    <button class="nav-link"
                            data-bs-toggle="tab"
                            data-bs-target="#transfers">
                        Transfers
                    </button>
                </li>

            </ul>

        </div>



        <div class="card-body">

            <div class="tab-content">

                <div class="tab-pane fade show active" id="overview">
                    @include('students.profile.overview')
                </div>

                <div class="tab-pane fade" id="academic">
                    @include('students.profile.academic')
                </div>

                <div class="tab-pane fade" id="parents">
                    @include('students.profile.parents')
                </div>

                <div class="tab-pane fade" id="guardians">
                    @include('students.profile.guardians')
                </div>

                <div class="tab-pane fade" id="medical">
                    @include('students.profile.medical')
                </div>

                <div class="tab-pane fade" id="documents">
                    @include('students.profile.documents')
                </div>

                <div class="tab-pane fade" id="attendance">
                    @include('students.profile.attendance')
                </div>

                <div class="tab-pane fade" id="discipline">
                    @include('students.profile.discipline')
                </div>

                <div class="tab-pane fade" id="promotions">
                    @include('students.profile.promotions')
                </div>

                <div class="tab-pane fade" id="transfers">
                    @include('students.profile.transfers')
                </div>

            </div>

        </div>

    </div>

</div>

@endsection