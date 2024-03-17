<div class="tile">
    <form action="{{ url('admin/setting') }}" method="POST" role="form">
        @csrf
        <h3 class="tile-title">General Settings</h3>
        <hr>
        <div class="tile-body">
            <div class="form-group">
                <label class="control-label" for="site_name">Site Name</label>
                <input
                    class="form-control"
                    type="text"
                    placeholder="Enter site name"
                    id="site_name"
                    name="site_name"
                    value="{{ Settings::get('site_name') }}"
                />
            </div>
            <div class="form-group">
                <label class="control-label" for="site_title">Site Title</label>
                <input
                    class="form-control"
                    type="text"
                    placeholder="Enter site title"
                    id="site_title"
                    name="site_title"
                    value="{{ Settings::get('site_title') }}"
                />
            </div>
             <div class="form-group">
                <label class="control-label" for="default_email_address">Phone Number</label>
                <input
                    class="form-control"
                    type="text"
                    placeholder="Enter store default phone"
                    id="phone_number"
                    name="phone_number"
                    value="{{ Settings::get('phone_number') }}"
                />
            </div>
            <div class="form-group">
                <label class="control-label" for="sms_content">Sms Content</label>
                <input
                    class="form-control"
                    type="text"
                    placeholder="Enter Sms Content"
                    id="sms_content"
                    name="sms_content"
                    value="{{ Settings::get('sms_content') }}"
                />
            </div>
            @if(Auth::check() && Auth::user()->role->id == 1)
                <div class="form-group">
                    <label class="control-label" for="sms_username">Sms Username</label>
                    <input
                        class="form-control"
                        type="text"
                        placeholder="Enter Sms Username"
                        id="sms_username"
                        name="sms_username"
                        value="{{ Settings::get('sms_username') }}"
                    />
                </div>
                <div class="form-group">
                    <label class="control-label" for="sms_password">Sms Password</label>
                    <input
                            class="form-control"
                            type="text"
                            placeholder="Enter Sms Password"
                            id="sms_password"
                            name="sms_password"
                            value="{{ Settings::get('sms_password') }}"
                    />
                </div>
            @endif
        </div>
        <div class="tile-footer">
            <div class="row d-print-none mt-2">
                <div class="col-12 text-right">
                    <button class="btn btn-success" type="submit"><i class="fa fa-fw fa-lg fa-check-circle"></i>Update Settings</button>
                </div>
            </div>
        </div>
    </form>
</div>
