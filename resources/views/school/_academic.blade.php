<div class="card shadow mb-4">

    <div class="card-header bg-secondary text-white">

        <h5 class="mb-0">

            Academic Settings

        </h5>

    </div>

    <div class="card-body">

        <div class="row">

            <div class="col-md-4">

                <label>Current Academic Year</label>

                <select name="academic_year_id" class="form-select">

                    @foreach($years as $year)

                        <option value="{{ $year->id }}"
                            {{ $school->academic_year_id == $year->id ? 'selected':'' }}>

                            {{ $year->name }}

                        </option>

                    @endforeach

                </select>

            </div>

            <div class="col-md-4">

                <label>Current Term</label>

                <select name="term_id" class="form-select">

                    @foreach($terms as $term)

                        <option value="{{ $term->id }}"
                            {{ $school->term_id == $term->id ? 'selected':'' }}>

                            {{ $term->name }}

                        </option>

                    @endforeach

                </select>

            </div>

            <div class="col-md-4">

                <label>Pass Mark (%)</label>

                <input type="number"
                       name="pass_mark"
                       class="form-control"
                       value="{{ old('pass_mark',$school->pass_mark) }}">

            </div>

        </div>

    </div>

</div>