<div class="row">

    <div class="col-md-6 mb-3">

        <label class="form-label">
            Academic Year
        </label>

        <select
            name="academic_year_id"
            class="form-select @error('academic_year_id') is-invalid @enderror"
            required>

            <option value="">Select Academic Year</option>

            @foreach($years as $year)

                <option
                    value="{{ $year->id }}"
                    {{ old('academic_year_id', $feeStructure->academic_year_id ?? '') == $year->id ? 'selected' : '' }}>

                    {{ $year->name }}

                </option>

            @endforeach

        </select>

        @error('academic_year_id')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror

    </div>

    <div class="col-md-6 mb-3">

        <label class="form-label">
            Term
        </label>

        <select
            name="term_id"
            class="form-select @error('term_id') is-invalid @enderror"
            required>

            <option value="">Select Term</option>

            @foreach($terms as $term)

                <option
                    value="{{ $term->id }}"
                    {{ old('term_id', $feeStructure->term_id ?? '') == $term->id ? 'selected' : '' }}>

                    {{ $term->name }}

                </option>

            @endforeach

        </select>

        @error('term_id')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror

    </div>

</div>

<div class="row">

    <div class="col-md-6 mb-3">

        <label class="form-label">
            Class
        </label>

        <select
            name="school_class_id"
            class="form-select @error('school_class_id') is-invalid @enderror"
            required>

            <option value="">Select Class</option>

            @foreach($classes as $class)

                <option
                    value="{{ $class->id }}"
                    {{ old('school_class_id', $feeStructure->school_class_id ?? '') == $class->id ? 'selected' : '' }}>

                    {{ $class->name }}

                </option>

            @endforeach

        </select>

        @error('school_class_id')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror

    </div>

    <div class="col-md-6 mb-3">

        <label class="form-label">
            Fee Category
        </label>

        <select
            name="fee_category_id"
            class="form-select @error('fee_category_id') is-invalid @enderror"
            required>

            <option value="">Select Category</option>

            @foreach($categories as $category)

                <option
                    value="{{ $category->id }}"
                    {{ old('fee_category_id', $feeStructure->fee_category_id ?? '') == $category->id ? 'selected' : '' }}>

                    {{ $category->name }}

                </option>

            @endforeach

        </select>

        @error('fee_category_id')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror

    </div>

</div>

<div class="row">

    <div class="col-md-6 mb-3">

        <label class="form-label">
            Amount
        </label>

        <input
            type="number"
            step="0.01"
            min="0"
            name="amount"
            value="{{ old('amount', $feeStructure->amount ?? '') }}"
            class="form-control @error('amount') is-invalid @enderror"
            required>

        @error('amount')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror

    </div>

</div>