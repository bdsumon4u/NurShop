@extends('layouts.app')
@push('css')

@endpush
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row mb-2">
                        <div class="col-md-6">
                            <h4 class="page-title mt-0 d-inline">Total <span class="total">0</span> Product </h4>
                        </div>
                        <div class="col-md-6">
                            <div class="text-md-right">
                                <button type="button" class="btn btn-sync btn-info btn-xs waves-effect waves-light ml-2 float-right"><i class="fas fa-spinner fa-spin mr-1"></i> Product Sync</button>
                                <button type="button" class="btn btn-blue btn-add btn-xs waves-effect waves-light float-right"><i class="mdi mdi-plus-circle mr-1"></i> Add New Product</button>
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-centered table-borderless table-hover mb-0" id="productTable" style="width: 100%;">
                            <thead class="thead-light">
                                <tr>
                                    <th>ID</th>
                                    <th>Product Code</th>
                                    <th>Product Image</th>
                                    <th>Product Name</th>
                                    <th>Product Price</th>
                                    <th>Status</th>
                                    <th class="hidden-sm">Action</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div> <!-- end card-body-->
            </div> <!-- end card-->
        </div> <!-- end col -->
    </div>
    <div id="modal" class="modal fade bs-example-modal-lg" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myLargeModalLabel">Title</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <div class="modal-body">
                    <form action=""  >
                        <div class="form-group">
                            <label for="productName">Product Name</label>
                            <input type="text" name="productName" class="form-control" id="productName">
                        </div>
                        <div class="form-group">
                            <label for="productCode">Product Code</label>
                            <input type="text" name="productCode" class="form-control" id="productCode">
                        </div>
                        <div class="form-group">
                            <label for="productPrice">Product Price</label>
                            <input type="number" name="productPrice" class="form-control" id="productPrice">
                        </div>
                        <div class="form-group">
                            <label for="productImage">Product Image</label>
                            <input type="file" accept="image/*" style=" border: none; box-shadow: none; padding: 0; " name="productImage" class="form-control" id="productImage">
                            <input type="hidden" name="" id="productUrl">
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" id="submit" class="btn btn-primary">Save</button>
                    <input type="hidden" name="" id="productID">

                    @csrf
                </div>
            </div>
        </div>
    </div>

@endsection

@push('js')
    <!-- third party js -->

    <script>
        $(document).ready(function(){
            var table = $("#productTable").DataTable({
                ajax: "{{url('admin/product/show')}}",
                ordering: false,
                processing: true,
                serverSide: true,
                pageLength: 50,
                rowId: "id",
                columns: [
                    {data: "id"},
                    {data: "productCode"},
                    {data: "productImage"},
                    {data: "productName"},
                    {data: "productPrice"},
                    {data: "status"},
                    {data: "action"}

                ],
                language:{
                    paginate:{
                        previous:"<i class='mdi mdi-chevron-left'>",
                        next:"<i class='mdi mdi-chevron-right'>"}},
                drawCallback:function(){
                    $(".dataTables_paginate > .pagination").addClass("pagination-sm")
                }
            });

            var token = $("input[name='_token']").val();


            $(document).on("click", ".btn-add", function () {
                var modal = $('#modal');
                modal.find('.modal-title').text('Add New Product');
                modal.find('.modal-footer .btn-primary').text('Save');
                modal.find('.modal-footer .btn-primary').val('Save');
                modal.modal('show');
            });

            $(document).on("click", ".btn-edit", function () {
                var id = $(this).attr('data-id');
                $.ajax({
                    url: "{{url('admin/product/')}}/" + id + "/edit",
                    headers: {
                        'X-CSRF-TOKEN': token
                    },
                    contentType: 'application/json',
                    success: function (response) {
                        var data = JSON.parse(response);
                        $('#productID').val(id);
                        $('#productName').val(data['productName']);
                        $('#productCode').val(data['productCode']);
                        $('#productPrice').val(data['productPrice']);
                        $('#productUrl').val(data['productImage']);
                        $('#modal .modal-title').empty().text('Edit Product');
                        $('#modal #submit').empty().text('Update');
                        $('#modal #submit').val('Update');
                        $("#modal").modal();
                    }
                });
            });

            // Save and update data
            $(document).on("click", "#submit", function () {

                var type = $(this).val();
                var productName = $('#productName');
                var productCode = $('#productCode');
                var productPrice = $('#productPrice');
                var productUrl = $('#productUrl');
                var productID = $('#productID').val();
                if (!productName.val()) {
                    toastr.error('Product Name should not empty !');
                     return;
                }
                if (!productCode.val()) {
                    toastr.error('Product Code should not empty !');
                    return;
                }
                if (!productPrice.val()) {
                    toastr.error('Product Price should not empty !');
                    return;
                }
                if (!productUrl.val()) {
                    toastr.error('Product Image should not empty !');
                    return;
                }
                // Add Data
                if (type == 'Save') {
                    $.ajax({
                        type: "post",
                        url: "{{url('admin/product')}}",
                        data: {
                            'productName': productName.val(),
                            'productCode': productCode.val(),
                            'productPrice': productPrice.val(),
                            'productUrl': productUrl.val(),
                            '_token': token
                        },
                        success: function (response) {
                            var data = JSON.parse(response);
                            if (data['status'] == 'success') {
                                toastr.success(data["message"]);
                                $('#modal').modal('toggle');
                                table.ajax.reload();
                            } else {
                                if (data['status'] == 'failed') {
                                    toastr.error(data["message"]);
                                } else {
                                    toastr.error('Something wrong ! Please try again.');
                                }

                            }

                        }
                    });
                }
                // ID check
                if (!productID) {
                    swal("Oops...!", "Something wrong ! Please try again.", "error");
                    return;
                }
                // Update data
                if (type === 'Update') {

                    $.ajax({
                        type: "PUT",
                        url: "{{url('admin/product')}}/" + productID,
                        data: {
                            'productName': productName.val(),
                            'productCode': productCode.val(),
                            'productPrice': productPrice.val(),
                            'productUrl': productUrl.val(),
                            '_token': token
                        },
                        success: function (response) {
                            var data = JSON.parse(response);
                            if (data['status'] === 'success') {
                                toastr.success(data["message"]);
                                $('#modal').modal('toggle');
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

            $(document).on("change", "#productImage", function (e) {

                e.preventDefault();
                var fd = new FormData();
                var files = $('#productImage')[0].files[0];
                fd.append('productImage',files);

                $.ajax({
                    url: "{{url('admin/product/image')}}",
                    type: 'post',
                    data: fd,
                    contentType: false,
                    processData: false,
                    headers: {
                        'X-CSRF-TOKEN': token
                    },
                    success: function (response) {
                        var data = JSON.parse(response);

                        if (data["status"] === "success") {
                            $('#productUrl').val(data["url"]);
                            toastr.success(data["message"]);
                        } else {
                            // toastr.error(data["message"])
                        }

                    }
                });
            });

            $(document).on("click", ".btn-delete", function () {
                var id = $(this).attr('data-id');;
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
                            url: "{{url('admin/product/')}}/" + id,
                            data: {
                                '_token': token
                            },
                            contentType: "application/json",
                            success: function (response) {
                                var data = JSON.parse(response);
                                if (data['status'] == 'success') {
                                    $(this).parent('tr').remove();
                                    toastr.success(data["message"]);
                                    table.ajax.reload();
                                } else {
                                    if (data['status'] == 'failed') {
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
                    url: "{{url('admin/product/status')}}",
                    data: {
                        'status': status,
                        'id': id,
                        '_token': token
                    },
                    success: function (response) {
                        var data = JSON.parse(response);
                        if (data['status'] == 'success') {
                            toastr.success(data["message"]);
                            table.ajax.reload();
                        } else {
                            if (data['status'] == 'failed') {
                                toastr.error(data["message"]);
                            } else {
                                toastr.error('Something wrong ! Please try again.');
                            }
                        }
                    }
                });
            });

            $(document).on('click', '.btn-sync', function (e) {


                Swal.fire({
                    title: 'Auto sync start!',
                    html: 'It will close after all Products sync.',
                    onBeforeOpen: () => {
                        Swal.showLoading()
                    }
                });
                jQuery.ajax({
                    type: "get",
                    url: "{{url('admin/product/productSync')}}",
                    contentType: 'application/json',
                    success: function(response) {
                        var data = JSON.parse(response);
                        if (data['status'] == 'success') {
                            Swal.fire({
                                title: data['message'],
                                html: data['products'] +
                                    ' Orders Sync.'

                            }).then(function() {
                                table.ajax.reload();
                            });
                        } else {
                            Swal.fire({
                                title: data['message'],
                                html: data['products'] +
                                    ' Products Sync.'

                            });
                        }
                    }
                });



            });


        });
    </script>
@endpush
