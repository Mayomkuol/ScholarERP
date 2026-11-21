@extends('layouts.app')

@section('content')

<div class="container">

    <div class="card shadow">

        <div class="card-header bg-primary text-white">

            <h4>Create Exam Type</h4>

        </div>

        <div class="card-body">

            <form
                action="{{ route('exam-types.store') }}"
                method="POST">

                @csrf

                @include('exam-types._form')

            </form>

        </div>

    </div>

</div>

@endsection