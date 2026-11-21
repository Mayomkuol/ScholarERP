@extends('layouts.app')

@section('content')

<div class="container-fluid">

    {{-- Page Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h3 class="fw-bold mb-1">
                Teacher Subject Allocations
            </h3>

            <small class="text-muted">
                Manage teacher subject, class and stream allocations
            </small>
        </div>

        <a href="{{ route('teacher-allocations.create') }}"
           class="btn btn-primary">

            <i class="bi bi-plus-circle me-1"></i>

            New Allocation

        </a>

    </div>

    {{-- Success Message --}}
    @if(session('success'))

        <div class="alert alert-success alert-dismissible fade show">

            <i class="bi bi-check-circle"></i>

            {{ session('success') }}

            <button class="btn-close"
                    data-bs-dismiss="alert"></button>

        </div>

    @endif

    {{-- Summary Cards --}}
    <div class="row mb-4">

        <div class="col-lg-3 col-md-6">

            <div class="card border-0 shadow-sm">

                <div class="card-body">

                    <div class="d-flex justify-content-between">

                        <div>

                            <small class="text-muted">

                                Total Allocations

                            </small>

                            <h3 class="fw-bold">

                                {{ $allocations->total() }}

                            </h3>

                        </div>

                        <i class="bi bi-diagram-3-fill fs-1 text-primary"></i>

                    </div>

                </div>

            </div>

        </div>

        <div class="col-lg-3 col-md-6">

            <div class="card border-0 shadow-sm">

                <div class="card-body">

                    <div class="d-flex justify-content-between">

                        <div>

                            <small class="text-muted">

                                Teachers

                            </small>

                            <h3 class="fw-bold">

                                {{ \App\Models\Teacher::count() }}

                            </h3>

                        </div>

                        <i class="bi bi-person-workspace fs-1 text-success"></i>

                    </div>

                </div>

            </div>

        </div>

        <div class="col-lg-3 col-md-6">

            <div class="card border-0 shadow-sm">

                <div class="card-body">

                    <div class="d-flex justify-content-between">

                        <div>

                            <small class="text-muted">

                                Subjects

                            </small>

                            <h3 class="fw-bold">

                                {{ \App\Models\Subject::count() }}

                            </h3>

                        </div>

                        <i class="bi bi-book fs-1 text-warning"></i>

                    </div>

                </div>

            </div>

        </div>

        <div class="col-lg-3 col-md-6">

            <div class="card border-0 shadow-sm">

                <div class="card-body">

                    <div class="d-flex justify-content-between">

                        <div>

                            <small class="text-muted">

                                Classes

                            </small>

                            <h3 class="fw-bold">

                                {{ \App\Models\SchoolClass::count() }}

                            </h3>

                        </div>

                        <i class="bi bi-building fs-1 text-danger"></i>

                    </div>

                </div>

            </div>

        </div>

    </div>

    {{-- Allocation Table --}}
    <div class="card shadow">

        <div class="card-header bg-primary text-white">

            <h5 class="mb-0">

                Allocation List

            </h5>

        </div>

        <div class="card-body">

            <div class="table-responsive">

                <table class="table table-hover table-bordered align-middle">

                    <thead class="table-light">

                    <tr>

                        <th>#</th>

                        <th>Teacher</th>

                        <th>Subject</th>

                        <th>Class</th>

                        <th>Stream</th>

                        <th>Academic Year</th>

                        <th>Term</th>

                        <th width="150">

                            Actions

                        </th>

                    </tr>

                    </thead>

                    <tbody>

                    @forelse($allocations as $allocation)

                        <tr>

                            <td>

                                {{ $loop->iteration + ($allocations->firstItem() - 1) }}

                            </td>

                            <td>

                                <strong>

                                    {{ $allocation->teacher->first_name }}

                                    {{ $allocation->teacher->last_name }}

                                </strong>

                            </td>

                            <td>

                                {{ $allocation->subject->name }}

                            </td>

                            <td>
    {{ $allocation->schoolClass->name }}
</td>

                            <td>

                                {{ $allocation->stream->name ?? '-' }}

                            </td>

                            <td>

                                {{ $allocation->academicYear->name }}

                            </td>

                            <td>

                                {{ $allocation->term->name }}

                            </td>

                            <td>

                                <div class="btn-group">

                                    <a href="{{ route('teacher-allocations.show',$allocation->id) }}"
                                       class="btn btn-info btn-sm">

                                        <i class="bi bi-eye"></i>

                                    </a>

                                    <a href="{{ route('teacher-allocations.edit',$allocation->id) }}"
                                       class="btn btn-warning btn-sm">

                                        <i class="bi bi-pencil"></i>

                                    </a>

                                    <form action="{{ route('teacher-allocations.destroy',$allocation->id) }}"
                                          method="POST"
                                          onsubmit="return confirm('Delete this allocation?')">

                                        @csrf
                                        @method('DELETE')

                                        <button class="btn btn-danger btn-sm">

                                            <i class="bi bi-trash"></i>

                                        </button>

                                    </form>

                                </div>

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td colspan="8"
                                class="text-center py-5">

                                <i class="bi bi-inbox fs-1 text-muted d-block mb-2"></i>

                                <h5>No Teacher Allocations Found</h5>

                                <p class="text-muted">

                                    Click <strong>New Allocation</strong> to assign a teacher.

                                </p>

                            </td>

                        </tr>

                    @endforelse

                    </tbody>

                </table>

            </div>

            <div class="mt-3">

                {{ $allocations->links() }}

            </div>

        </div>

    </div>

</div>

@endsection