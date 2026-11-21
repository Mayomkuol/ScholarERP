<div class="row">

    <div class="col-md-6 mb-3">

        <label class="form-label">
            Student
        </label>

        <select
            name="student_id"
            class="form-select"
            required>

            <option value="">
                Select Student
            </option>

            @foreach($students as $student)

                <option
                    value="{{ $student->id }}"
                    {{ old('student_id', $studentFee->student_id ?? '') == $student->id ? 'selected' : '' }}>

                    {{ $student->admission_no }}
                    -
                    {{ $student->first_name }}
                    {{ $student->middle_name }}
                    {{ $student->last_name }}

                </option>

            @endforeach

        </select>

    </div>

    <div class="col-md-3 mb-3">

        <label class="form-label">
            Academic Year
        </label>

        <select
            name="academic_year_id"
            class="form-select"
            required>

            @foreach($years as $year)

                <option
                    value="{{ $year->id }}"
                    {{ old('academic_year_id', $studentFee->academic_year_id ?? '') == $year->id ? 'selected' : '' }}>

                    {{ $year->name }}

                </option>

            @endforeach

        </select>

    </div>

    <div class="col-md-3 mb-3">

        <label class="form-label">
            Term
        </label>

        <select
            name="term_id"
            class="form-select"
            required>

            @foreach($terms as $term)

                <option
                    value="{{ $term->id }}"
                    {{ old('term_id', $studentFee->term_id ?? '') == $term->id ? 'selected' : '' }}>

                    {{ $term->name }}

                </option>

            @endforeach

        </select>

    </div>

</div>

<div class="row">

    <div class="col-md-4 mb-3">

        <label class="form-label">
            Class
        </label>

        <select
            name="school_class_id"
            class="form-select"
            required>

            @foreach($classes as $class)

                <option
                    value="{{ $class->id }}"
                    {{ old('school_class_id', $studentFee->school_class_id ?? '') == $class->id ? 'selected' : '' }}>

                    {{ $class->name }}

                </option>

            @endforeach

        </select>

    </div>

    <div class="col-md-4 mb-3">

        <label class="form-label">
            Total Fee
        </label>

        <input
            type="number"
            name="total_fee"
            class="form-control"
            step="0.01"
            min="0"
            value="{{ old('total_fee', $studentFee->total_fee ?? '') }}"
            required>

    </div>

    <div class="col-md-4 mb-3">

        <label class="form-label">
            Discount
        </label>

        <input
            type="number"
            name="discount"
            class="form-control"
            step="0.01"
            min="0"
            value="{{ old('discount', $studentFee->discount ?? 0) }}">

    </div>

</div>

<div class="row">

    <div class="col-md-4 mb-3">

        <label class="form-label">
            Net Fee
        </label>

        <input
            type="number"
            name="net_fee"
            class="form-control"
            step="0.01"
            min="0"
            value="{{ old('net_fee', $studentFee->net_fee ?? '') }}"
            required>

    </div>

    <div class="col-md-8 mb-3">

        <label class="form-label">
            Remarks
        </label>

        <textarea
            name="remarks"
            rows="3"
            class="form-control">{{ old('remarks', $studentFee->remarks ?? '') }}</textarea>

    </div>

</div>

<hr>

<div class="text-end">

    <a href="{{ route('student-fees.index') }}" class="btn btn-secondary">

        Cancel

    </a>

    <button type="submit" class="btn btn-primary">

        <i class="bi bi-save"></i>

        {{ isset($studentFee) ? 'Update Student Fee' : 'Save Student Fee' }}

    </button>

</div>