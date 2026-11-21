@extends('layouts.app')

@section('title', 'Exams')

@section('content')

<div class="container">

    <div class="d-flex justify-content-between mb-3">

        <h3>Examinations</h3>

        <a href="{{ route('exams.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i>
            New Exam
        </a>

    </div>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="card shadow-sm">

        <div class="card-body">

            <table class="table table-bordered table-hover">

                <thead class="table-dark">

                    <tr>

                        <th>Name</th>

                        <th>Type</th>

                        <th>Academic Year</th>

                        <th>Term</th>

                        <th>Class</th>

                        <th>Start</th>

                        <th>End</th>

                        <th>Status</th>

                        <th width="170">Action</th>

                    </tr>

                </thead>

                <tbody>

                @forelse($exams as $exam)

                    <tr>

                        <td>{{ $exam->name }}</td>

                        <td>{{ $exam->type->name ?? '-' }}</td>

                        <td>{{ $exam->academicYear->name ?? '-' }}</td>

                        <td>{{ $exam->term->name ?? '-' }}</td>

                        <td>{{ $exam->schoolClass->name ?? '-' }}</td>

                        <td>{{ $exam->start_date->format('d M Y') }}</td>

                        <td>{{ $exam->end_date->format('d M Y') }}</td>

                        <td>

                            <span class="badge bg-info">
                                {{ $exam->status }}
                            </span>

                        </td>

                        <td>

                            <a href="{{ route('exams.show',$exam) }}"
                               class="btn btn-sm btn-success">
                                View
                            </a>

                            <a href="{{ route('exams.edit',$exam) }}"
                               class="btn btn-sm btn-warning">
                                Edit
                            </a>

                        </td>

                    </tr>

                @empty

                    <tr>

                        <td colspan="9" class="text-center">
                            No exams found.
                        </td>

                    </tr>

                @endforelse

                </tbody>

            </table>

            {{ $exams->links() }}

        </div>

    </div>

</div>

@endsection