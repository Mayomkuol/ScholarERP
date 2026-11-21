@extends('layouts.app')

@section('content')

<div class="container">

<div class="card shadow">

<div class="card-header">

<h4>Edit Grade</h4>

</div>

<div class="card-body">

<form method="POST"
      action="{{ route('grades.update',$grade) }}">

@csrf

@method('PUT')

<div class="row">

<div class="col-md-6 mb-3">

<label>Grade</label>

<input type="text"
       name="grade"
       value="{{ old('grade',$grade->grade) }}"
       class="form-control">

</div>

<div class="col-md-6 mb-3">

<label>Points</label>

<input type="number"
       step="0.1"
       name="points"
       value="{{ old('points',$grade->points) }}"
       class="form-control">

</div>

<div class="col-md-6 mb-3">

<label>Minimum</label>

<input type="number"
       name="min_mark"
       value="{{ old('min_mark',$grade->min_mark) }}"
       class="form-control">

</div>

<div class="col-md-6 mb-3">

<label>Maximum</label>

<input type="number"
       name="max_mark"
       value="{{ old('max_mark',$grade->max_mark) }}"
       class="form-control">

</div>

<div class="col-md-6 mb-3">

<label>Remarks</label>

<input type="text"
       name="remarks"
       value="{{ old('remarks',$grade->remarks) }}"
       class="form-control">

</div>

<div class="col-md-6 mb-3">

<label>Colour</label>

<input type="color"
       name="color"
       value="{{ old('color',$grade->color) }}"
       class="form-control form-control-color">

</div>

</div>

<button class="btn btn-primary">

Update

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