@extends('layouts.app')

@section('content')

<div class="container">

    <div class="card shadow">

        <div class="card-header bg-primary text-white">
            <h4>Create Academic Year</h4>
        </div>

        <div class="card-body">

            @if ($errors->any())

                <div class="alert alert-danger">

                    <ul class="mb-0">

                        @foreach ($errors->all() as $error)

                            <li>{{ $error }}</li>

                        @endforeach

                    </ul>

                </div>

            @endif

            <form action="{{ route('academic-years.store') }}" method="POST">

                @csrf

                <div class="mb-3">

                    <label class="form-label">

                        Academic Year

                    </label>

                    <input
                        type="text"
                        name="name"
                        class="form-control"
                        placeholder="2026/2027"
                        value="{{ old('name') }}">

                </div>

                <div class="row">

                    <div class="col-md-6">

                        <label class="form-label">

                            Start Date

                        </label>

                        <input
                            type="date"
                            name="start_date"
                            class="form-control"
                            value="{{ old('start_date') }}">

                    </div>

                    <div class="col-md-6">

                        <label class="form-label">

                            End Date

                        </label>

                        <input
                            type="date"
                            name="end_date"
                            class="form-control"
                            value="{{ old('end_date') }}">

                    </div>

                </div>

                <br>

                <div class="mb-3">

                    <label class="form-label">

                        Status

                    </label>

                    <select name="status" class="form-select">

                        <option value="Open">Open</option>

                        <option value="Closed">Closed</option>

                    </select>

                </div>

                <div class="form-check mb-3">

                    <input
                        type="checkbox"
                        name="is_current"
                        value="1"
                        class="form-check-input">

                    <label class="form-check-label">

                        Current Academic Year

                    </label>

                </div>

                <button class="btn btn-success">

                    Save Academic Year

                </button>

            </form>

        </div>

    </div>

</div>

@endsection