@extends('layouts.app')

@section('content')

<div class="container-fluid">

    <div class="card shadow">

        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">

            <h4 class="mb-0">
                Timetable Management
            </h4>

            <a href="{{ route('timetables.create') }}" class="btn btn-light">
                <i class="bi bi-plus-circle"></i>
                New Timetable
            </a>

        </div>

        <div class="card-body">

            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Filters -->
            <div class="card mb-4">

                <div class="card-body">

                    <form method="GET">

                        <div class="row">

                            <div class="col-md-3">
                                <label class="form-label">Class</label>

                                <select name="school_class_id" class="form-select">

                                    <option value="">All Classes</option>

                                    @foreach($classes as $class)

                                        <option value="{{ $class->id }}"
                                            {{ request('school_class_id') == $class->id ? 'selected' : '' }}>

                                            {{ $class->name }}

                                        </option>

                                    @endforeach

                                </select>

                            </div>

                            <div class="col-md-3">

                                <label class="form-label">Stream</label>

                                <select name="stream_id" class="form-select">

                                    <option value="">All Streams</option>

                                    @foreach($streams as $stream)

                                        <option value="{{ $stream->id }}"
                                            {{ request('stream_id') == $stream->id ? 'selected' : '' }}>

                                            {{ $stream->name }}

                                        </option>

                                    @endforeach

                                </select>

                            </div>

                            <div class="col-md-3">

                                <label class="form-label">Teacher</label>

                                <select name="teacher_id" class="form-select">

                                    <option value="">All Teachers</option>

                                    @foreach($teachers as $teacher)

                                        <option value="{{ $teacher->id }}"
                                            {{ request('teacher_id') == $teacher->id ? 'selected' : '' }}>

                                            {{ $teacher->first_name }}
                                            {{ $teacher->last_name }}

                                        </option>

                                    @endforeach

                                </select>

                            </div>

                            <div class="col-md-3 d-flex align-items-end">

                                <button class="btn btn-primary w-100">
                                    Filter Timetable
                                </button>

                            </div>

                        </div>

                    </form>

                </div>

            </div>

            <!-- Timetable Table -->

            <div class="table-responsive">

                <table class="table table-bordered table-hover align-middle">

                    <thead class="table-dark">

                        <tr>

                            <th>Day</th>

                            <th>Lesson</th>

                            <th>Time</th>

                            <th>Subject</th>

                            <th>Teacher</th>

                            <th>Class</th>

                            <th>Stream</th>

                            <th>Room</th>

                            <th width="170">Actions</th>

                        </tr>

                    </thead>

                    <tbody>

                        @forelse($timetables as $row)

                            <tr>

                                <td>{{ $row->day }}</td>

                                <td>{{ $row->lesson_no }}</td>

                                <td>
                                    {{ date('h:i A', strtotime($row->start_time)) }}
                                    -
                                    {{ date('h:i A', strtotime($row->end_time)) }}
                                </td>

                                <td>{{ $row->subject->name }}</td>

                                <td>
                                    {{ $row->teacher->first_name }}
                                    {{ $row->teacher->last_name }}
                                </td>

                                <td>{{ $row->schoolClass->name }}</td>

                                <td>{{ $row->stream->name ?? '-' }}</td>

                                <td>{{ $row->room ?? '-' }}</td>

                                <td>

                                    <a href="{{ route('timetables.show', $row) }}"
                                       class="btn btn-info btn-sm">
                                        View
                                    </a>

                                    <a href="{{ route('timetables.edit', $row) }}"
                                       class="btn btn-warning btn-sm">
                                        Edit
                                    </a>

                                    <form action="{{ route('timetables.destroy', $row) }}"
                                          method="POST"
                                          class="d-inline">

                                        @csrf
                                        @method('DELETE')

                                        <button type="submit"
                                                class="btn btn-danger btn-sm"
                                                onclick="return confirm('Delete this timetable?')">

                                            Delete

                                        </button>

                                    </form>

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td colspan="9" class="text-center text-muted">

                                    No timetable records found.

                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>

    </div>

</div>

@endsection