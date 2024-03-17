<div class="tile">
    <form action="{{ url('/admin/setting/') }}" method="POST" role="form">
        @csrf
        <div class="tile-body">
            <div class="form-group">
                <label class="control-label" for="inside_dhaka">Inside Dhaka Delivery Charge</label>
                <input
                    class="form-control"
                    type="text"
                    placeholder="Enter Inside Dhaka Delivery Charge"
                    id="inside_dhaka"
                    name="inside_dhaka"
                    value="{{ Settings::get('inside_dhaka') }}"
                />
            </div>
            <hr>

            <div class="form-group pb-2">
                <label class="control-label" for="outside_dhaka">Outside Dhaka Delivery Charge</label>
                <input
                    class="form-control"
                    type="text"
                    placeholder="Enter Outside Dhaka Delivery Charge"
                    id="outside_dhaka"
                    name="outside_dhaka"
                    value="{{ Settings::get('outside_dhaka') }}"
                />
            </div>
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
