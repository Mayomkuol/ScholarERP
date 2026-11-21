@extends('layouts.app')

@section('content')

<div class="card shadow-sm">

    <div class="card-header">

        <h4>Edit Academic Year</h4>

    </div>

    <div class="card-body">

        <form method="POST"
              action="{{ route('academic-years.update',$academicYear) }}">

            @csrf

            @method('PUT')

            @include('academic-years.form')

            <button class="btn btn-success">

                Update

            </button>

        </form>

    </div>

</div>

@endsection