@extends('layouts.app')

@section('title', 'Student Discipline')

@section('content')

<div class="container-fluid py-4">

    {{-- Page Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h4 class="mb-1">
                <i class="bi bi-shield-exclamation me-2"></i>
                Student Discipline
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
                href="{{ route('students.show', $student) }}"
                class="btn btn-outline-secondary"
            >
                <i class="bi bi-arrow-left me-1"></i>
                Back to Student
            </a>

            @can('create', [App\Models\StudentDiscipline::class, $student])
                <a
                    href="{{ route('students.discipline.create', $student) }}"
                    class="btn btn-primary"
                >
                    <i class="bi bi-plus-lg me-1"></i>
                    Add Discipline
                </a>
            @endcan

        </div>
    </div>


    {{-- Success Message --}}
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            <i class="bi bi-check-circle me-2"></i>
            {{ session('success') }}

            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert"
            ></button>
        </div>
    @endif


    {{-- Error Message --}}
    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show">
            <i class="bi bi-exclamation-triangle me-2"></i>
            {{ session('error') }}

            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert"
            ></button>
        </div>
    @endif


    {{-- Discipline Records --}}
    <div class="card border-0 shadow-sm">

        <div class="card-header bg-white py-3">

            <div class="d-flex justify-content-between align-items-center">

                <h5 class="mb-0">
                    Discipline Records
                </h5>

                <span class="badge bg-secondary">
                    {{ $disciplines->total() }}
                    {{ Str::plural('Record', $disciplines->total()) }}
                </span>

            </div>

        </div>


        <div class="card-body p-0">

            @if ($disciplines->count())

                <div class="table-responsive">

                    <table class="table table-hover align-middle mb-0">

                        <thead class="table-light">

                            <tr>
                                <th class="px-4">
                                    Date
                                </th>

                                <th>
                                    Category
                                </th>

                                <th>
                                    Incident
                                </th>

                                <th>
                                    Action Taken
                                </th>

                                <th>
                                    Reported By
                                </th>

                                <th>
                                    Status
                                </th>

                                <th class="text-end px-4">
                                    Actions
                                </th>
                            </tr>

                        </thead>

                        <tbody>

                            @foreach ($disciplines as $discipline)

                                <tr>

                                    {{-- Date --}}
                                    <td class="px-4">
                                        <span class="fw-semibold">
                                            {{ $discipline->incident_date?->format('d M Y') }}
                                        </span>
                                    </td>


                                    {{-- Category --}}
                                    <td>
                                        <span class="badge bg-light text-dark border">
                                            {{ $discipline->category }}
                                        </span>
                                    </td>


                                    {{-- Incident --}}
                                    <td style="min-width: 250px; max-width: 400px;">
                                        <div>
                                            {{ Str::limit($discipline->incident, 120) }}
                                        </div>
                                    </td>


                                    {{-- Action Taken --}}
                                    <td style="min-width: 200px; max-width: 350px;">
                                        @if ($discipline->action_taken)
                                            {{ Str::limit($discipline->action_taken, 100) }}
                                        @else
                                            <span class="text-muted">
                                                —
                                            </span>
                                        @endif
                                    </td>


                                    {{-- Reported By --}}
                                    <td>
                                        {{ $discipline->reported_by ?? '—' }}
                                    </td>


                                    {{-- Status --}}
                                    <td>

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

                                    </td>


                                    {{-- Actions --}}
                                    <td class="text-end px-4">

                                        <div class="d-flex justify-content-end gap-1">

                                            {{-- View --}}
                                            <a
                                                href="{{ route(
                                                    'students.discipline.show',
                                                    [
                                                        'student' => $student,
                                                        'discipline' => $discipline
                                                    ]
                                                ) }}"
                                                class="btn btn-sm btn-outline-primary"
                                                title="View"
                                            >
                                                <i class="bi bi-eye"></i>
                                            </a>


                                            {{-- Edit --}}
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
                                                    class="btn btn-sm btn-outline-warning"
                                                    title="Edit"
                                                >
                                                    <i class="bi bi-pencil"></i>
                                                </a>
                                            @endcan


                                            {{-- Delete --}}
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
                                                    class="d-inline"
                                                    onsubmit="return confirm('Are you sure you want to delete this discipline record? This action cannot be undone.');"
                                                >

                                                    @csrf
                                                    @method('DELETE')

                                                    <button
                                                        type="submit"
                                                        class="btn btn-sm btn-outline-danger"
                                                        title="Delete"
                                                    >
                                                        <i class="bi bi-trash"></i>
                                                    </button>

                                                </form>
                                            @endcan

                                        </div>

                                    </td>

                                </tr>

                            @endforeach

                        </tbody>

                    </table>

                </div>


                {{-- Pagination --}}
                @if ($disciplines->hasPages())

                    <div class="p-3 border-top">

                        {{ $disciplines->links() }}

                    </div>

                @endif

            @else

                {{-- Empty State --}}
                <div class="text-center py-5">

                    <div class="mb-3">
                        <i
                            class="bi bi-shield-check"
                            style="font-size: 3rem;"
                        ></i>
                    </div>

                    <h5 class="mb-2">
                        No Discipline Records
                    </h5>

                    <p class="text-muted mb-4">
                        No discipline records have been recorded
                        for this student.
                    </p>

                    @can('create', [App\Models\StudentDiscipline::class, $student])

                        <a
                            href="{{ route(
                                'students.discipline.create',
                                $student
                            ) }}"
                            class="btn btn-primary"
                        >
                            <i class="bi bi-plus-lg me-1"></i>
                            Add First Record
                        </a>

                    @endcan

                </div>

            @endif

        </div>

    </div>

</div>

@endsection

