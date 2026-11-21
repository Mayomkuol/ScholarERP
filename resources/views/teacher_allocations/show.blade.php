@extends('layouts.app')

@section('content')

<div class="container-fluid">

    <div class="card shadow">

        <div class="card-header bg-primary text-white d-flex justify-content-between">

            <h4 class="mb-0">
                Teacher Allocation Details
            </h4>

            <a href="{{ route('teacher-allocations.index') }}"
               class="btn btn-light btn-sm">

                <i class="bi bi-arrow-left"></i>
                Back

            </a>

        </div>

        <div class="card-body">

            <table class="table table-bordered">

                <tr>
                    <th width="30%">Teacher</th>
                    <td>
                        {{ $teacherAllocation->teacher->employee_no }}
                        -
                        {{ $teacherAllocation->teacher->first_name }}
                        {{ $teacherAllocation->teacher->last_name }}
                    </td>
                </tr>

                <tr>
                    <th>Subject</th>
                    <td>{{ $teacherAllocation->subject->name }}</td>
                </tr>

                <tr>
                    <th>Class</th>
                    <td>{{ $teacherAllocation->class->name }}</td>
                </tr>

                <tr>
                    <th>Stream</th>
                    <td>{{ $teacherAllocation->stream->name ?? 'N/A' }}</td>
                </tr>

                <tr>
                    <th>Academic Year</th>
                    <td>{{ $teacherAllocation->academicYear->name }}</td>
                </tr>

                <tr>
                    <th>Term</th>
                    <td>{{ $teacherAllocation->term->name }}</td>
                </tr>

                <tr>
                    <th>Created</th>
                    <td>{{ $teacherAllocation->created_at->format('d M Y h:i A') }}</td>
                </tr>

            </table>

        </div>

    </div>

</div>

@endsection