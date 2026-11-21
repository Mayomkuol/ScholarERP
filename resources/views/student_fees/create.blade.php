@extends('layouts.app')

@section('title', 'Assign Student Fees')

@section('content')

<div class="container-fluid">

    <div class="card shadow">

        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">

            <h4 class="mb-0">
                Assign Student Fees
            </h4>

            <a href="{{ route('student-fees.index') }}" class="btn btn-light">
                <i class="bi bi-arrow-left"></i>
                Back
            </a>

        </div>

        <div class="card-body">

            @if($errors->any())

                <div class="alert alert-danger">

                    <ul class="mb-0">

                        @foreach($errors->all() as $error)

                            <li>{{ $error }}</li>

                        @endforeach

                    </ul>

                </div>

            @endif

            {{-- Load Students --}}

            <form method="GET" class="mb-4">

                <div class="row">

                    <div class="col-md-4">

                        <label class="form-label">
                            Academic Year
                        </label>

                        <select
                            name="academic_year_id"
                            class="form-select"
                            required>

                            <option value="">Select Academic Year</option>

                            @foreach($years as $year)

                                <option
                                    value="{{ $year->id }}"
                                    {{ request('academic_year_id') == $year->id ? 'selected' : '' }}>

                                    {{ $year->name }}

                                </option>

                            @endforeach

                        </select>

                    </div>

                    <div class="col-md-4">

                        <label class="form-label">
                            Term
                        </label>

                        <select
                            name="term_id"
                            class="form-select"
                            required>

                            <option value="">Select Term</option>

                            @foreach($terms as $term)

                                <option
                                    value="{{ $term->id }}"
                                    {{ request('term_id') == $term->id ? 'selected' : '' }}>

                                    {{ $term->name }}

                                </option>

                            @endforeach

                        </select>

                    </div>

                    <div class="col-md-4">

                        <label class="form-label">
                            Class
                        </label>

                        <select
                            name="school_class_id"
                            class="form-select"
                            required>

                            <option value="">Select Class</option>

                            @foreach($classes as $class)

                                <option
                                    value="{{ $class->id }}"
                                    {{ request('school_class_id') == $class->id ? 'selected' : '' }}>

                                    {{ $class->name }}

                                </option>

                            @endforeach

                        </select>

                    </div>

                </div>

                <div class="mt-3">

                    <button class="btn btn-primary">

                        <i class="bi bi-search"></i>

                        Load Students

                    </button>

                </div>

            </form>

            @if($students->count())

            <form
                action="{{ route('student-fees.store') }}"
                method="POST">

                @csrf

                <input
                    type="hidden"
                    name="academic_year_id"
                    value="{{ request('academic_year_id') }}">

                <input
                    type="hidden"
                    name="term_id"
                    value="{{ request('term_id') }}">

                <input
                    type="hidden"
                    name="school_class_id"
                    value="{{ request('school_class_id') }}">

                <div class="table-responsive">

                    <table class="table table-bordered table-hover">

                        <thead class="table-light">

                            <tr>

                                <th width="50">

                                    <input
                                        type="checkbox"
                                        id="checkAll">

                                </th>

                                <th>Admission No</th>

                                <th>Student Name</th>

                                <th>Class</th>

                                <th>Discount</th>

                            </tr>

                        </thead>

                        <tbody>

                        @foreach($students as $student)

                            <tr>

                                <td>

                                    <input
                                        type="checkbox"
                                        name="students[]"
                                        value="{{ $student->id }}">

                                </td>

                                <td>

                                    {{ $student->admission_no }}

                                </td>

                                <td>

                                    {{ $student->first_name }}
                                    {{ $student->middle_name }}
                                    {{ $student->last_name }}

                                </td>

                                <td>

                                    {{ optional($student->class)->name }}

                                </td>

                                <td>

                                    <input
                                        type="number"
                                        step="0.01"
                                        min="0"
                                        value="0"
                                        name="discount[{{ $student->id }}]"
                                        class="form-control">

                                </td>

                            </tr>

                        @endforeach

                        </tbody>

                    </table>

                </div>

                <div class="text-end">

                    <button
                        class="btn btn-success">

                        <i class="bi bi-check-circle"></i>

                        Assign Fees

                    </button>

                </div>

            </form>

            @else

                <div class="alert alert-info">

                    Select the Academic Year, Term and Class, then click
                    <strong>Load Students</strong>.

                </div>

            @endif

        </div>

    </div>

</div>

<script>

document.getElementById('checkAll')?.addEventListener('change', function () {

    document.querySelectorAll('input[name="students[]"]').forEach(function(item){

        item.checked = document.getElementById('checkAll').checked;

    });

});

</script>

@endsection