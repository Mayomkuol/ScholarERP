<form method="GET">

<div class="row">

<div class="col-md-4">

<label>Class</label>

<select name="school_class_id" class="form-select">

@foreach($classes as $class)

<option value="{{ $class->id }}">

{{ $class->name }}

</option>

@endforeach

</select>

</div>

<div class="col-md-3">

<label>Month</label>

<input
type="number"
name="month"
min="1"
max="12"
class="form-control">

</div>

<div class="col-md-3">

<label>Year</label>

<input
type="number"
name="year"
value="{{ date('Y') }}"
class="form-control">

</div>

<div class="col-md-2 d-grid">

<label>&nbsp;</label>

<button class="btn btn-primary">

Load Register

</button>

</div>

</div>

</form>


<table class="table table-bordered">

<thead>

<tr>

<th>Adm No</th>

<th>Student</th>

@foreach($days as $day)

<th>{{ $day }}</th>

@endforeach

</tr>

</thead>

<tbody>

@foreach($students as $student)

<tr>

<td>

{{ $student->admission_no }}

</td>

<td>

{{ $student->first_name }}

{{ $student->last_name }}

</td>

@foreach($days as $day)

@php

$record = $student->attendance[$day] ?? null;

@endphp

<td class="text-center">

@if(!$record)

-

@elseif($record->status=='Present')

<span class="badge bg-success">P</span>

@elseif($record->status=='Absent')

<span class="badge bg-danger">A</span>

@elseif($record->status=='Late')

<span class="badge bg-warning">L</span>

@elseif($record->status=='Excused')

<span class="badge bg-info">E</span>

@else

<span class="badge bg-secondary">S</span>

@endif

</td>

@endforeach

</tr>

@endforeach

</tbody>

</table>