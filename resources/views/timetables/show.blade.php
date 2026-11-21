@extends('layouts.app')

@section('content')

<div class="container-fluid">

    <div class="card shadow">

        <div class="card-header bg-info text-white">

            <h4 class="mb-0">

                Timetable Details

            </h4>

        </div>

        <div class="card-body">

            <table class="table table-bordered">

                <tr>
                    <th width="220">Teacher</th>
                    <td>{{ $timetable->teacher->first_name }} {{ $timetable->teacher->last_name }}</td>
                </tr>

                <tr>
                    <th>Subject</th>
                    <td>{{ $timetable->subject->name }}</td>
                </tr>

                <tr>
                    <th>Academic Year</th>
                    <td>{{ $timetable->academicYear->name }}</td>
                </tr>

                <tr>
                    <th>Term</th>
                    <td>{{ $timetable->term->name }}</td>
                </tr>

                <tr>
                    <th>Class</th>
                    <td>{{ $timetable->schoolClass->name }}</td>
                </tr>

                <tr>
                    <th>Stream</th>
                    <td>{{ $timetable->stream->name ?? '-' }}</td>
                </tr>

                <tr>
                    <th>Day</th>
                    <td>{{ $timetable->day }}</td>
                </tr>

                <tr>
                    <th>Period</th>
                    <td>{{ $timetable->period }}</td>
                </tr>

                <tr>
                    <th>Start Time</th>
                    <td>{{ date('h:i A', strtotime($timetable->start_time)) }}</td>
                </tr>

                <tr>
                    <th>End Time</th>
                    <td>{{ date('h:i A', strtotime($timetable->end_time)) }}</td>
                </tr>

                <tr>
                    <th>Room</th>
                    <td>{{ $timetable->room ?? '-' }}</td>
                </tr>

            </table>

            <a href="{{ route('timetables.edit',$timetable) }}"
               class="btn btn-warning">

                Edit

            </a>

            <a href="{{ route('timetables.index') }}"
               class="btn btn-secondary">

                Back

            </a>

        </div>

    </div>

</div>

@endsection