@extends('layouts.app')

@push('css')
    <link href="{{asset('libs/flatpickr/flatpickr.min.css')}}" rel="stylesheet" type="text/css"/>
 @endpush
@section('content')

    <div class="row">
        <div class="col-md-6 col-xl-3">
            <div class="widget-rounded-circle card-box">
                <div class="row">
                    <div class="col-6">
                        <div class="avatar-lg rounded bg-soft-primary">
                            <i class="dripicons-wallet font-24 avatar-title text-primary"></i>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="text-right">
                            <h3 class="text-dark mt-1">
                                <span id="revenue" data-plugin="counterup">0 Tk</span>
                            </h3>
                            <p class="text-muted mb-1 text-truncate">Total Revenue</p>
                        </div>
                    </div>
                </div> <!-- end row-->
            </div> <!-- end widget-rounded-circle-->
        </div> <!-- end col-->

        <div class="col-md-6 col-xl-3">
            <div class="widget-rounded-circle card-box">
                <div class="row">
                    <div class="col-6">
                        <div class="avatar-lg rounded bg-soft-success">
                            <i class="dripicons-basket font-24 avatar-title text-success"></i>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="text-right">
                            <h3 class="text-dark mt-1">
                                <span id="allOrders" data-plugin="counterup">0</span>
                            </h3>
                            <p class="text-muted mb-1 text-truncate">Orders</p>
                        </div>
                    </div>
                </div> <!-- end row-->
            </div> <!-- end widget-rounded-circle-->
        </div> <!-- end col-->

        <div class="col-md-6 col-xl-3">
            <div class="widget-rounded-circle card-box">
                <div class="row">
                    <div class="col-6">
                        <div class="avatar-lg rounded bg-soft-info">
                            <i class="dripicons-store font-24 avatar-title text-info"></i>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="text-right">
                            <h3 class="text-dark mt-1">
                                <span id="store" data-plugin="counterup">0</span>
                            </h3>
                            <p class="text-muted mb-1 text-truncate">Stores</p>
                        </div>
                    </div>
                </div> <!-- end row-->
            </div> <!-- end widget-rounded-circle-->
        </div> <!-- end col-->

        <div class="col-md-6 col-xl-3">
            <div class="widget-rounded-circle card-box">
                <div class="row">
                    <div class="col-6">
                        <div class="avatar-lg rounded bg-soft-warning">
                            <i class="dripicons-user-group font-24 avatar-title text-warning"></i>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="text-right">
                            <h3 class="text-dark mt-1">
                                <span id="user" data-plugin="counterup">0</span>
                            </h3>
                            <p class="text-muted mb-1 text-truncate">Users</p>
                        </div>
                    </div>
                </div> <!-- end row-->
            </div> <!-- end widget-rounded-circle-->
        </div> <!-- end col-->
    </div>

    <div class="row">
        <div class="col-xl-4">
            <div class="card">
                <div class="card-body">
                    <h4 class="header-title mb-3">Today Report</h4>
                    <div class="table-responsive">
                        <table class="table table-striped table-sm table-nowrap table-centered mb-0">
                            <tbody>
                            <tr>
                                <td><h5 class="font-15 my-1 font-weight-normal">Today Order</h5></td>
                                <td><span id="all">0</span></td>
                            </tr>
                            <tr>
                                <td><h5 class="font-15 my-1 font-weight-normal">Processing</h5></td>
                                <td><span id="processing">0</span></td>
                            </tr>
                            <tr>
                                <td><h5 class="font-15 my-1 font-weight-normal">Payment Pending</h5></td>
                                <td><span id="pendingPayment">0</span></td>
                            </tr>
                            <tr>
                                <td><h5 class="font-15 my-1 font-weight-normal">On Hold</h5></td>
                                <td><span id="onHold">0</span></td>
                            </tr>
                            <tr>
                                <td><h5 class="font-15 my-1 font-weight-normal">Canceled</h5></td>
                                <td><span id="canceled">0</span></td>
                            </tr>
                            <tr>
                                <td><h5 class="font-15 my-1 font-weight-normal">Completed</h5></td>
                                <td><span id="completed">0</span></td>
                            </tr>
                            <tr>
                                <td><h5 class="font-15 my-1 font-weight-normal">Pending Invoiced</h5></td>
                                <td><span id="pendingInvoiced">0</span></td>
                            </tr>
                            <tr>
                                <td><h5 class="font-15 my-1 font-weight-normal">Invoiced</h5></td>
                                <td><span id="invoiced">0</span></td>
                            </tr>
                            <tr>
                                <td><h5 class="font-15 my-1 font-weight-normal">Stock Out</h5></td>
                                <td><span id="stockOut">0</span></td>
                            </tr>
                            <tr>
                                <td><h5 class="font-15 my-1 font-weight-normal">Delivered</h5></td>
                                <td><span id="delivered">0</span></td>
                            </tr>
                            <tr>
                                <td><h5 class="font-15 my-1 font-weight-normal">Customer Confirm</h5></td>
                                <td><span id="customerConfirm">0</span></td>
                            </tr>
                            <tr>
                                <td><h5 class="font-15 my-1 font-weight-normal">Paid</h5></td>
                                <td><span id="paid">0</span></td>
                            </tr>
                            <tr>
                                <td><h5 class="font-15 my-1 font-weight-normal">Return</h5></td>
                                <td><span id="return">0</span></td>
                            </tr>
                            <tr>
                                <td><h5 class="font-15 my-1 font-weight-normal">Lost</h5></td>
                                <td><span id="list">0</span></td>
                            </tr>
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>
        <div class="col-xl-4">
            <div class="card">
                <div class="card-body">
                    <h4 class="header-title mb-3">Stock Out Product</h4>
                    <div class="table-responsive">
                        <table id="stocOutTable" class="table table-striped table-sm table-nowrap table-centered mb-0">
                            <thead>
                            <tr>
                                <td>ID</td>
                                <td>Name</td>
                            </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>
        <div class="col-xl-4">
            <div class="card">
                <div class="card-body">
                    <h4 class="header-title mb-3">Recent Update</h4>
                    <div class="table-responsive">
                        <table id="recentUpdate" class="table table-striped table-sm table-nowrap table-centered mb-0">
                            <thead>
                            <tr>
                                <td>Name</td>
                                <td>Message</td>
                            </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>

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

