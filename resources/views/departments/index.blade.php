@extends('layouts.app')

@section('content')

<div class="container-fluid">

    <!-- Header -->

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h3 class="fw-bold mb-0">
                Departments
            </h3>
            <small class="text-muted">
                Manage school departments
            </small>
        </div>

        <a href="{{ route('departments.create') }}"
           class="btn btn-primary">

            <i class="bi bi-plus-circle"></i>

            New Department

        </a>

    </div>

    <!-- Statistics -->

    <div class="row mb-4">

        <div class="col-md-4">

            <div class="card shadow-sm border-0">

                <div class="card-body">

                    <h6 class="text-muted">

                        Total Departments

                    </h6>

                    <h2 class="fw-bold">

                        {{ $stats['total'] }}

                    </h2>

                </div>

            </div>

        </div>

        <div class="col-md-4">

            <div class="card shadow-sm border-0">

                <div class="card-body">

                    <h6 class="text-success">

                        Active

                    </h6>

                    <h2 class="fw-bold text-success">

                        {{ $stats['active'] }}

                    </h2>

                </div>

            </div>

        </div>

        <div class="col-md-4">

            <div class="card shadow-sm border-0">

                <div class="card-body">

                    <h6 class="text-danger">

                        Inactive

                    </h6>

                    <h2 class="fw-bold text-danger">

                        {{ $stats['inactive'] }}

                    </h2>

                </div>

            </div>

        </div>

    </div>

    <!-- Search -->

    <div class="card shadow mb-4">

        <div class="card-body">

            <form method="GET">

                <div class="row">

                    <div class="col-md-10">

                        <input
                            type="text"
                            name="search"
                            class="form-control"
                            placeholder="Search department..."
                            value="{{ request('search') }}">

                    </div>

                    <div class="col-md-2 d-grid">

                        <button class="btn btn-primary">

                            <i class="bi bi-search"></i>

                            Search

                        </button>

                    </div>

                </div>

            </form>

        </div>

    </div>

    <!-- Success -->

    @if(session('success'))

        <div class="alert alert-success">

            {{ session('success') }}

        </div>

    @endif

    <!-- Table -->

    <div class="card shadow">

        <div class="card-body">

            <div class="table-responsive">

                <table class="table table-hover align-middle">

                    <thead class="table-light">

                    <tr>

                        <th>#</th>

                        <th>Department</th>

                        <th>Code</th>

                        <th>Description</th>

                        <th>Status</th>

                        <th width="180">Actions</th>

                    </tr>

                    </thead>

                    <tbody>

                    @forelse($departments as $department)

                        <tr>

                            <td>

                                {{ $loop->iteration + ($departments->firstItem() - 1) }}

                            </td>

                            <td>

                                <strong>

                                    {{ $department->name }}

                                </strong>

                            </td>

                            <td>

                                {{ $department->code }}

                            </td>

                            <td>

                                {{ $department->description ?? '-' }}

                            </td>

                            <td>

                                @if($department->status)

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

                                <a href="{{ route('departments.show',$department) }}"
                                   class="btn btn-info btn-sm">

                                    <i class="bi bi-eye"></i>

                                </a>

                                <a href="{{ route('departments.edit',$department) }}"
                                   class="btn btn-warning btn-sm">

                                    <i class="bi bi-pencil"></i>

                                </a>

                                <form
                                    action="{{ route('departments.destroy',$department) }}"
                                    method="POST"
                                    class="d-inline">

                                    @csrf
                                    @method('DELETE')

                                    <button
                                        class="btn btn-danger btn-sm"
                                        onclick="return confirm('Delete this department?')">

                                        <i class="bi bi-trash"></i>

                                    </button>

                                </form>

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td colspan="6"
                                class="text-center text-muted py-5">

                                No departments found.

                            </td>

                        </tr>

                    @endforelse

                    </tbody>

                </table>

            </div>

            <div class="mt-3">

                {{ $departments->links() }}

            </div>

        </div>

    </div>

</div>

@endsection