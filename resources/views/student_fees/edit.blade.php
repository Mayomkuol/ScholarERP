@extends('layouts.app')

@section('title', 'Edit Student Fee')

@section('content')

<div class="container-fluid">

    <div class="card shadow">

        <div class="card-header bg-warning d-flex justify-content-between align-items-center">

            <h4 class="mb-0 text-dark">
                Edit Student Fee Assignment
            </h4>

            <a href="{{ route('student-fees.index') }}" class="btn btn-dark">
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

            <form action="{{ route('student-fees.update', $studentFee) }}" method="POST">

                @csrf
                @method('PUT')

                <div class="row">

                    <div class="col-md-6 mb-3">

                        <label class="form-label">
                            Student
                        </label>

                        <input
                            type="text"
                            class="form-control"
                            value="{{ $studentFee->student->admission_no }} - {{ $studentFee->student->first_name }} {{ $studentFee->student->middle_name }} {{ $studentFee->student->last_name }}"
                            readonly>

                    </div>

                    <div class="col-md-3 mb-3">

                        <label class="form-label">
                            Academic Year
                        </label>

                        <select
                            name="academic_year_id"
                            class="form-select"
                            required>

                            @foreach($years as $year)

                                <option
                                    value="{{ $year->id }}"
                                    {{ old('academic_year_id', $studentFee->academic_year_id) == $year->id ? 'selected' : '' }}>

                                    {{ $year->name }}

                                </option>

                            @endforeach

                        </select>

                    </div>

                    <div class="col-md-3 mb-3">

                        <label class="form-label">
                            Term
                        </label>

                        <select
                            name="term_id"
                            class="form-select"
                            required>

                            @foreach($terms as $term)

                                <option
                                    value="{{ $term->id }}"
                                    {{ old('term_id', $studentFee->term_id) == $term->id ? 'selected' : '' }}>

                                    {{ $term->name }}

                                </option>

                            @endforeach

                        </select>

                    </div>

                </div>

                <div class="row">

                    <div class="col-md-4 mb-3">

                        <label class="form-label">
                            Class
                        </label>

                        <select
                            name="school_class_id"
                            class="form-select"
                            required>

                            @foreach($classes as $class)

                                <option
                                    value="{{ $class->id }}"
                                    {{ old('school_class_id', $studentFee->school_class_id) == $class->id ? 'selected' : '' }}>

                                    {{ $class->name }}

                                </option>

                            @endforeach

                        </select>

                    </div>

                    <div class="col-md-4 mb-3">

                        <label class="form-label">
                            Total Fee
                        </label>

                        <input
                            type="number"
                            step="0.01"
                            min="0"
                            name="total_fee"
                            class="form-control"
                            value="{{ old('total_fee', $studentFee->total_fee) }}"
                            required>

                    </div>

                    <div class="col-md-4 mb-3">

                        <label class="form-label">
                            Discount
                        </label>

                        <input
                            type="number"
                            step="0.01"
                            min="0"
                            name="discount"
                            class="form-control"
                            value="{{ old('discount', $studentFee->discount) }}">

                    </div>

                </div>

                <div class="row">

                    <div class="col-md-4 mb-3">

                        <label class="form-label">
                            Net Fee
                        </label>

                        <input
                            type="number"
                            step="0.01"
                            min="0"
                            name="net_fee"
                            class="form-control"
                            value="{{ old('net_fee', $studentFee->net_fee) }}"
                            required>

                    </div>

                    <div class="col-md-8 mb-3">

                        <label class="form-label">
                            Remarks
                        </label>

                        <textarea
                            name="remarks"
                            rows="3"
                            class="form-control">{{ old('remarks', $studentFee->remarks) }}</textarea>

                    </div>

                </div>

                <hr>

                <div class="text-end">

                    <a href="{{ route('student-fees.index') }}" class="btn btn-secondary">

                        Cancel

                    </a>

                    <button class="btn btn-warning">

                        <i class="bi bi-save"></i>

                        Update Fee Assignment

                    </button>

                </div>

            </form>

        </div>

    </div>

</div>

@endsection