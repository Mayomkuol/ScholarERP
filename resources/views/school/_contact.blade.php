<div class="card shadow mb-4">

    <div class="card-header bg-info text-white">

        <h5 class="mb-0">

            Contact Information

        </h5>

    </div>

    <div class="card-body">

        <div class="row">

            <div class="col-md-4">

                <label>Email</label>

                <input type="email"
                       name="email"
                       class="form-control"
                       value="{{ old('email',$school->email) }}">

            </div>

            <div class="col-md-4">

                <label>Phone</label>

                <input type="text"
                       name="phone"
                       class="form-control"
                       value="{{ old('phone',$school->phone) }}">

            </div>

            <div class="col-md-4">

                <label>Website</label>

                <input type="text"
                       name="website"
                       class="form-control"
                       value="{{ old('website',$school->website) }}">

            </div>

        </div>

        <div class="mt-3">

            <label>Physical Address</label>

            <textarea
                name="physical_address"
                class="form-control"
                rows="3">{{ old('physical_address',$school->physical_address) }}</textarea>

        </div>

    </div>

</div>