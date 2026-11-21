@extends('layouts.app')

@section('title', 'Student Report Card')

@section('content')

<div class="container-fluid">

    <div class="card shadow">

        <div class="card-header d-flex justify-content-between">

            <h4>Student Report Card</h4>

           <button class="btn btn-success" disabled>
    <i class="bi bi-printer"></i>
    Print Report (Coming Soon)
</button>

        </div>

        <div class="card-body">

            <div class="text-center mb-4">

                <h2 class="fw-bold">

                    {{ config('app.name') }}

                </h2>

                <h4>STUDENT REPORT FORM</h4>

                <hr>

            </div>

            <div class="row">

                <div class="col-md-6">

                    <table class="table table-borderless">

                        <tr>
                            <th>Admission No</th>
                            <td>{{ $student->admission_no }}</td>
                        </tr>

                        <tr>
                            <th>Student Name</th>
                            <td>
                                {{ $student->first_name }}
                                {{ $student->last_name }}
                            </td>
                        </tr>

                        <tr>
                            <th>Class</th>
                            <td>{{ $student->schoolClass->name }}</td>
                        </tr>

                    </table>

                </div>

                <div class="col-md-6">

                    <table class="table table-borderless">

                        <tr>
                            <th>Exam</th>
                            <td>{{ $exam->name }}</td>
                        </tr>

                        <tr>
                            <th>Mean Grade</th>
                            <td>{{ $result->mean_grade }}</td>
                        </tr>

                        <tr>
                            <th>Position</th>
                            <td>{{ $result->position }}</td>
                        </tr>

                    </table>

                </div>

            </div>

            <table class="table table-bordered">

                <thead class="table-dark">

                    <tr>

                        <th>Subject</th>

                        <th>Marks</th>

                        <th>Grade</th>

                        <th>Points</th>

                        <th>Remarks</th>

                    </tr>

                </thead>

                <tbody>

                    @foreach($marks as $mark)

                    <tr>

                        <td>{{ $mark->subject->name }}</td>

                        <td>{{ number_format($mark->marks,2) }}</td>

                        <td>{{ $mark->grade }}</td>

                        <td>{{ $mark->points }}</td>

                        <td>{{ $mark->remarks }}</td>

                    </tr>

                    @endforeach

                </tbody>

            </table>

            <div class="row mt-4">

                <div class="col-md-3">

                    <div class="card text-center">

                        <div class="card-body">

                            <h6>Total Marks</h6>

                            <h3>{{ $result->total_marks }}</h3>

                        </div>

                    </div>

                </div>

                <div class="col-md-3">

                    <div class="card text-center">

                        <div class="card-body">

                            <h6>Mean Score</h6>

                            <h3>{{ number_format($result->mean_score,2) }}</h3>

                        </div>

                    </div>

                </div>

                <div class="col-md-3">

                    <div class="card text-center">

                        <div class="card-body">

                            <h6>Mean Grade</h6>

                            <h3>{{ $result->mean_grade }}</h3>

                        </div>

                    </div>

                </div>

                <div class="col-md-3">

                    <div class="card text-center">

                        <div class="card-body">

                            <h6>Position</h6>

                            <h3>{{ $result->position }}</h3>

                        </div>

                    </div>

                </div>

            </div>

            <hr>

            <div class="row mt-5">

                <div class="col-md-6">

                    <strong>Class Teacher's Comment</strong>

                    <p>

                        ....................................................................

                    </p>

                </div>

                <div class="col-md-6">

                    <strong>Principal's Comment</strong>

                    <p>

                        ....................................................................

                    </p>

                </div>

            </div>

            <div class="row mt-5">

                <div class="col-md-6 text-center">

                    __________________________

                    <br>

                    Class Teacher

                </div>

                <div class="col-md-6 text-center">

                    __________________________

                    <br>

                    Principal

                </div>

            </div>

        </div>

    </div>

</div>

@endsection