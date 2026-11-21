<div class="mb-3">
    <label>Class Name</label>
    <input type="text"
           name="name"
           class="form-control"
           value="{{ old('name', $class->name ?? '') }}"
           placeholder="Grade 1">
</div>

<div class="mb-3">
    <label>Class Code</label>
    <input type="text"
           name="code"
           class="form-control"
           value="{{ old('code', $class->code ?? '') }}"
           placeholder="G1">
</div>

<div class="mb-3">
    <label>Level</label>

    <select name="level" class="form-select">

        <option value="">Select Level</option>

        <option value="Primary">Primary</option>

        <option value="Secondary">Secondary</option>

        <option value="College">College</option>

        <option value="University">University</option>

    </select>

</div>

<div class="mb-3">

    <label>Capacity</label>

    <input
        type="number"
        name="capacity"
        class="form-control"
        value="{{ old('capacity', $class->capacity ?? 40) }}">

</div>

<div class="form-check">

    <input
        class="form-check-input"
        type="checkbox"
        name="active"
        value="1"
        {{ old('active', $class->active ?? true) ? 'checked' : '' }}>

    <label class="form-check-label">

        Active Class

    </label>

</div>