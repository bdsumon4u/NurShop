@extends('layouts.app')
@push('css')
    <link href="{{asset('libs/flatpickr/flatpickr.min.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('libs/select2/select2.min.css')}}" rel="stylesheet" type="text/css" />

@endpush
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row mb-2">
                        <div class="col-md-6">
                            <h4 class="page-title mt-0 d-inline">Total <span class="total">0</span> Purchase </h4>
                        </div>
                        <div class="col-md-6">
                            <div class="text-md-right">
                                <button type="button" class="btn btn-blue btn-add btn-xs waves-effect waves-light float-right"><i class="mdi mdi-plus-circle mr-1"></i> Add New Purchase</button>
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-centered table-borderless table-hover mb-0" id="storeTable">
                            <thead class="thead-light">
                            <tr>
                                <th>ID</th>
                                <th>Date</th>
                                <th>Invoice ID</th>
                                <th>Product Name</th>
                                <th>Supplier Name</th>
                                <th>Quantity</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody></tbody>
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
                            <label for="date">Date</label>
                            <input type="text" name="date" class="form-control datepicker" id="date">
                        </div>
                        <div class="form-group">
                            <label for="invoiceID">Invoice ID</label>
                            <input type="text" name="invoiceID" class="form-control" id="invoiceID">
                        </div>
                        <div class="form-group">
                            <label for="productID">Product Name</label>
                            <select name="productID" id="productID" class="form-control"  style="width: 100%" >

                            </select>
                        </div>
                        <div class="form-group">
                            <label for="supplierID">Supplier Name</label>
                            <select name="productID" id="supplierID" class="form-control" style="width: 100%" ></select>
                        </div>
                        <div class="form-group">
                            <label for="quantity">Quantity</label>
                            <input type="text" name="quantity" class="form-control" id="quantity">
                            <input type="hidden" id="oldQuantity">
                        </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" id="submit" class="btn btn-primary">Save</button>
                    <input type="hidden" id="purchaseID">
                    @csrf
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')

    <script src="{{asset('libs/flatpickr/flatpickr.min.js')}}"></script>
    <script src="{{asset('libs/select2/select2.min.js')}}"></script>

    <script !src="">

        $(document).ready(function(){
            $(".datepicker").flatpickr();
            var table = $("#storeTable").DataTable({
                ajax: "{{url('admin/purchase/show')}}",
                "pageLength": 50,
                ordering: false,
                columns: [
                    {data: "id"},
                    {
                        data: null,
                        render: function (data) {
                            var items = data.created_at.split(' ');
                            return items[0];
                        }
                    },
                    {data: "invoiceID"},
                    {data: "productName"},
                    { data: "supplierName" },
                    { data: "quantity" },
                    {
                        data: null,
                        render: function (data) {
                            return '<button type="button" value="' + data.id + '" class="btn btn-edit btn-xs btn-info"> <i class="mdi mdi-square-edit-outline"></i></button>'
                                + '<button type="button" value="' + data.id + '"class="btn btn-danger btn-delete btn-xs ml-2"  ><i class="mdi mdi-delete"></i></button>';
                        }
                    }
                ],
                language:{
                    paginate:{
                        previous:"<i class='mdi mdi-chevron-left'>",
                        next:"<i class='mdi mdi-chevron-right'>"
                    },
                    "emptyTable": "No purchase available in table"
                },
                drawCallback:function(){
                    $(".dataTables_paginate > .pagination").addClass("pagination-sm")
                },
                "initComplete": function(settings, json) {

                },
                "footerCallback": function ( row, data, start, end, display ) {
                    var api = this.api();
                    var numRows = api.rows( ).count();
                    $('.total').empty().append(numRows);
                }
                });

            var token = $("input[name='_token']").val();

            $("#supplierID").select2({
                placeholder: "Select a Supplier",
                ajax: {
                    url:'{{url('admin/purchase/supplier')}}',
                    processResults: function (data) {
                        var data = $.parseJSON(data);
                        return {
                            results: data
                        };
                    }
                }
            });

            $("#productID").select2({
                placeholder: "Select a Product",
                ajax: {
                    url:'{{url('admin/purchase/product')}}',
                    processResults: function (data) {
                        var data = $.parseJSON(data);
                        return {
                            results: data
                        };
                    }
                }
            });


            $(document).on("click", ".btn-add", function () {
                $('.modal-title').text('Add New Purchase');
                $('.modal-footer .btn-primary').text('Save');
                $('.modal-footer .btn-primary').val('Save');
                $('.modal').modal('toggle');
            });

            $(document).on("click", ".btn-edit", function () {
                var id = $(this).val();
                $.ajax({
                    url: "{{url('admin/purchase')}}/" + id + "/edit",
                    headers: {
                        'X-CSRF-TOKEN': token
                    },
                    contentType: 'application/json',
                    success: function (response) {
                        var data = JSON.parse(response);
                        $('#purchaseID').val(id);
                        $('#date').val(data['date']);
                        $('#invoiceID').val(data['invoiceID']);
                        $("#productID").empty().append('<option value="' + data['product_id'] + '"  >' + data['productName'] + '</option>');
                        $("#supplierID").empty().append('<option value="' + data['supplier_id'] + '"  >' + data['supplierName'] + '</option>');
                        $('#quantity').val(data['quantity']);
                        $('#oldQuantity').val(data['quantity']);
                        $('.modal .modal-title').empty().text('Edit Purchase');
                        $('.modal #submit').empty().text('Update');
                        $('.modal #submit').val('Update');
                        $(".modal").modal();
                    }
                });
            });

            // Save and update data
            $(document).on("click", "#submit", function () {

                var type = $(this).val();
                var date = $('#date');
                var invoiceID = $('#invoiceID');
                var productID = $('#productID');
                var supplierID = $('#supplierID');
                var quantity = $('#quantity');
                var oldQuantity = $('#oldQuantity');
                var purchaseID = $('#purchaseID').val();
                if (!date.val()) {
                    toastr.error('Date should not empty !');
                    return;
                }
                if (!invoiceID.val()) {
                    toastr.error('Invoice should not empty !');
                    return;
                }
                if (!productID.val()) {
                    toastr.error('Product Name should not empty !');
                    return;
                }
                if (!supplierID.val()) {
                    toastr.error('Supplier Name should not empty !');
                    return;
                }
                if (!quantity.val()) {
                    toastr.error('Quantity should not empty !');
                    return;
                }
                // Add Data
                if (type === 'Save') {
                    $.ajax({
                        type: "post",
                        url: "{{url('admin/purchase')}}",
                        data: {
                            'date': date.val(),
                            'invoiceID': invoiceID.val(),
                            'productID': productID.val(),
                            'supplierID': supplierID.val(),
                            'quantity': quantity.val(),
                            '_token': token
                        },
                        success: function (response) {
                            var data = JSON.parse(response);
                            if (data['status'] === 'success') {
                                toastr.success(data["message"]);
                                $('.modal').modal('toggle');
                                table.ajax.reload();
                            } else {
                                if (data['status'] === 'failed') {
                                    toastr.error(data["message"]);
                                } else {
                                    toastr.error('Something wrong ! Please try again.');
                                }

                            }

                        }
                    });
                    return;
                }
                // ID check
                if (!purchaseID) {
                    toastr.error('Something wrong ! Please try again.');
                    return;
                }
                // Update data
                if (type === 'Update') {
                    $.ajax({
                        type: "PUT",
                        url: "{{url('admin/purchase')}}/" + purchaseID,
                        data: {
                            'date': date.val(),
                            'invoiceID': invoiceID.val(),
                            'productID': productID.val(),
                            'supplierID': supplierID.val(),
                            'quantity': quantity.val(),
                            'oldQuantity': oldQuantity.val(),
                            '_token': token
                        },
                        success: function (response) {
                            var data = JSON.parse(response);
                            if (data['status'] === 'success') {
                                toastr.success(data["message"]);
                                $('.modal').modal('toggle');
                                table.ajax.reload();
                            } else {
                                if (data['status'] === 'failed') {
                                    toastr.error(data["message"]);
                                } else {
                                    toastr.error('Something wrong ! Please try again.');
                                }
                            }
                        }
                    });
                }
            });

            $(document).on("click", ".btn-delete", function () {
                var id = $(this).val();

                Swal.fire({
                    title:"Are you sure?",
                    text:"You won't be able to revert this!",
                    type:"warning",
                    showCancelButton:!0,
                    confirmButtonColor:"#3085d6"
                    ,cancelButtonColor:"#d33"
                    ,confirmButtonText:"Yes, delete it!"
                }).then(  function(t){
                    if(t.value){
                        $.ajax({
                            headers: {
                                'X-CSRF-TOKEN': token
                            },
                            type: "DELETE",
                            url: "{{url('admin/purchase/')}}/" + id,
                            data: {
                                '_token': token
                            },
                            contentType: "application/json",
                            success: function (response) {
                                var data = JSON.parse(response);
                                if (data['status'] === 'success') {
                                    toastr.success(data["message"]);
                                    table.ajax.reload();
                                } else {
                                    if (data['status'] === 'failed') {
                                        toastr.error(data["message"]);
                                    } else {
                                        toastr.error('Something wrong ! Please try again.');
                                    }
                                }
                            }
                        });
                    }
                })

            });

            $(document).on('click', '.btn-status', function () {
                var status = $(this).attr('data-status');
                var id = $(this).val();
                $.ajax({
                    type: "post",
                    url: "{{url('admin/purchase/status')}}",
                    data: {
                        'status': status,
                        'id': id,
                        '_token': token
                    },
                    success: function (response) {
                        var data = JSON.parse(response);
                        if (data['status'] === 'success') {
                            toastr.success(data["message"]);
                            table.ajax.reload();
                        } else {
                            if (data['status'] === 'failed') {
                                toastr.error(data["message"]);
                            } else {
                                toastr.error('Something wrong ! Please try again.');
                            }
                        }
                    }
                });
            });


        });
    </script>
@endpush
