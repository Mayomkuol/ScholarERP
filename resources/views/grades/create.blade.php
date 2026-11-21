@extends('layouts.app')

@section('content')

<div class="container">

<div class="card shadow">

<div class="card-header">

<h4>Add Grade</h4>

</div>

<div class="card-body">

<form action="{{ route('grades.store') }}"
      method="POST">

@csrf

<div class="row">

<div class="col-md-6 mb-3">

<label>Grade</label>

<input type="text"
       name="grade"
       class="form-control"
       required>

</div>

<div class="col-md-6 mb-3">

<label>Points</label>

<input type="number"
       step="0.1"
       name="points"
       class="form-control">

</div>

<div class="col-md-6 mb-3">

<label>Minimum Mark</label>

<input type="number"
       name="min_mark"
       class="form-control">

</div>

<div class="col-md-6 mb-3">

<label>Maximum Mark</label>

<input type="number"
       name="max_mark"
       class="form-control">

</div>

<div class="col-md-6 mb-3">

<label>Remarks</label>

<input type="text"
       name="remarks"
       class="form-control">

</div>

<div class="col-md-6 mb-3">

<label>Colour</label>

<input type="color"
       name="color"
       class="form-control form-control-color">

</div>

</div>

<button class="btn btn-success">

Save Grade

</button>

<a href="{{ route('grades.index') }}"
   class="btn btn-secondary">

Cancel

</a>

</form>

</div>

</div>

</div>

@endsection