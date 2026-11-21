@extends('layouts.app')

@section('content')

<div class="container">

<div class="card shadow">

<div class="card-header">

<h4>Edit Class</h4>

</div>

<div class="card-body">

<form action="{{ route('classes.update',$class) }}" method="POST">

@csrf

@method('PUT')

@include('classes.form')

<br>

<button class="btn btn-success">

Update Class

</button>

</form>

</div>

</div>

</div>

@endsection