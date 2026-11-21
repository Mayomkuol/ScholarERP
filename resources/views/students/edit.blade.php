@extends('layouts.app')

@section('title', 'New Student Admission')

@section('content')

<div class="container-fluid">

    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>Please correct the following errors:</strong>
            <ul class="mb-0 mt-2">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
<div class="card mb-4">

    <div class="card-header bg-light">

        Student Status

    </div>

    <div class="card-body">

        <select
            name="status"
            class="form-select">

            <option value="Active"
                {{ old('status',$student->status)=='Active'?'selected':'' }}>
                Active
            </option>

            <option value="Transferred"
                {{ old('status',$student->status)=='Transferred'?'selected':'' }}>
                Transferred
            </option>

            <option value="Graduated"
                {{ old('status',$student->status)=='Graduated'?'selected':'' }}>
                Graduated
            </option>

            <option value="Inactive"
                {{ old('status',$student->status)=='Inactive'?'selected':'' }}>
                Inactive
            </option>

        </select>

    </div>

</div>
   <form action="{{ route('students.update',$student) }}"
      method="POST"
      enctype="multipart/form-data">

    @csrf
    @method('PUT')

        @csrf

        <div class="card shadow">

            <div class="card-header bg-primary text-white">

                <h4 class="mb-0">

                    <i class="bi bi-person-plus-fill"></i>

                    New Student Admission

                </h4>

            </div>

            <div class="card-body">

                {{-- ================= Admission Information ================= --}}

                <div class="card mb-4">

                    <div class="card-header fw-bold">

                        Admission Information

                    </div>

                    <div class="card-body">

                        <div class="row">

                            <div class="col-md-4 mb-3">

                                <label class="form-label">Admission Number</label>

                                <input type="text"
                                       class="form-control"
                                      value="{{ $student->admission_no }}"
                                       readonly>

                            </div>

                            <div class="col-md-4 mb-3">

                                <label class="form-label">Admission Date</label>

                                <input type="date"
                                       name="admission_date"
                                       value="{{ old('admission_date', date('Y-m-d')) }}"
                                       class="form-control">

                            </div>

                            <div class="col-md-4 mb-3">

                                <label class="form-label">Student Photo</label>

                                <input type="file"
                                       name="photo"
                                       class="form-control"
                                       accept="image/*"
                                       onchange="previewImage(event)">

                            </div>

                        </div>

                        <img id="preview"
                             src="https://via.placeholder.com/120x120?text=Photo"
                             class="img-thumbnail"
                             width="120">

                             @if($student->photo)

    <img
        src="{{ asset('storage/'.$student->photo) }}"
        class="img-thumbnail mt-2"
        width="120">

@endif

                    </div>

                </div>

                {{-- ================= Personal Information ================= --}}

                <div class="card mb-4">

                    <div class="card-header fw-bold">

                        Personal Information

                    </div>

                    <div class="card-body">

                        <div class="row">

                            <div class="col-md-4 mb-3">
                                <label>First Name *</label>
                                <input type="text" name="first_name"
                                      value="{{ old('first_name',$student->first_name) }}"
                                       class="form-control">
                            </div>

                            <div class="col-md-4 mb-3">
                                <label>Middle Name</label>
                                <input type="text"
                                       name="middle_name"
                                       value="{{ old('middle_name') }}"
                                       class="form-control">
                            </div>

                            <div class="col-md-4 mb-3">
                                <label>Last Name *</label>
                                <input type="text"
                                       name="last_name"
                                       value="{{ old('last_name') }}"
                                       class="form-control">
                            </div>

                        </div>

                        <div class="row">

                            <div class="col-md-3 mb-3">

                                <label>Gender *</label>

                                <select name="gender" class="form-select">

    <option value="Male"
        {{ old('gender',$student->gender)=='Male'?'selected':'' }}>
        Male
    </option>

    <option value="Female"
        {{ old('gender',$student->gender)=='Female'?'selected':'' }}>
        Female
    </option>

</select>

                            </div>

                            <div class="col-md-3 mb-3">

                                <label>Date of Birth *</label>

                                <input type="date"
                                       name="dob"
                                       class="form-control">

                            </div>

                            <div class="col-md-3 mb-3">

                                <label>Birth Certificate</label>

                                <input type="text"
                                       name="birth_certificate"
                                       class="form-control">

                            </div>

                            <div class="col-md-3 mb-3">

                                <label>Blood Group</label>

                                <input type="text"
                                       name="blood_group"
                                       class="form-control">

                            </div>

                        </div>

                        <div class="row">

                            <div class="col-md-6 mb-3">

                                <label>Religion</label>

                                <input type="text"
                                       name="religion"
                                       class="form-control">

                            </div>

                            <div class="col-md-6 mb-3">

                                <label>Nationality</label>

                                <input type="text"
                                       name="nationality"
                                       class="form-control">

                            </div>

                        </div>

                    </div>

                </div>

                {{-- ================= Academic Information ================= --}}

                <div class="card mb-4">

                    <div class="card-header fw-bold">

                        Academic Information

                    </div>

                    <div class="card-body">

                        <div class="row">

                            <div class="col-md-3">

                                <label>Academic Year</label>

                                <select name="academic_year_id" class="form-select">

                                    @foreach($years as $year)

<option
    value="{{ $year->id }}"
    {{ old('academic_year_id',$student->academic_year_id)==$year->id ? 'selected':'' }}>

    {{ $year->name }}

</option>

                                    @endforeach

                                </select>

                            </div>

                            <div class="col-md-3">

                                <label>Term</label>

                                <select name="term_id" class="form-select">

                                   @foreach($terms as $term)

<option
    value="{{ $term->id }}"
    {{ old('term_id',$student->term_id)==$term->id ? 'selected':'' }}>

    {{ $term->name }}

</option>

@endforeach

                                </select>

                            </div>

                            <div class="col-md-3">

                                <label>Class</label>

                                <select name="school_class_id"
                                        class="form-select">

                                   @foreach($classes as $class)

<option
    value="{{ $class->id }}"
    {{ old('school_class_id',$student->school_class_id)==$class->id ? 'selected':'' }}>

    {{ $class->name }}

</option>

@endforeach

                                </select>

                            </div>

                            <div class="col-md-3">

                                <label>Stream</label>

                                <select name="stream_id"
                                        class="form-select">

                                   <option value="">None</option>

@foreach($streams as $stream)

<option
    value="{{ $stream->id }}"
    {{ old('stream_id',$student->stream_id)==$stream->id ? 'selected':'' }}>

    {{ $stream->name }}

</option>

@endforeach

                                </select>

                            </div>

                        </div>

                    </div>

                </div>

                {{-- ================= Contact ================= --}}

                <div class="card mb-4">

                    <div class="card-header fw-bold">

                        Contact Information

                    </div>

                    <div class="card-body">

                        <div class="row">

                            <div class="col-md-4">

                                <label>Phone</label>

                                <input type="text"
                                       name="phone"
                                       class="form-control">

                            </div>

                            <div class="col-md-4">

                                <label>Email</label>

                                <input type="email"
                                       name="email"
                                       class="form-control">

                            </div>

                            <div class="col-md-4">

                                <label>Address</label>

                                <textarea name="address"
                                          rows="1"
                                          class="form-control"></textarea>

                            </div>

                        </div>

                    </div>

                </div>

                {{-- ================= Parent ================= --}}

                <div class="card mb-4">

                    <div class="card-header fw-bold">

                        Parent Information

                    </div>

                    <div class="card-body">

                        <div class="row">

                            <div class="col-md-3">

                                <label>Parent Name *</label>

                                <input type="text"
                                       name="parent_name"
                                       class="form-control">

                            </div>

                            <div class="col-md-3">

                                <label>Parent Phone *</label>

                                <input type="text"
                                       name="parent_phone"
                                       class="form-control">

                            </div>

                            <div class="col-md-3">

                                <label>Parent Email</label>

                                <input type="email"
                                       name="parent_email"
                                       class="form-control">

                            </div>

                        </div>

                        <hr>

                        <div class="row">

                            <div class="col-md-3">

                                <label>Father Name</label>

                                <input type="text"
                                       name="father_name"
                                       class="form-control">

                            </div>

                            <div class="col-md-3">

                                <label>Father Phone</label>

                                <input type="text"
                                       name="father_phone"
                                       class="form-control">

                            </div>

                            <div class="col-md-3">

                                <label>Mother Name</label>

                                <input type="text"
                                       name="mother_name"
                                       class="form-control">

                            </div>

                            <div class="col-md-3">

                                <label>Mother Phone</label>

                                <input type="text"
                                       name="mother_phone"
                                       class="form-control">

                            </div>

                        </div>

                    </div>

                </div>

                {{-- ================= Guardian ================= --}}

                <div class="card mb-4">

                    <div class="card-header fw-bold">

                        Guardian Information

                    </div>

                    <div class="card-body">

                        <div class="row">

                            <div class="col-md-4">

                                <label>Guardian Name</label>

                                <input type="text"
                                       name="guardian_name"
                                       class="form-control">

                            </div>

                            <div class="col-md-4">

                                <label>Guardian Phone</label>

                                <input type="text"
                                       name="guardian_phone"
                                       class="form-control">

                            </div>

                            <div class="col-md-4">

                                <label>Relationship</label>

                                <input type="text"
                                       name="guardian_relationship"
                                       class="form-control">

                            </div>

                        </div>

                    </div>

                </div>

                {{-- ================= Medical ================= --}}

                <div class="card mb-4">

                    <div class="card-header fw-bold">

                        Medical Information

                    </div>

                    <div class="card-body">

                        <div class="mb-3">

                            <label>Medical Conditions</label>

                            <textarea name="medical_conditions"
                                      rows="3"
                                      class="form-control"></textarea>

                        </div>

                        <div>

                            <label>Allergies</label>

                            <textarea name="allergies"
                                      rows="3"
                                      class="form-control"></textarea>

                        </div>

                    </div>

                </div>

                {{-- ================= Previous School ================= --}}

                <div class="card mb-4">

                    <div class="card-header fw-bold">

                        Previous School

                    </div>

                    <div class="card-body">

                        <input type="text"
                               name="previous_school"
                               class="form-control"
                               placeholder="Previous School Name">

                    </div>

                </div>

                <div class="text-end">

                    <a href="{{ route('students.index') }}"
                       class="btn btn-secondary">

                        Cancel

                    </a>

                    <button type="Update Student"
                            class="btn btn-success">

                       <i class="bi bi-check2-circle"></i>

                      Update Student

                    </button>

                </div>

            </div>

        </div>

    </form>

</div>

<script>
function previewImage(event){

    const reader = new FileReader();

    reader.onload = function(){

        document.getElementById('preview').src = reader.result;

    }

    reader.readAsDataURL(event.target.files[0]);

}
</script>

@endsection