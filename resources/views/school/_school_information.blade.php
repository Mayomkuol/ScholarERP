<div class="card shadow mb-4">

    <div class="card-header bg-primary text-white">

        <h5 class="mb-0">

            <i class="bi bi-building"></i>

            School Information

        </h5>

    </div>

    <div class="card-body">

        <div class="row">

            <div class="col-md-6 mb-3">

                <label>School Name</label>

                <input type="text"
                       name="name"
                       class="form-control"
                       value="{{ old('name',$school->name) }}">

            </div>

            <div class="col-md-3 mb-3">

                <label>School Code</label>

                <input type="text"
                       name="code"
                       class="form-control"
                       value="{{ old('code',$school->code) }}">

            </div>

            <div class="col-md-3 mb-3">

                <label>Registration No</label>

                <input type="text"
                       name="registration_no"
                       class="form-control"
                       value="{{ old('registration_no',$school->registration_no) }}">

            </div>

        </div>

        <div class="row">

            <div class="col-md-6">

                <label>Motto</label>

                <input type="text"
                       name="motto"
                       class="form-control"
                       value="{{ old('motto',$school->motto) }}">

            </div>

            <div class="col-md-3">

                <label>School Type</label>

                <input type="text"
                       name="school_type"
                       class="form-control"
                       value="{{ old('school_type',$school->school_type) }}">

            </div>

            <div class="col-md-3">

                <label>Ownership</label>

                <input type="text"
                       name="ownership"
                       class="form-control"
                       value="{{ old('ownership',$school->ownership) }}">

            </div>

        </div>

    </div>

</div>