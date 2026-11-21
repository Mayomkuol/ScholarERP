@extends('layouts.app')

@section('content')

<div class="container">

    <div class="card shadow">

        <div class="card-header bg-warning">

            <h4>Edit Exam Type</h4>

        </div>

        <div class="card-body">

            <form
                action="{{ route('exam-types.update',$examType) }}"
                method="POST">

                @csrf

                @method('PUT')

                @include('exam-types._form')

            </form>

        </div>

    </div>

</div>

@endsection