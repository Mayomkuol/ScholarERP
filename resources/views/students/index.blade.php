@extends('layouts.app')

@section('content')

<div class="container-fluid">

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="card shadow">

        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">

            <h4 class="mb-0">
                Student Management
            </h4>

            <a href="{{ route('students.create') }}"
               class="btn btn-light">

                <i class="bi bi-person-plus-fill"></i>

                New Student

            </a>

        </div>

        <div class="card-body">

            <!-- Search -->

            <form method="GET"
                  action="{{ route('students.index') }}"
                  class="row mb-4">

                <div class="col-md-4">

                    <input
                        type="text"
                        name="search"
                        value="{{ request('search') }}"
                        class="form-control"
                        placeholder="Search student...">

                </div>

                <div class="col-md-2">

                    <button class="btn btn-primary">

                        <i class="bi bi-search"></i>

                        Search

                    </button>

                </div>

            </form>

            <div class="table-responsive">

                <table class="table table-bordered table-hover align-middle">

                    <thead class="table-dark">

                    <tr>

                        <th>#</th>

                        <th>Photo</th>

                        <th>Admission No</th>

                        <th>Student Name</th>

                        <th>Gender</th>

                        <th>Class</th>

                        <th>Stream</th>

                        <th>Academic Year</th>

                        <th>Status</th>

                        <th width="180">
                            Actions
                        </th>

                    </tr>

                    </thead>

                    <tbody>

                    @forelse($students as $student)

                    <tr>

                        <td>{{ $loop->iteration }}</td>

                        <td width="70">

                            @if($student->photo)

                                <img
                                    src="{{ asset('storage/'.$student->photo) }}"
                                    width="50"
                                    height="50"
                                    class="rounded-circle">

                            @else

                                <img
                                    src="https://ui-avatars.com/api/?name={{ urlencode($student->first_name.' '.$student->last_name) }}&background=0D6EFD&color=fff"
                                    width="50"
                                    class="rounded-circle">

                            @endif

                        </td>

                        <td>

                            <strong>

                                {{ $student->admission_no }}

                            </strong>

                        </td>

                        <td>

                            {{ $student->first_name }}

                            {{ $student->middle_name }}

                            {{ $student->last_name }}

                        </td>

                        <td>

                            {{ $student->gender }}

                        </td>

                        <td>

                            {{ $student->schoolClass->name ?? '-' }}

                        </td>

                        <td>

                            {{ $student->stream->name ?? '-' }}

                        </td>

                        <td>

                            {{ $student->academicYear->name ?? '-' }}

                        </td>

                        <td>

                            @if($student->status=="Active")

                                <span class="badge bg-success">

                                    Active

                                </span>

                            @elseif($student->status=="Transferred")

                                <span class="badge bg-warning">

                                    Transferred

                                </span>

                            @elseif($student->status=="Graduated")

                                <span class="badge bg-info">

                                    Graduated

                                </span>

                            @else

                                <span class="badge bg-danger">

                                    Inactive

                                </span>

                            @endif

                        </td>

                        <td>

                            <a
                                href="{{ route('students.show',$student) }}"
                                class="btn btn-sm btn-info">

                                <i class="bi bi-eye"></i>

                            </a>

                            <a
                                href="{{ route('students.edit',$student) }}"
                                class="btn btn-sm btn-warning">

                                <i class="bi bi-pencil"></i>

                            </a>

                            <form
                                action="{{ route('students.destroy',$student) }}"
                                method="POST"
                                class="d-inline">

                                @csrf
                                @method('DELETE')

                                <button
                                    onclick="return confirm('Delete student?')"
                                    class="btn btn-sm btn-danger">

                                    <i class="bi bi-trash"></i>

                                </button>

                            </form>

                        </td>

                    </tr>

                    @empty

                    <tr>

                        <td colspan="10" class="text-center">

                            No students found.

                        </td>

                    </tr>

                    @endforelse

                    </tbody>

                </table>

            </div>

            <div class="mt-3">

                {{ $students->links() }}

            </div>

        </div>

    </div>

</div>

@endsection