@extends('layouts.app')
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row mb-2">
                        <div class="col-md-6">
                            <h4 class="page-title mt-0 d-inline">Total <span class="total">0</span> Page </h4>
                        </div>
                        <div class="col-md-6">
                            <div class="text-md-right">
                                <button type="button" class="btn btn-blue btn-add btn-xs waves-effect waves-light float-right"><i class="fas fa-plus"></i> Add New Page</button>
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table id="table" class="table table-hover table-bordered"></table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="modal" class="modal fade bs-example-modal-lg" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myLargeModalLabel">Title</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <h5 class="text-uppercase bg-light p-2 mt-0 mb-3">General</h5>

                            <div class="form-group mb-3">
                                <label for="ProductName">Page Name <span class="text-danger">*</span></label>
                                <input type="text" id="pageTitle" class="form-control" >
                            </div>
                            <div class="form-group mb-3">
                                <label for="ProductSlug">Page Slug <span class="text-danger">*</span></label>
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="inputGroupPrepend">{{ url('/page/') }}/</span>
                                    <input type="text" id="pageSlug" class="form-control">
                                </div>
                            </div>

                            <div class="form-group mb-3">
                                <label for="ProductDetails">Page Content <span class="text-danger">*</span></label>
                                <textarea class="form-control" id="pageContent" rows="5" ></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" id="submit" class="btn btn-primary">Save</button>
                    <input type="hidden" name="" id="pageID">
                    @csrf
                </div>
            </div>
        </div>
    </div>
@endsection
@push('js')
    <script>
        $(document).ready(function(){
            $("#pageContent").summernote({height:180,minHeight:null,maxHeight:null,focus:!1});
            var token = $("input[name='_token']").val();
            var table = $("#table").DataTable({
                ajax: "{{ url('admin/page/create') }}",
                pageLength: 50,
                ordering: false,
                columnDefs: [
                    {
                        targets: 0,
                        checkboxes: {
                            selectRow: true
                        }
                    },
                ],
                columns: [
                    {data: "id",title:""},
                    {data: "pageTitle",title:"Page Title"},
                    {data: "pageSlug",title:"Page Slug"},
                    {data: "status",title:"Status"},
                    {data: "action",title:"Action"}
                ],

                drawCallback:function(){
                    $(".dataTables_paginate > .pagination").addClass("pagination-sm")
                },
                footerCallback : function ( row, data, start, end, display ) {
                    var api = this.api();
                    var numRows = api.rows( ).count();
                    $('.total').empty().append(numRows);
                }
            });
            $(document).on("click", ".btn-add", function () {
                var modal = $('#modal');
                modal.find('.modal-title').text('Add New Page');
                modal.find('.modal-footer .btn-primary').text('Save');
                modal.find('.modal-footer .btn-primary').val('Save');
                modal.modal('show');
            });

            $(document).on("click", ".btn-edit", function () {
                var id = $(this).attr('data-id');
                $.ajax({
                    url: "{{url('admin/page/')}}/" + id + "/edit",
                    headers: {
                        'X-CSRF-TOKEN': token
                    },
                    contentType: 'application/json',
                    success: function (data) {
                        $('#pageID').val(id);
                        $('#pageTitle').val(data['pageTitle']);
                        $('#pageSlug').val(data['pageSlug']);
                        $("#pageContent").summernote("code", data['pageContent']);

                        $('#modal .modal-title').empty().text('Edit Page');
                        $('#modal #submit').empty().text('Update');
                        $('#modal #submit').val('Update');
                        $("#modal").modal();
                    }
                });
            });

            // Save and update data
            $(document).on("click", "#submit", function () {

                var type = $(this).val();
                var pageTitle = $('#pageTitle');
                var pageContent = $('#pageContent');
                var pageSlug = $('#pageSlug');
                var pageID = $('#pageID').val();

                if (!pageTitle.val()) {
                    toastr.error('Product Name should not empty !');
                    pageTitle.addClass("parsley-error");
                    return;
                }
                if (!pageContent.val()) {
                    toastr.error('Product Slug should not empty !');
                    pageContent.addClass("parsley-error");
                    return;
                }
                if (!pageSlug.val()) {
                    toastr.error('Product Code should not empty !');
                    pageSlug.addClass("parsley-error");
                    return;
                }

                // Add Data
                if (type === 'Save') {
                    $.ajax({
                        type: "post",
                        url: "{{url('admin/page')}}",
                        data: {
                            'pageTitle': pageTitle.val(),
                            'pageContent': pageContent.val(),
                            'pageSlug': pageSlug.val(),
                            '_token': token
                        },
                        success: function (data) {
                            if (data['status'] === 'success') {
                                toastr.success(data["message"]);
                                $('#modal').modal('hide');
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
                if (!pageID) {
                    swal("Oops...!", "Something wrong ! Please try again.", "error");
                    return;
                }
                // Update data
                if (type === 'Update') {

                    $.ajax({
                        type: "PUT",
                        url: "{{url('admin/page')}}/" + pageID,
                        data: {
                            'pageTitle': pageTitle.val(),
                            'pageContent': pageContent.val(),
                            'pageSlug': pageSlug.val(),
                            '_token': token
                        },
                        success: function (data) {
                            if (data['status'] === 'success') {
                                toastr.success(data["message"]);
                                $('.modal').modal('hide');
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
                            url: "{{url('admin/page/')}}/" + id,
                            data: {
                                '_token': token
                            },
                            contentType: "application/json",
                            success: function (response) {
                                var data = JSON.parse(response);
                                if (data['status'] === 'success') {
                                    $(this).parent('tr').remove();
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
                    url: "{{url('admin/page/status')}}",
                    data: {
                        'status': status,
                        'id': id,
                        '_token': token
                    },
                    success: function (data) {
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

            $(document).on("click", ".btn-danger", function (e) {
                e.preventDefault();
                var rows_selected = table.column(0).checkboxes.selected();
                var ids = [];
                $.each(rows_selected, function (index, rowId) {
                    ids[index] = rowId;
                });
                if(ids.length > 0){
                    Swal.fire({
                        title:"Are you sure?",
                        text:"You won't be able to revert this!",
                        type:"warning",
                        showCancelButton:!0,
                        confirmButtonColor:"#3085d6"
                        ,cancelButtonColor:"#d33"
                        ,confirmButtonText:"Yes, delete it!"
                    }).then(function(t){
                        e.preventDefault();
                        if(t.value){
                            $.ajax({
                                type: "get",
                                url: "{{url('admin/page/delete')}}",
                                data: {
                                    'ids': ids
                                },
                                contentType: "application/json",
                                success: function (response) {
                                    if (response['status'] === 'success') {
                                        toastr.success(response["message"]);
                                        table.ajax.reload();
                                    } else {
                                        if (response['status'] === 'failed') {
                                            toastr.error(response["message"]);
                                        } else {
                                            toastr.error('Something wrong ! Please try again.');
                                        }
                                    }
                                }
                            });
                        }
                    })
                }

            });



        });
    </script>
@endpush
