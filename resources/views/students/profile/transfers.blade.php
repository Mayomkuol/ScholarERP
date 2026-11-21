<div class="card shadow-sm">

    <div class="card-header bg-dark text-white">

        <h5 class="mb-0">

            Transfer History

        </h5>

    </div>

    <div class="card-body">

        @forelse($student->transfers as $transfer)

        <div class="card mb-3">

            <div class="card-body">

                <div class="row">

                    <div class="col-md-8">

                        <h5>

                            {{ $transfer->transfer_type }}

                        </h5>

                        <p>

                            {{ $transfer->reason }}

                        </p>

                        <small>

                            {{ $transfer->remarks }}

                        </small>

                    </div>

                    <div class="col-md-4 text-end">

                        <strong>

                            {{ $transfer->transfer_date }}

                        </strong>

                    </div>

                </div>

            </div>

        </div>

        @empty

        <div class="alert alert-secondary">

            No transfer history available.

        </div>

        @endforelse

    </div>

</div>