@extends('layouts.app')

@section('title', 'Discipline Record')

@section('content')

<div class="container-fluid py-4">

{{-- Page Header --}}
<div class="d-flex justify-content-between align-items-center mb-4">

    <div>
        <h4 class="mb-1">
            <i class="bi bi-shield-exclamation me-2"></i>
            Discipline Record
        </h4>

        <p class="text-muted mb-0">
            {{ $student->first_name }}
            {{ $student->middle_name }}
            {{ $student->last_name }}
            —
            {{ $student->admission_no }}
        </p>
    </div>

    <div class="d-flex gap-2">

        <a
            href="{{ route('students.discipline.index', $student) }}"
            class="btn btn-outline-secondary"
        >
            <i class="bi bi-arrow-left me-1"></i>
            Back to Records
        </a>

        @can(
            'update',
            [
                App\Models\StudentDiscipline::class,
                $student,
                $discipline
            ]
        )
            <a
                href="{{ route(
                    'students.discipline.edit',
                    [
                        'student' => $student,
                        'discipline' => $discipline
                    ]
                ) }}"
                class="btn btn-warning"
            >
                <i class="bi bi-pencil me-1"></i>
                Edit
            </a>
        @endcan

    </div>
</div>

{{-- Record --}}
<div class="card border-0 shadow-sm">

    <div class="card-header bg-white py-3">
        <div class="d-flex justify-content-between align-items-center">

            <h5 class="mb-0">
                Discipline Information
            </h5>

            @if ($discipline->status === 'Resolved')

                <span class="badge bg-success">
                    <i class="bi bi-check-circle me-1"></i>
                    Resolved
                </span>

            @else

                <span class="badge bg-warning text-dark">
                    <i class="bi bi-clock me-1"></i>
                    Pending
                </span>

            @endif

        </div>
    </div>

    <div class="card-body">

        <div class="row g-4">

            {{-- Incident Date --}}
            <div class="col-md-6">

                <label class="form-label text-muted">
                    Incident Date
                </label>

                <div class="fw-semibold">
                    {{ $discipline->incident_date?->format('d M Y') ?? '—' }}
                </div>

            </div>

            {{-- Category --}}
            <div class="col-md-6">

                <label class="form-label text-muted">
                    Category
                </label>

                <div>
                    <span class="badge bg-light text-dark border">
                        {{ $discipline->category }}
                    </span>
                </div>

            </div>

            {{-- Reported By --}}
            <div class="col-md-6">

                <label class="form-label text-muted">
                    Reported By
                </label>

                <div class="fw-semibold">
                    {{ $discipline->reported_by ?? '—' }}
                </div>

            </div>

            {{-- Status --}}
            <div class="col-md-6">

                <label class="form-label text-muted">
                    Status
                </label>

                <div>

                    @if ($discipline->status === 'Resolved')

                        <span class="badge bg-success">
                            Resolved
                        </span>

                    @else

                        <span class="badge bg-warning text-dark">
                            Pending
                        </span>

                    @endif

                </div>

            </div>

            {{-- Incident --}}
            <div class="col-12">

                <label class="form-label text-muted">
                    Incident Description
                </label>

                <div class="border rounded p-3 bg-light">
                    {!! nl2br(e($discipline->incident)) !!}
                </div>

            </div>

            {{-- Action Taken --}}
            <div class="col-12">

                <label class="form-label text-muted">
                    Action Taken
                </label>

                <div class="border rounded p-3 bg-light">

                    @if ($discipline->action_taken)

                        {!! nl2br(e($discipline->action_taken)) !!}

                    @else

                        <span class="text-muted">
                            No action recorded.
                        </span>

                    @endif

                </div>

            </div>

        </div>

    </div>

    <div class="card-footer bg-white">

        <div class="d-flex justify-content-between align-items-center">

            <small class="text-muted">
                Record created:
                {{ $discipline->created_at?->format('d M Y H:i') ?? '—' }}
            </small>

            @can(
                'delete',
                [
                    App\Models\StudentDiscipline::class,
                    $student,
                    $discipline
                ]
            )
                <form
                    method="POST"
                    action="{{ route(
                        'students.discipline.destroy',
                        [
                            'student' => $student,
                            'discipline' => $discipline
                        ]
                    ) }}"
                    onsubmit="return confirm('Are you sure you want to delete this discipline record? This action cannot be undone.');"
                >

                    @csrf
                    @method('DELETE')

                    <button
                        type="submit"
                        class="btn btn-outline-danger btn-sm"
                    >
                        <i class="bi bi-trash me-1"></i>
                        Delete Record
                    </button>

                </form>
            @endcan

        </div>

    </div>

</div>

</div>

@endsection
