<div class="mb-3">
    <label class="form-label">Academic Year</label>

    <select name="academic_year_id" class="form-select" required>

        <option value="">Select Academic Year</option>

        @foreach($academicYears as $year)

            <option
                value="{{ $year->id }}"
                {{ old('academic_year_id', $term->academic_year_id ?? '') == $year->id ? 'selected' : '' }}>

                {{ $year->name }}

            </option>

        @endforeach

    </select>
</div>

<div class="mb-3">
    <label class="form-label">Term Name</label>

    <input
        type="text"
        name="name"
        class="form-control"
        value="{{ old('name', $term->name ?? '') }}"
        placeholder="Term 1"
        required>
</div>

<div class="mb-3">
    <label class="form-label">Code</label>

    <input
        type="text"
        name="code"
        class="form-control"
        value="{{ old('code', $term->code ?? '') }}"
        placeholder="T1">
</div>

<div class="row">

    <div class="col-md-6">

        <label class="form-label">Start Date</label>

        <input
            type="date"
            name="start_date"
            class="form-control"
            value="{{ old('start_date', $term->start_date ?? '') }}"
            required>

    </div>

    <div class="col-md-6">

        <label class="form-label">End Date</label>

        <input
            type="date"
            name="end_date"
            class="form-control"
            value="{{ old('end_date', $term->end_date ?? '') }}"
            required>

    </div>

</div>

<br>

<div class="mb-3">

    <label>Status</label>

    <select name="status" class="form-select">

        <option value="Open"
        {{ old('status', $term->status ?? '') == 'Open' ? 'selected' : '' }}>
            Open
        </option>

        <option value="Closed"
        {{ old('status', $term->status ?? '') == 'Closed' ? 'selected' : '' }}>
            Closed
        </option>

    </select>

</div>

<div class="form-check">

    <input
        type="checkbox"
        class="form-check-input"
        name="is_current"
        value="1"
        {{ old('is_current', $term->is_current ?? false) ? 'checked' : '' }}>

    <label class="form-check-label">

        Current Term

    </label>

</div>