@extends('layouts.app')
@push('css')
    <link href="{{asset('libs/flatpickr/flatpickr.min.css')}}" rel="stylesheet" type="text/css"/>
    <link href="{{asset('libs/select2/select2.min.css')}}" rel="stylesheet" type="text/css"/>
    <link href="https://cdn.datatables.net/buttons/1.6.1/css/buttons.dataTables.min.css" rel="stylesheet" type="text/css"/>
@endpush
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row mb-2">
                        <div class="col-lg-12">
                            <div class="form-row">
                                <div class="form-group col-md-2">
                                    <label for="inputCity" class="col-form-label">Start Date</label>
                                    <input type="text" class="form-control datepicker" id="startDate"  value="<?php echo date('Y-m-d')?>" placeholder="Select Date">
                                </div>
                                <div class="form-group col-md-2">
                                    <label for="inputCity" class="col-form-label">End Date</label>
                                    <input type="text" class="form-control datepicker" id="endDate" value="<?php echo date('Y-m-d')?>" placeholder="Select Date">
                                </div>
                                <div class="form-group col-md-2">
                                    <label for="inputState" class="col-form-label">Payment Type	</label>
                                    <select id="paymentTypeID" class="form-control"></select>
                                </div>
                                <div class="form-group col-md-2">
                                    <label for="inputState" class="col-form-label">Payment Number	</label>
                                    <select id="paymentID" class="form-control"></select>
                                </div>
                                <div class="form-group col-md-2">
                                    <label for="inputState" class="col-form-label">Select User</label>
                                    <select id="userID" class="form-control"></select>
                                </div>
                                <div class="form-group col-md-1">
                                    <label for="inputZip" class="col-form-label" style="opacity: 0">Print</label>
                                    <button class="btn btn-info btn-print"><i class="fas fa-print"></i> Print</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table id="reportTable" class="table table-centered table-nowrap mb-0" style="width: 100%">
                            <thead class="thead-light" >
                            <tr>
                                <th>ID</th>
                                <th>Date</th>
                                <th>Paymetn Type</th>
                                <th>Payment Number</th>
                                <th>TrxID</th>
                                <th>Amount</th>
                                <th>User</th>
                            </tr>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script src="{{asset('libs/flatpickr/flatpickr.min.js')}}"></script>
    <script src="{{asset('libs/select2/select2.min.js')}}"></script>
    <script src="https://cdn.datatables.net/buttons/1.6.1/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.6.1/js/buttons.print.min.js"></script>
    <script !src="">

        $(document).ready(function () {
            var token = $("input[name='_token']").val();
            $(".datepicker").flatpickr();

            var table = $("#reportTable").DataTable({
                type: "get",
                ajax: {
                    url: "{{url('manager/report/getPayment')}}",
                    data: {
                        startDate: function() { return $('#startDate').val() },
                        endDate: function() { return $('#endDate').val() },
                        userID: function() { return $('#userID').val() },
                        paymentTypeID: function() { return $('#paymentTypeID').val() },
                        paymentID: function() { return $('#paymentID').val() }
                    }
                },
                ordering: false,
                pageLength: 50,
                columns: [
                    {data: "order_id"},
                    {data: "date"},
                    {data: "paymentTypeName"},
                    {data: "paymentNumber"},
                    {data: "trid"},
                    {data: "amount"},
                    {data: "name"}
                ],
                search:false,
                dom: '<"row"<"col-sm-6"Bl><"col-sm-6"f>>' +
                    '<"row"<"col-sm-12"<"table-responsive"tr>>>' +
                    '<"row"<"col-sm-5"i><"col-sm-7"p>>',
                buttons: {
                    buttons: [{
                        extend: 'print',
                        text: 'Print',
                        footer: true ,
                        title: function(){
                            var printTitle = 'Invoice';
                            return printTitle;
                        },
                        customize: function (win) {
                            $(win.document.body).find('h1').css('text-align','center');
                            $(win.document.body).find('h1').after('<p style="text-align: center">'+$('#date').val()+'</p>');

                        }
                    }]
                },
                language: {
                    paginate: {
                        previous: "<i class='fas fa-chevron-left'>",
                        next: "<i class='fas fa-chevron-right'>"
                    }
                },
                drawCallback: function () {
                    $(".dataTables_paginate > .pagination").addClass("pagination-sm");
                    $('.dt-buttons').hide();
                },

            });
            $("#courierID").select2({
                placeholder: "Select a Courier",
                ajax: {
                    url:'{{url('manager/city/courier')}}',
                    processResults: function (data) {
                        var data = $.parseJSON(data);
                        return {
                            results: data
                        };
                    }
                }
            }).trigger("change").on("select2:select", function (e) {
                table.ajax.reload();
            });

            $("#userID").select2({
                placeholder: "Select a user",
                ajax: {
                    url:'{{url('manager/report/users')}}',
                    processResults: function (data) {
                        var data = $.parseJSON(data);
                        return {
                            results: data
                        };
                    }
                }
            }).trigger("change").on("select2:select", function (e) {
                table.ajax.reload();
            });

            $("#paymentTypeID").select2({
                placeholder: "Select a Payment Type",
                ajax: {
                    url:'{{url('manager/report/paymentType')}}',
                    processResults: function (data) {
                        var data = $.parseJSON(data);
                        return {
                            results: data
                        };
                    }
                }
            }).trigger("change").on("select2:select", function (e) {
                table.ajax.reload();
            });


            $("#paymentID").select2({
                placeholder: "Select a Payment Number",
                ajax: {
                    url:'{{url('manager/report/paymentID')}}',
                    processResults: function (data) {
                        var data = $.parseJSON(data);
                        return {
                            results: data
                        };
                    }
                }
            }).trigger("change").on("select2:select", function (e) {
                table.ajax.reload();
            });




            $(document).on('click', '.btn-print', function(){
                $(".buttons-print")[0].click();
            });
            $(document).on('change', '#startDate', function(){
                table.ajax.reload();
            });
            $(document).on('change', '#endDate', function(){
                table.ajax.reload();
            });
            $(document).on('change', '#orderStatus', function(){
                table.ajax.reload();
            });

        });
    </script>

@endpush
