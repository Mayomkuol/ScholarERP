```blade
@extends('layouts.app')

@section('title', 'Exam Details')

@section('content')
<div class="container-fluid">

    {{-- Page Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="mb-1">Examination Details</h3>
            <p class="text-muted mb-0">View examination information and assessment progress.</p>
        </div>

        <div class="d-flex gap-2">
            <a href="{{ route('exams.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Back
            </a>

            <a href="{{ route('exams.edit', $exam) }}" class="btn btn-warning">
                <i class="bi bi-pencil-square"></i> Edit Exam
            </a>
        </div>
    </div>

    {{-- Exam Summary --}}
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <h4 class="mb-2">{{ $exam->name }}</h4>
                    <p class="text-muted mb-0">
                        {{ $exam->type->name ?? 'Exam Type Not Set' }}
                    </p>
                </div>

                @php
                    $statusClass = match($exam->status) {
                        'Open' => 'bg-success',
                        'Closed' => 'bg-secondary',
                        'Published' => 'bg-primary',
                        default => 'bg-warning text-dark',
                    };
                @endphp

                <span class="badge {{ $statusClass }} fs-6">
                    {{ $exam->status }}
                </span>
            </div>
        </div>
    </div>

    {{-- Examination Information --}}
    <div class="card shadow-sm mb-4">
        <div class="card-header">
            <h5 class="mb-0">Examination Information</h5>
        </div>

        <div class="card-body">
            <div class="row g-4">

                <div class="col-md-4">
                    <small class="text-muted">Exam Name</small>
                    <h6>{{ $exam->name }}</h6>
                </div>

                <div class="col-md-4">
                    <small class="text-muted">Exam Type</small>
                    <h6>{{ $exam->type->name ?? '-' }}</h6>
                </div>

                <div class="col-md-4">
                    <small class="text-muted">Academic Year</small>
                    <h6>{{ $exam->academicYear->name ?? '-' }}</h6>
                </div>

                <div class="col-md-4">
                    <small class="text-muted">Term</small>
                    <h6>{{ $exam->term->name ?? '-' }}</h6>
                </div>

                <div class="col-md-4">
                    <small class="text-muted">Class</small>
                    <h6>{{ $exam->schoolClass->name ?? '-' }}</h6>
                </div>

                <div class="col-md-4">
                    <small class="text-muted">Status</small>
                    <h6>{{ $exam->status }}</h6>
                </div>

                <div class="col-md-4">
                    <small class="text-muted">Start Date</small>
                    <h6>{{ $exam->start_date?->format('d M Y') ?? '-' }}</h6>
                </div>

                <div class="col-md-4">
                    <small class="text-muted">End Date</small>
                    <h6>{{ $exam->end_date?->format('d M Y') ?? '-' }}</h6>
                </div>

                <div class="col-md-4">
                    <small class="text-muted">Created On</small>
                    <h6>{{ $exam->created_at?->format('d M Y') ?? '-' }}</h6>
                </div>

            </div>
        </div>
    </div>

    {{-- Assessment Summary --}}
    <div class="card shadow-sm">
        <div class="card-header">
            <h5 class="mb-0">Assessment Summary</h5>
        </div>

        <div class="card-body">
            <div class="row g-3">

                <div class="col-md-4">
                    <div class="border rounded p-3">
                        <small class="text-muted">Total Marks Recorded</small>
                        <h3 class="mb-0">{{ $exam->marks->count() }}</h3>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="border rounded p-3">
                        <small class="text-muted">Students Assessed</small>
                        <h3 class="mb-0">
                            {{ $exam->marks->pluck('student_id')->unique()->count() }}
                        </h3>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="border rounded p-3">
                        <small class="text-muted">Subjects Assessed</small>
                        <h3 class="mb-0">
                            {{ $exam->marks->pluck('subject_id')->unique()->count() }}
                        </h3>
                    </div>
                </div>

            </div>

            @if($exam->marks->isEmpty())
                <div class="alert alert-info mt-3 mb-0">
                    No examination marks have been recorded yet.
                </div>
            @endif
        </div>
    </div>

</div>
@endsection
```