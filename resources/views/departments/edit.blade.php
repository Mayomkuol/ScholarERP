@extends('layouts.app')

@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h3 class="fw-bold mb-0">

                Edit Department

            </h3>

            <small class="text-muted">

                Update department information

            </small>

        </div>

        <a href="{{ route('departments.index') }}"
           class="btn btn-secondary">

            <i class="bi bi-arrow-left"></i>

            Back

        </a>

    </div>

    @if($errors->any())

        <div class="alert alert-danger">

            <ul class="mb-0">

                @foreach($errors->all() as $error)

                    <li>{{ $error }}</li>

                @endforeach

            </ul>

        </div>

    @endif

    <div class="card shadow">

        <div class="card-header bg-warning">

            <h5 class="mb-0">

                Department Details

            </h5>

        </div>

        <div class="card-body">

            <form action="{{ route('departments.update',$department) }}"
                  method="POST">

                @csrf
                @method('PUT')

                <div class="row">

                    <div class="col-md-6 mb-3">

                        <label class="form-label">

                            Department Name

                        </label>

                        <input
                            type="text"
                            name="name"
                            class="form-control"
                            value="{{ old('name',$department->name) }}"
                            required>

                    </div>

                    <div class="col-md-6 mb-3">

                        <label class="form-label">

                            Department Code

                        </label>

                        <input
                            type="text"
                            name="code"
                            class="form-control"
                            value="{{ old('code',$department->code) }}"
                            required>

                    </div>

                </div>

                <div class="mb-3">

                    <label class="form-label">

                        Description

                    </label>

                    <textarea
                        name="description"
                        rows="4"
                        class="form-control">{{ old('description',$department->description) }}</textarea>

                </div>

                <div class="mb-4">

                    <label class="form-label">

                        Status

                    </label>

                    <select name="status"
                            class="form-select">

                        <option value="1"
                            {{ $department->status ? 'selected' : '' }}>

                            Active

                        </option>

                        <option value="0"
                            {{ !$department->status ? 'selected' : '' }}>

                            Inactive

                        </option>

                    </select>

                </div>

                <div class="text-end">

                    <a href="{{ route('departments.index') }}"
                       class="btn btn-secondary">

                        Cancel

                    </a>

                    <button class="btn btn-success">

                        <i class="bi bi-check-circle"></i>

                        Update Department

                    </button>

                </div>

            </form>

        </div>

    </div>

</div>

@endsection