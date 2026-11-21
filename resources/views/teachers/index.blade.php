@extends('layouts.app')

@section('content')

<div class="container-fluid">

    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h3 class="fw-bold mb-0">Teachers</h3>
            <small class="text-muted">
                Manage all teaching staff
            </small>
        </div>

        <a href="{{ route('teachers.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i>
            Add Teacher
        </a>

    </div>

    <!-- Summary Cards -->
    <div class="row mb-4">

        <div class="col-md-3">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <h6 class="text-muted">Total Teachers</h6>
                    <h3>{{ $teachers->count() }}</h3>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <h6 class="text-muted">Active</h6>
                    <h3>
                        {{ $teachers->where('status',1)->count() }}
                    </h3>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <h6 class="text-muted">Permanent</h6>
                    <h3>
                        {{ $teachers->where('employment_type','Permanent')->count() }}
                    </h3>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <h6 class="text-muted">Departments</h6>
                    <h3>
                        {{ $teachers->pluck('department_id')->unique()->count() }}
                    </h3>
                </div>
            </div>
        </div>

    </div>

    <!-- Teacher Table -->

    <div class="card shadow">

        <div class="card-header bg-primary text-white">

            <strong>Teacher List</strong>

        </div>

        <div class="card-body">

            <div class="table-responsive">

                <table class="table table-hover table-bordered align-middle">

                    <thead class="table-light">

                        <tr>

                            <th>#</th>

                            <th>Photo</th>

                            <th>Employee No</th>

                            <th>Name</th>

                            <th>Department</th>

                            <th>Designation</th>

                            <th>Employment</th>

                            <th>Phone</th>

                            <th>Status</th>

                            <th width="180">Actions</th>

                        </tr>

                    </thead>

                    <tbody>

                    @forelse($teachers as $teacher)

                        <tr>

                            <td>
                                {{ $loop->iteration + ($teachers->firstItem() - 1) }}
                            </td>

                            <td>

                                @if($teacher->photo)

                                    <img src="{{ asset('storage/'.$teacher->photo) }}"
                                         width="50"
                                         height="50"
                                         class="rounded-circle">

                                @else

                                    <img src="https://via.placeholder.com/50"
                                         class="rounded-circle">

                                @endif

                            </td>

                            <td>
                                {{ $teacher->employee_no }}
                            </td>

                            <td>

                                <strong>

                                    {{ $teacher->first_name }}
                                    {{ $teacher->middle_name }}
                                    {{ $teacher->last_name }}

                                </strong>

                            </td>

                            <td>

                                {{ $teacher->department->name ?? '-' }}

                            </td>

                            <td>

                                {{ $teacher->designation }}

                            </td>

                            <td>

                                {{ $teacher->employment_type }}

                            </td>

                            <td>

                                {{ $teacher->phone }}

                            </td>

                            <td>

                                @if($teacher->status)

                                    <span class="badge bg-success">

                                        Active

                                    </span>

                                @else

                                    <span class="badge bg-danger">

                                        Inactive

                                    </span>

                                @endif

                            </td>

                            <td>

                                <a href="{{ route('teachers.show',$teacher) }}"
                                   class="btn btn-info btn-sm">

                                    <i class="bi bi-eye"></i>

                                </a>

                                <a href="{{ route('teachers.edit',$teacher) }}"
                                   class="btn btn-warning btn-sm">

                                    <i class="bi bi-pencil"></i>

                                </a>

                                <form action="{{ route('teachers.destroy',$teacher) }}"
                                      method="POST"
                                      class="d-inline">

                                    @csrf
                                    @method('DELETE')

                                    <button
                                        onclick="return confirm('Delete this teacher?')"
                                        class="btn btn-danger btn-sm">

                                        <i class="bi bi-trash"></i>

                                    </button>

                                </form>

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td colspan="10"
                                class="text-center text-muted">

                                No teachers found.

                            </td>

                        </tr>

                    @endforelse

                    </tbody>

                </table>

            </div>

            <div class="mt-3">

                {{ $teachers->links() }}

            </div>

        </div>

    </div>

</div>

@endsection