<div class="card shadow mb-4">

    <div class="card-header bg-dark text-white">

        <h5 class="mb-0">

            Financial Settings

        </h5>

    </div>

    <div class="card-body">

        <div class="row">

            <div class="col-md-3">

                <label>Currency</label>

                <input type="text"
                       name="currency"
                       class="form-control"
                       value="{{ old('currency',$school->currency) }}">

            </div>

            <div class="col-md-3">

                <label>Currency Symbol</label>

                <input type="text"
                       name="currency_symbol"
                       class="form-control"
                       value="{{ old('currency_symbol',$school->currency_symbol) }}">

            </div>

            <div class="col-md-3">

                <label>Receipt Prefix</label>

                <input type="text"
                       name="receipt_prefix"
                       class="form-control"
                       value="{{ old('receipt_prefix',$school->receipt_prefix) }}">

            </div>

            <div class="col-md-3">

                <label>Invoice Prefix</label>

                <input type="text"
                       name="invoice_prefix"
                       class="form-control"
                       value="{{ old('invoice_prefix',$school->invoice_prefix) }}">

            </div>

        </div>

    </div>

</div>