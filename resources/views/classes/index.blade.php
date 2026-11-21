@extends('layouts.app')

@section('content')

<div class="container">

<div class="d-flex justify-content-between mb-3">

<h3>School Classes</h3>

<a href="{{ route('classes.create') }}"
class="btn btn-primary">

New Class

</a>

</div>

@if(session('success'))

<div class="alert alert-success">

{{ session('success') }}

</div>

@endif

<div class="card shadow">

<div class="card-body">

<table class="table table-bordered">

<thead>

<tr>

<th>ID</th>

<th>Name</th>

<th>Code</th>

<th>Level</th>

<th>Capacity</th>

<th>Status</th>

<th width="180">Action</th>

</tr>

</thead>

<tbody>

@forelse($classes as $class)

<tr>

<td>{{ $class->id }}</td>

<td>{{ $class->name }}</td>

<td>{{ $class->code }}</td>

<td>{{ $class->level }}</td>

<td>{{ $class->capacity }}</td>

<td>

@if($class->active)

<span class="badge bg-success">

Active

</span>

@else

<span class="badge bg-danger">

Inactive

</span>

@endif

</td>

<td>

<a href="{{ route('classes.edit',$class) }}"
class="btn btn-warning btn-sm">

Edit

</a>

<form
action="{{ route('classes.destroy',$class) }}"
method="POST"
class="d-inline">

@csrf

@method('DELETE')

<button
class="btn btn-danger btn-sm"
onclick="return confirm('Delete this class?')">

Delete

</button>

</form>

</td>

</tr>

@empty

<tr>

<td colspan="7" class="text-center">

No classes found.

</td>

</tr>

@endforelse

</tbody>

</table>

{{ $classes->links() }}

</div>

</div>

</div>

@endsection