<div class="row">

    {{-- Category Name --}}
    <div class="col-md-6 mb-3">

        <label for="name" class="form-label">
            Category Name <span class="text-danger">*</span>
        </label>

        <input
            type="text"
            id="name"
            name="name"
            class="form-control @error('name') is-invalid @enderror"
            value="{{ old('name', $category->name ?? '') }}"
            placeholder="Enter fee category name"
            required
        >

        @error('name')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror

    </div>


    {{-- Status --}}
    <div class="col-md-6 mb-3">

        <label for="is_active" class="form-label">
            Status <span class="text-danger">*</span>
        </label>

        <select
            id="is_active"
            name="is_active"
            class="form-select @error('is_active') is-invalid @enderror"
            required
        >

            <option value="1"
                {{ old('is_active', $category->is_active ?? 1) == 1 ? 'selected' : '' }}>
                Active
            </option>

            <option value="0"
                {{ old('is_active', $category->is_active ?? 1) == 0 ? 'selected' : '' }}>
                Inactive
            </option>

        </select>

        @error('is_active')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror

    </div>

</div>


{{-- Description --}}
<div class="mb-3">

    <label for="description" class="form-label">
        Description
    </label>

    <textarea
        id="description"
        name="description"
        rows="4"
        class="form-control @error('description') is-invalid @enderror"
        placeholder="Enter a description for this fee category"
    >{{ old('description', $category->description ?? '') }}</textarea>

    @error('description')
        <div class="invalid-feedback">
            {{ $message }}
        </div>
    @enderror

</div>


{{-- Form Actions --}}
<div class="d-flex justify-content-end gap-2">

    <a
        href="{{ route('fee-categories.index') }}"
        class="btn btn-secondary"
    >
        <i class="bi bi-x-circle"></i>
        Cancel
    </a>

    <button
        type="submit"
        class="btn btn-success"
    >
        <i class="bi bi-check-circle"></i>

        {{ isset($category) ? 'Update Category' : 'Save Category' }}

    </button>

</div>