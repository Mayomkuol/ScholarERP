@extends('layouts.app')

@section('title', 'Class Mark Sheet')

@section('content')

<div class="container-fluid">

    <div class="card shadow">

        <div class="card-header">
            <h4 class="mb-0">Class Mark Sheet</h4>
        </div>

        <div class="card-body">

            <form method="GET">

                <div class="row">

                    <div class="col-md-4">

                        <label class="form-label">Exam</label>

                        <select name="exam_id" class="form-select">

                            <option value="">Select Exam</option>

                            @foreach($exams as $exam)

                                <option value="{{ $exam->id }}"
                                    {{ $examId == $exam->id ? 'selected' : '' }}>

                                    {{ $exam->name }}

                                </option>

                            @endforeach

                        </select>

                    </div>

                    <div class="col-md-4">

                        <label class="form-label">Class</label>

                        <select name="class_id" class="form-select">

                            <option value="">Select Class</option>

                            @foreach($classes as $class)

                                <option value="{{ $class->id }}"
                                    {{ $classId == $class->id ? 'selected' : '' }}>

                                    {{ $class->name }}

                                </option>

                            @endforeach

                        </select>

                    </div>

                    <div class="col-md-2 d-flex align-items-end">

                        <button class="btn btn-primary w-100">

                            Load Mark Sheet

                        </button>

                    </div>

                </div>

            </form>

            <hr>

            @if(count($results))

            <div class="table-responsive">

                <table class="table table-bordered table-striped table-hover">

                    <thead class="table-dark">

                        <tr>

                            <th>Adm No</th>

                            <th>Student</th>

                            @foreach($subjects as $subject)

                                <th class="text-center">

                                    {{ $subject->name }}

                                </th>

                            @endforeach

                            <th>Total</th>

                            <th>Mean</th>

                            <th>Grade</th>

                            <th>Points</th>

                            <th>Position</th>

                        </tr>

                    </thead>

                    <tbody>

                    @foreach($results as $row)

                        @php

                            $student = $row['student'];

                        @endphp

                        <tr>

                            <td>

                                {{ $student->admission_no }}

                            </td>

                            <td>

                                {{ $student->first_name }}
                                {{ $student->last_name }}

                            </td>

                            @foreach($subjects as $subject)

                                @php

                                    $key = $student->id . '-' . $subject->id;

                                    $examMark = $marks->get($key);

                                @endphp

                                <td class="text-center">

                                    {{ $examMark?->marks ?? '-' }}

                                </td>

                            @endforeach

                            <td>

                                <strong>

                                    {{ $row['total'] }}

                                </strong>

                            </td>

                            <td>

                                <strong>

                                    {{ number_format($row['mean'],2) }}

                                </strong>

                            </td>

                            <td>

                                @if($row['grade'])

                                    <span class="badge bg-success">

                                        {{ $row['grade'] }}

                                    </span>

                                @else

                                    -

                                @endif

                            </td>

                            <td>

                                {{ $row['points'] }}

                            </td>

                            <td>

                                <span class="badge bg-primary">

                                    {{ $row['position'] }}

                                </span>

                            </td>

                        </tr>

                    @endforeach

                    </tbody>

                </table>

            </div>

            @else

                <div class="alert alert-info">

                    Please select an Exam and Class then click
                    <strong>Load Mark Sheet</strong>.

                </div>

            @endif

        </div>

    </div>

</div>

@endsection