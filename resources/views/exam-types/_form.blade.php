<div class="row">

    <div class="col-md-6 mb-3">

        <label class="form-label">Exam Type Name</label>

        <input
            type="text"
            name="name"
            class="form-control"
            value="{{ old('name', $examType->name ?? '') }}"
            required>

    </div>

    <div class="col-md-6 mb-3">

        <label class="form-label">Code</label>

        <input
            type="text"
            name="code"
            class="form-control"
            value="{{ old('code', $examType->code ?? '') }}">

    </div>

</div>

<div class="row">

    <div class="col-md-6 mb-3">

        <label class="form-label">Percentage</label>

        <input
            type="number"
            name="percentage"
            class="form-control"
            value="{{ old('percentage', $examType->percentage ?? 100) }}"
            min="0"
            max="100">

    </div>

    <div class="col-md-6 mt-4">

        <div class="form-check">

            <input
                class="form-check-input"
                type="checkbox"
                name="active"
                value="1"
                {{ old('active', $examType->active ?? true) ? 'checked' : '' }}>

            <label class="form-check-label">

                Active

            </label>

        </div>

    </div>

</div>

<button class="btn btn-primary">

    Save

</button>

<a href="{{ route('exam-types.index') }}"
   class="btn btn-secondary">

    Cancel

</a>