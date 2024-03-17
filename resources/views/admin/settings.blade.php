@extends('layouts.app')

@push('css')
    <link href="{{asset('libs/flatpickr/flatpickr.min.css')}}" rel="stylesheet" type="text/css"/>
@endpush
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row mb-2">
                        <div class="col-md-6">
                            <h4 class="page-title mt-0 d-inline">Total <span class="total">0</span> Settings </h4>
                        </div>
                        <div class="col-md-6">
                            <div class="text-md-right">
                                <button type="button" class="btn btn-blue btn-add btn-xs waves-effect waves-light float-right"><i class="mdi mdi-plus-circle mr-1"></i> Add New City</button>
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-centered table-borderless table-hover mb-0" id="storeTable">
                            <thead class="thead-light">
                            <tr>
                                <th>ID</th>
                                <th>Settings Name</th>
                                <th>Settings</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                                @foreach($settings as $setting)
                                    <tr>
                                        <td>{{$setting->id}}</td>
                                        <td>{{$setting->name}}</td>
                                        <td>{{$setting->value}}</td>
                                        <td>Active</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div> <!-- end card-body-->
            </div> <!-- end card-->
        </div> <!-- end col -->
    </div>
    <div class="modal fade bs-example-modal-lg" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myLargeModalLabel">Title</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="courierName">Courier Name</label>
                        <select name="courierID" id="courierID" class="form-control"  style="width: 100%" ></select>
                    </div>
                    <div class="form-group">
                        <label for="cityName">City name</label>
                        <input type="text" name="cityName" class="form-control" id="cityName">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" id="submit" class="btn btn-primary">Save</button>
                    <input type="hidden" id="cityID">
                    @csrf
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script src="{{asset('libs/flatpickr/flatpickr.min.js')}}"></script>
    <script>
        $(document).ready(function () {
            $("#datepicker").flatpickr({mode:"range"})

            function loadcountOrders() {
                // Dashboard Detais
                $.ajax({
                    type: "get",
                    url: "{{url('admin/dashboard/getData')}}/?date="+$('#datepicker').val(),
                    contentType: "application/json",
                    success: function (response) {
                        var data = JSON.parse(response);
                        if (data["status"] === "success") {
                            $('#revenue').text(data["revenue"]+' TK');
                            $('#allOrders').text(data["allOrders"]);
                            $('#store').text(data["store"]);
                            $('#user').text(data["user"]);
                            $('#customerConfirm').text(data["customerConfirm"]);
                            $('#paid').text(data["paid"]);
                            $('#return').text(data["return"]);
                            $('#lost').text(data["lost"]);
                            $('#pendingInvoiced').text(data["pendingInvoiced"]);
                            $('#invoiced').text(data["invoiced"]);
                            $('#stockOut').text(data["stockOut"]);
                            $('#all').text(data["all"]);
                            $('#processing').text(data["processing"]);
                            $('#pendingPayment').text(data["pendingPayment"]);
                            $('#onHold').text(data["onHold"]);
                            $('#canceled').text(data["canceled"]);
                            $('#completed').text(data["completed"]);

                            // console.log(data)
                        } else {
                            if (data["status"] == "failed") {
                                // Swal.fire(data["message"]);
                            } else {
                                // Swal.fire("Something wrong ! Please try again.");
                            }
                        }
                    }
                });

            }
            loadcountOrders();
            var table = $("#stocOutTable").DataTable({
                ajax: {
                    url:"{{url('admin/dashboard/stockOutProduct')}}",
                },
                ordering: false,
                paging: false,
                lengthChange: false,
                bFilter:false,
                search:false,
                info:false,
                columns: [
                    {data: 'productCode'},
                    {data: 'productName'}
                ]
            });
            var recentUpdate = $("#recentUpdate").DataTable({
                ajax: {
                    url:"{{url('admin/dashboard/recentUpdate')}}",
                },
                ordering: false,
                paging: false,
                lengthChange: false,
                bFilter:false,
                search:false,
                info:false,
                columns: [
                    {data: 'name'},
                    {data: 'notificaton'}
                ]
            });
            $(document).on('change', '#datepicker', function(){
                loadcountOrders();
            });


        });
    </script>
@endpush

