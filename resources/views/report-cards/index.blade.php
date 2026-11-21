@extends('layouts.app')

@section('title', 'Report Cards')

@section('content')

<div class="container-fluid">

    <div class="card shadow">

        <div class="card-header">
            <h4 class="mb-0">Student Report Cards</h4>
        </div>

        <div class="card-body">

            @if(session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            @endif

            <div class="row">

                <div class="col-md-5">

                    <label class="form-label">Student</label>

                    <select id="student" class="form-select">

                        <option value="">-- Select Student --</option>

                        @foreach($students as $student)

                            <option value="{{ $student->id }}">

                                {{ $student->admission_no }}
                                -
                                {{ $student->first_name }}
                                {{ $student->last_name }}

                            </option>

                        @endforeach

                    </select>

                </div>

                <div class="col-md-5">

                    <label class="form-label">Exam</label>

                    <select id="exam" class="form-select">

                        <option value="">-- Select Exam --</option>

                        @foreach($exams as $exam)

                            <option value="{{ $exam->id }}">

                                {{ $exam->name }}

                            </option>

                        @endforeach

                    </select>

                </div>

                <div class="col-md-2 d-grid">

                    <label class="form-label">&nbsp;</label>

                    <button
                        type="button"
                        class="btn btn-primary"
                        onclick="openReport()">

                        <i class="bi bi-file-earmark-text"></i>

                        View Report

                    </button>

                </div>

            </div>

        </div>

    </div>

</div>

<script>

function openReport()
{
    let student = document.getElementById('student').value;
    let exam = document.getElementById('exam').value;

    if (!student || !exam) {
        alert('Please select both Student and Exam.');
        return;
    }

    window.location.href = `/report-cards/${student}/${exam}`;
}

</script>

@endsection