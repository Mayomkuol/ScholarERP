<div class="card shadow mb-4">

    <div class="card-header bg-warning">

        <h5 class="mb-0">

            Principal Information

        </h5>

    </div>

    <div class="card-body">

        <div class="row">

            <div class="col-md-4">

                <label>Name</label>

                <input type="text"
                       name="principal_name"
                       class="form-control"
                       value="{{ old('principal_name',$school->principal_name) }}">

            </div>

            <div class="col-md-4">

                <label>Email</label>

                <input type="email"
                       name="principal_email"
                       class="form-control"
                       value="{{ old('principal_email',$school->principal_email) }}">

            </div>

            <div class="col-md-4">

                <label>Phone</label>

                <input type="text"
                       name="principal_phone"
                       class="form-control"
                       value="{{ old('principal_phone',$school->principal_phone) }}">

            </div>

        </div>

    </div>

</div>