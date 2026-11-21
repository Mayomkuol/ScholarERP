@extends('layouts.app')

@section('title', 'Examination Results')

@section('content')

<div class="container-fluid">

    {{-- Success Message --}}
    @if(session('success'))

        <div class="alert alert-success">
            {{ session('success') }}
        </div>

    @endif


    {{-- Validation Errors --}}
    @if($errors->any())

        <div class="alert alert-danger">

            <ul class="mb-0">

                @foreach($errors->all() as $error)

                    <li>{{ $error }}</li>

                @endforeach

            </ul>

        </div>

    @endif


    <div class="card shadow-sm">

        <div class="card-header bg-primary text-white">

            <h4 class="mb-0">

                Examination Results

            </h4>

        </div>


        <div class="card-body">


            {{-- Filter --}}
            <form
                method="GET"
                action="{{ route('student-results.index') }}"
                class="mb-4"
            >

                <div class="row">

                    <div class="col-md-5">

                        <label class="form-label">

                            Examination

                        </label>

                        <select
                            name="exam_id"
                            class="form-select"
                            required
                        >

                            <option value="">

                                Select Examination

                            </option>

                            @foreach($exams as $exam)

                                <option
                                    value="{{ $exam->id }}"
                                    {{ request('exam_id') == $exam->id ? 'selected' : '' }}
                                >

                                    {{ $exam->name }}

                                </option>

                            @endforeach

                        </select>

                    </div>


                    <div class="col-md-5">

                        <label class="form-label">

                            Class

                        </label>

                        <select
                            name="class_id"
                            class="form-select"
                            required
                        >

                            <option value="">

                                Select Class

                            </option>

                            @foreach($classes as $class)

                                <option
                                    value="{{ $class->id }}"
                                    {{ request('class_id') == $class->id ? 'selected' : '' }}
                                >

                                    {{ $class->name }}

                                </option>

                            @endforeach

                        </select>

                    </div>


                    <div class="col-md-2 d-grid">

                        <label class="form-label">&nbsp;</label>

                        <button
                            type="submit"
                            class="btn btn-primary"
                        >

                            Load Students

                        </button>

                    </div>

                </div>

            </form>


            @if($results->count())


                {{-- Generate Results --}}
                <form
                    method="POST"
                    action="{{ route('student-results.generate') }}"
                    class="mb-4"
                >

                    @csrf

                    <input
                        type="hidden"
                        name="exam_id"
                        value="{{ request('exam_id') }}"
                    >

                    <input
                        type="hidden"
                        name="class_id"
                        value="{{ request('class_id') }}"
                    >


                    <button
                        type="submit"
                        class="btn btn-success"
                    >

                        <i class="bi bi-calculator"></i>

                        Generate / Recalculate Results

                    </button>

                </form>


                {{-- Results Table --}}

                <div class="table-responsive">

                    <table
                        class="table table-bordered table-hover align-middle"
                    >

                        <thead class="table-dark">

                            <tr>

                                <th>
                                    #
                                </th>

                                <th>
                                    Admission No
                                </th>

                                <th>
                                    Student
                                </th>

                                <th>
                                    Subjects
                                </th>

                                <th>
                                    Total
                                </th>

                                <th>
                                    Mean
                                </th>

                                <th>
                                    Points
                                </th>

                            </tr>

                        </thead>


                        <tbody>

                            @foreach($results as $index => $row)

                                <tr>

                                    <td>

                                        {{ $index + 1 }}

                                    </td>

                                    <td>

                                        {{ $row['student']->admission_no }}

                                    </td>

                                    <td>

                                        {{ $row['student']->first_name }}

                                        {{ $row['student']->middle_name }}

                                        {{ $row['student']->last_name }}

                                    </td>

                                    <td>

                                        {{ $row['marks_count'] }}

                                    </td>

                                    <td>

                                        {{ number_format(
                                            $row['total_marks'],
                                            2
                                        ) }}

                                    </td>

                                    <td>

                                        <strong>

                                            {{ number_format(
                                                $row['mean_score'],
                                                2
                                            ) }}

                                        </strong>

                                    </td>

                                    <td>

                                        {{ number_format(
                                            $row['total_points'],
                                            2
                                        ) }}

                                    </td>

                                </tr>

                            @endforeach

                        </tbody>

                    </table>

                </div>


            @elseif(request()->filled('exam_id'))

                <div class="alert alert-warning">

                    No examination marks were found for the
                    selected examination and class.

                </div>

            @else

                <div class="alert alert-info">

                    Select an examination and class to load
                    student marks.

                </div>

            @endif


        </div>

    </div>

</div>

@endsection