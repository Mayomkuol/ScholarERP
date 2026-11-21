<div class="card shadow-sm">

    <div class="card-header bg-primary text-white">

        <h5 class="mb-0">

            Promotion History

        </h5>

    </div>

    <div class="card-body">

        @if($student->promotions->count())

        <table class="table table-bordered table-striped">

            <thead>

                <tr>

                    <th>Date</th>
                    <th>From Class</th>
                    <th>To Class</th>
                    <th>Remarks</th>

                </tr>

            </thead>

            <tbody>

                @foreach($student->promotions as $promotion)

                <tr>

                    <td>

                        {{ $promotion->promotion_date }}

                    </td>

                    <td>

                        {{ $promotion->fromClass->name }}

                        <br>

                        <small>

                            {{ $promotion->fromYear->name }}

                        </small>

                    </td>

                    <td>

                        {{ $promotion->toClass->name }}

                        <br>

                        <small>

                            {{ $promotion->toYear->name }}

                        </small>

                    </td>

                    <td>

                        {{ $promotion->remarks }}

                    </td>

                </tr>

                @endforeach

            </tbody>

        </table>

        @else

        <div class="alert alert-info">

            No promotion history available.

        </div>

        @endif

    </div>

</div>