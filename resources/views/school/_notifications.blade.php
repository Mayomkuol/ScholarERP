<div class="card shadow mb-4">

    <div class="card-header bg-danger text-white">

        <h5 class="mb-0">

            System Notifications

        </h5>

    </div>

    <div class="card-body">

        <div class="form-check form-switch">

            <input class="form-check-input"
                   type="checkbox"
                   name="email_notifications"
                   value="1"
                   {{ $school->email_notifications ? 'checked':'' }}>

            <label class="form-check-label">

                Enable Email Notifications

            </label>

        </div>

        <div class="form-check form-switch">

            <input class="form-check-input"
                   type="checkbox"
                   name="sms_notifications"
                   value="1"
                   {{ $school->sms_notifications ? 'checked':'' }}>

            <label class="form-check-label">

                Enable SMS Notifications

            </label>

        </div>

        <div class="form-check form-switch">

            <input class="form-check-input"
                   type="checkbox"
                   name="maintenance_mode"
                   value="1"
                   {{ $school->maintenance_mode ? 'checked':'' }}>

            <label class="form-check-label">

                Maintenance Mode

            </label>

        </div>

    </div>

</div>