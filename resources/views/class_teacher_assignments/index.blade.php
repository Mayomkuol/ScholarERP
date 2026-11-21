@extends('layouts.app')

@section('content')

<div class="container-fluid">

    {{-- =========================================================
        PAGE HEADER
    ========================================================== --}}

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h4 class="fw-bold mb-1">
                Class Teacher Assignments
            </h4>

            <p class="text-muted mb-0">
                Manage teachers assigned to classes and streams.
            </p>
        </div>

        <div>
            <a href="{{ route('class-teacher-assignments.create') }}"
               class="btn btn-primary">

                <i class="bi bi-person-plus"></i>

                Assign Class Teacher

            </a>
        </div>

    </div>


    {{-- =========================================================
        SUCCESS MESSAGE
    ========================================================== --}}

    @if(session('success'))

        <div class="alert alert-success alert-dismissible fade show">

            <i class="bi bi-check-circle"></i>

            {{ session('success') }}

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert">
            </button>

        </div>

    @endif


    {{-- =========================================================
        ERROR MESSAGE
    ========================================================== --}}

    @if(session('error'))

        <div class="alert alert-danger alert-dismissible fade show">

            <i class="bi bi-exclamation-triangle"></i>

            {{ session('error') }}

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert">
            </button>

        </div>

    @endif


    {{-- =========================================================
        ASSIGNMENTS TABLE
    ========================================================== --}}

    <div class="card shadow-sm">

        <div class="card-header bg-white">

            <div class="d-flex justify-content-between align-items-center">

                <strong>
                    Class Teacher Assignments
                </strong>

                <span class="badge bg-primary">
                    {{ $assignments->total() }} Total
                </span>

            </div>

        </div>


        <div class="card-body p-0">

            <div class="table-responsive">

                <table class="table table-hover align-middle mb-0">

                    <thead class="table-light">

                        <tr>

                            <th>#</th>

                            <th>Teacher</th>

                            <th>Employee No.</th>

                            <th>Class</th>

                            <th>Stream</th>

                            <th>Academic Year</th>

                            <th>Term</th>

                            <th>Status</th>

                            <th>Assigned Date</th>

                            <th class="text-end">
                                Action
                            </th>

                        </tr>

                    </thead>


                    <tbody>

                        @forelse($assignments as $assignment)

                            <tr>

                                {{-- Number --}}

                                <td>

                                    {{ $assignments->firstItem() + $loop->index }}

                                </td>


                                {{-- Teacher --}}

                                <td>

                                    <div class="fw-semibold">

                                        {{ $assignment->teacher->first_name }}

                                        @if($assignment->teacher->middle_name)
                                            {{ $assignment->teacher->middle_name }}
                                        @endif

                                        {{ $assignment->teacher->last_name }}

                                    </div>

                                </td>


                                {{-- Employee Number --}}

                                <td>

                                    <span class="text-muted">

                                        {{ $assignment->teacher->employee_no }}

                                    </span>

                                </td>


                                {{-- Class --}}

                                <td>

                                    <span class="fw-semibold">

                                        {{ $assignment->schoolClass->name }}

                                    </span>

                                </td>


                                {{-- Stream --}}

                                <td>

                                    @if($assignment->stream)

                                        <span class="badge bg-info text-dark">

                                            {{ $assignment->stream->name }}

                                        </span>

                                    @else

                                        <span class="text-muted">

                                            Whole Class

                                        </span>

                                    @endif

                                </td>


                                {{-- Academic Year --}}

                                <td>

                                    {{ $assignment->academicYear->name }}

                                </td>


                                {{-- Term --}}

                                <td>

                                    {{ $assignment->term->name }}

                                </td>


                                {{-- Status --}}

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


                                {{-- Assigned Date --}}

                                <td>

                                    {{ $assignment->assigned_at
                                        ? $assignment->assigned_at->format('d M Y')
                                        : '—'
                                    }}

                                </td>


                                {{-- Actions --}}

                                <td class="text-end">

                                    <div class="btn-group">

                                        <a href="{{ route(
                                            'class-teacher-assignments.edit',
                                            $assignment
                                        ) }}"
                                           class="btn btn-sm btn-outline-primary"
                                           title="Edit">

                                            <i class="bi bi-pencil"></i>

                                        </a>


                                        <form action="{{ route(
                                            'class-teacher-assignments.destroy',
                                            $assignment
                                        ) }}"
                                              method="POST"
                                              class="d-inline"
                                              onsubmit="return confirm(
                                                  'Are you sure you want to remove this class teacher assignment?'
                                              );">

                                            @csrf

                                            @method('DELETE')

                                            <button type="submit"
                                                    class="btn btn-sm btn-outline-danger"
                                                    title="Delete">

                                                <i class="bi bi-trash"></i>

                                            </button>

                                        </form>

                                    </div>

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td colspan="10"
                                    class="text-center py-5">

                                    <div class="text-muted">

                                        <i class="bi bi-person-workspace fs-1 d-block mb-3"></i>

                                        <h6 class="fw-semibold">

                                            No Class Teacher Assignments

                                        </h6>

                                        <p class="mb-3">

                                            No class teacher has been assigned yet.

                                        </p>

                                        <a href="{{ route(
                                            'class-teacher-assignments.create'
                                        ) }}"
                                           class="btn btn-primary">

                                            <i class="bi bi-person-plus"></i>

                                            Assign Class Teacher

                                        </a>

                                    </div>

                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>


        {{-- =====================================================
            PAGINATION
        ====================================================== --}}

        @if($assignments->hasPages())

            <div class="card-footer bg-white">

                {{ $assignments->links() }}

            </div>

        @endif

    </div>

</div>

@endsection