@extends('layouts.app')

@section('content')

<div class="container-fluid">

    <div class="card shadow">

        <div class="card-header bg-warning text-dark">

            <h4 class="mb-0">
                Edit Timetable
            </h4>

        </div>

        <div class="card-body">

            <form action="{{ route('timetables.update',$timetable) }}" method="POST">

                @csrf
                @method('PUT')

                <div class="row">

                    <div class="col-md-4 mb-3">

                        <label class="form-label">Academic Year</label>

                        <select name="academic_year_id" class="form-select" required>

                            @foreach($years as $year)

                                <option value="{{ $year->id }}"
                                    {{ $timetable->academic_year_id == $year->id ? 'selected' : '' }}>

                                    {{ $year->name }}

                                </option>

                            @endforeach

                        </select>

                    </div>

                    <div class="col-md-4 mb-3">

                        <label class="form-label">Term</label>

                        <select name="term_id" class="form-select">

                            @foreach($terms as $term)

                                <option value="{{ $term->id }}"
                                    {{ $timetable->term_id == $term->id ? 'selected' : '' }}>

                                    {{ $term->name }}

                                </option>

                            @endforeach

                        </select>

                    </div>

                    <div class="col-md-4 mb-3">

                        <label class="form-label">Teacher</label>

                        <select name="teacher_id" class="form-select">

                            @foreach($teachers as $teacher)

                                <option value="{{ $teacher->id }}"
                                    {{ $timetable->teacher_id == $teacher->id ? 'selected' : '' }}>

                                    {{ $teacher->first_name }}
                                    {{ $teacher->last_name }}

                                </option>

                            @endforeach

                        </select>

                    </div>

                    <div class="col-md-4 mb-3">

                        <label class="form-label">Subject</label>

                        <select name="subject_id" class="form-select">

                            @foreach($subjects as $subject)

                                <option value="{{ $subject->id }}"
                                    {{ $timetable->subject_id == $subject->id ? 'selected' : '' }}>

                                    {{ $subject->name }}

                                </option>

                            @endforeach

                        </select>

                    </div>

                    <div class="col-md-4 mb-3">

                        <label class="form-label">Class</label>

                        <select name="school_class_id" class="form-select">

                            @foreach($classes as $class)

                                <option value="{{ $class->id }}"
                                    {{ $timetable->school_class_id == $class->id ? 'selected' : '' }}>

                                    {{ $class->name }}

                                </option>

                            @endforeach

                        </select>

                    </div>

                    <div class="col-md-4 mb-3">

                        <label class="form-label">Stream</label>

                        <select name="stream_id" class="form-select">

                            <option value="">None</option>

                            @foreach($streams as $stream)

                                <option value="{{ $stream->id }}"
                                    {{ $timetable->stream_id == $stream->id ? 'selected' : '' }}>

                                    {{ $stream->name }}

                                </option>

                            @endforeach

                        </select>

                    </div>

                    <div class="col-md-3">

                        <label class="form-label">Day</label>

                        <select name="day" class="form-select">

                            @foreach(['Monday','Tuesday','Wednesday','Thursday','Friday','Saturday'] as $day)

                                <option value="{{ $day }}"
                                    {{ $timetable->day == $day ? 'selected' : '' }}>

                                    {{ $day }}

                                </option>

                            @endforeach

                        </select>

                    </div>

                    <div class="col-md-3">

                        <label class="form-label">Period</label>

                        <input type="number"
                               name="period"
                               value="{{ $timetable->period }}"
                               class="form-control">

                    </div>

                    <div class="col-md-3">

                        <label class="form-label">Start Time</label>

                        <input type="time"
                               name="start_time"
                               value="{{ $timetable->start_time }}"
                               class="form-control">

                    </div>

                    <div class="col-md-3">

                        <label class="form-label">End Time</label>

                        <input type="time"
                               name="end_time"
                               value="{{ $timetable->end_time }}"
                               class="form-control">

                    </div>

                    <div class="col-md-6 mt-3">

                        <label class="form-label">Room</label>

                        <input type="text"
                               name="room"
                               value="{{ $timetable->room }}"
                               class="form-control">

                    </div>

                    <div class="col-md-12 mt-4">

                        <button class="btn btn-warning">

                            Update Timetable

                        </button>

                        <a href="{{ route('timetables.index') }}"
                           class="btn btn-secondary">

                            Cancel

                        </a>

                    </div>

                </div>

            </form>

        </div>

    </div>

</div>

@endsection