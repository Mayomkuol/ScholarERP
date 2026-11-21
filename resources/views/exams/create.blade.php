@extends('layouts.app')

@section('title', 'Create Exam')

@section('content')

<div class="container">

    <div class="row justify-content-center">

        <div class="col-lg-8">

            <div class="card shadow">

                <div class="card-header bg-primary text-white">

                    <h4 class="mb-0">
                        Create Examination
                    </h4>

                </div>

                <div class="card-body">

                    <form action="{{ route('exams.store') }}" method="POST">

                        @csrf

                        <div class="row">

                            <div class="col-md-6 mb-3">

                                <label class="form-label">
                                    Exam Type
                                </label>

                                <select
                                    name="exam_type_id"
                                    class="form-select @error('exam_type_id') is-invalid @enderror"
                                    required>

                                    <option value="">
                                        -- Select Exam Type --
                                    </option>

                                    @foreach($types as $type)

                                        <option
                                            value="{{ $type->id }}"
                                            {{ old('exam_type_id') == $type->id ? 'selected' : '' }}>

                                            {{ $type->name }}

                                        </option>

                                    @endforeach

                                </select>

                                @error('exam_type_id')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror

                            </div>


                            <div class="col-md-6 mb-3">

                                <label class="form-label">
                                    Academic Year
                                </label>

                                <select
                                    name="academic_year_id"
                                    class="form-select @error('academic_year_id') is-invalid @enderror"
                                    required>

                                    <option value="">
                                        -- Select Academic Year --
                                    </option>

                                    @foreach($years as $year)

                                        <option
                                            value="{{ $year->id }}"
                                            {{ old('academic_year_id') == $year->id ? 'selected' : '' }}>

                                            {{ $year->name }}

                                        </option>

                                    @endforeach

                                </select>

                            </div>


                            <div class="col-md-6 mb-3">

                                <label class="form-label">
                                    Term
                                </label>

                                <select
                                    name="term_id"
                                    class="form-select @error('term_id') is-invalid @enderror"
                                    required>

                                    <option value="">
                                        -- Select Term --
                                    </option>

                                    @foreach($terms as $term)

                                        <option
                                            value="{{ $term->id }}"
                                            {{ old('term_id') == $term->id ? 'selected' : '' }}>

                                            {{ $term->name }}

                                        </option>

                                    @endforeach

                                </select>

                            </div>


                            <div class="col-md-6 mb-3">

                                <label class="form-label">
                                    Class
                                </label>

                                <select
                                    name="school_class_id"
                                    class="form-select @error('school_class_id') is-invalid @enderror"
                                    required>

                                    <option value="">
                                        -- Select Class --
                                    </option>

                                    @foreach($classes as $class)

                                        <option
                                            value="{{ $class->id }}"
                                            {{ old('school_class_id') == $class->id ? 'selected' : '' }}>

                                            {{ $class->name }}

                                        </option>

                                    @endforeach

                                </select>

                            </div>


                            <div class="col-12 mb-3">

                                <label class="form-label">
                                    Examination Name
                                </label>

                                <input
                                    type="text"
                                    name="name"
                                    class="form-control @error('name') is-invalid @enderror"
                                    value="{{ old('name') }}"
                                    placeholder="e.g Mid Term Examination"
                                    required>

                                @error('name')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror

                            </div>


                            <div class="col-md-6 mb-3">

                                <label class="form-label">
                                    Start Date
                                </label>

                                <input
                                    type="date"
                                    name="start_date"
                                    class="form-control @error('start_date') is-invalid @enderror"
                                    value="{{ old('start_date') }}"
                                    required>

                            </div>


                            <div class="col-md-6 mb-3">

                                <label class="form-label">
                                    End Date
                                </label>

                                <input
                                    type="date"
                                    name="end_date"
                                    class="form-control @error('end_date') is-invalid @enderror"
                                    value="{{ old('end_date') }}"
                                    required>

                            </div>

                        </div>

                        <hr>

                        <div class="d-flex justify-content-between">

                            <a
                                href="{{ route('exams.index') }}"
                                class="btn btn-secondary">

                                Back

                            </a>

                            <button
                                type="submit"
                                class="btn btn-primary">

                                Save Examination

                            </button>

                        </div>

                    </form>

                </div>

            </div>

        </div>

    </div>

</div>

@endsection