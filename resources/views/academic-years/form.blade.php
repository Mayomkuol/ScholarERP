<div class="row">

    <div class="col-md-6 mb-3">
        <label>Academic Year</label>

        <input
            type="text"
            name="name"
            class="form-control"
            value="{{ old('name', $academicYear->name ?? '') }}"
            placeholder="2026/2027">
    </div>

    <div class="col-md-3 mb-3">
        <label>Start Date</label>

        <input
            type="date"
            name="start_date"
            class="form-control"
            value="{{ old('start_date', $academicYear->start_date ?? '') }}">
    </div>

    <div class="col-md-3 mb-3">
        <label>End Date</label>

        <input
            type="date"
            name="end_date"
            class="form-control"
            value="{{ old('end_date', $academicYear->end_date ?? '') }}">
    </div>

    <div class="col-md-4 mb-3">
        <label>Status</label>

        <select name="status" class="form-select">
            <option value="Open">Open</option>
            <option value="Closed">Closed</option>
        </select>
    </div>

    <div class="col-md-4 mt-4">

        <div class="form-check">

            <input
                class="form-check-input"
                type="checkbox"
                name="is_current"
                value="1"
                {{ old('is_current', $academicYear->is_current ?? false) ? 'checked' : '' }}>

            <label class="form-check-label">

                Current Academic Year

            </label>

        </div>

    </div>

</div>