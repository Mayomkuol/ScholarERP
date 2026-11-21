@extends('layouts.app')

@section('content')

<div class="container">

<div class="card shadow">

<div class="card-header">

<h4>New Class</h4>

</div>

<div class="card-body">

<form action="{{ route('classes.store') }}" method="POST">

@csrf

@include('classes.form')

<br>

<button class="btn btn-primary">

Save Class

</button>

<a href="{{ route('classes.index') }}"
class="btn btn-secondary">

Cancel

</a>

</form>

</div>

</div>

</div>

@endsection