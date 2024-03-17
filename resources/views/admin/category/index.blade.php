@extends('layouts.app')
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row mb-2">
                        <div class="col-md-6">
                            <h4 class="page-title mt-0 d-inline">Total <span class="total">0</span> Category </h4>
                        </div>
                        <div class="col-md-6">
                            <div class="text-md-right">
                                <button type="button" class="btn btn-blue btn-add btn-xs waves-effect waves-light float-right"><i class="fas fa-plus"></i> Add New Category</button>
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

    <div id="modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="fullWidthModalLabel" aria-hidden="true" >
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="fullWidthModalLabel">Store</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <div class="modal-body p-3">

                    <div class="form-group mb-3">
                        <label for="categoryName">category Name <span class="text-danger">*</span></label>
                        <input type="text" id="categoryName" class="form-control" >
                    </div>
                    <div class="form-group mb-3">
                        <label for="categorySlug">Category Slug <span class="text-danger">*</span></label>
                        <div class="input-group-prepend">
                            <span class="input-group-text" id="inputGroupPrepend">{{ url('/') }}/</span>
                            <input type="text" id="categorySlug" class="form-control">
                        </div>
                    </div>
                    <div class="form-group mb-3">
                        <label for="ProductDetails">Feature Image <span class="text-danger">*</span></label>
                        <button type="button" class="image-picker btn btn-success d-block mb-2" data-input-name="files[additional_images][]">
                            <i class="mdi mdi-cloud-upload"></i> Browse
                        </button>
                        <div class="single-image image-holder-wrapper clearfix">
                            <div class="image-holder placeholder">
                                <i class="mdi mdi-folder-image"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-sm waves-effect" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-info btn-sm waves-effect waves-light" id="submit">Save changes</button>
                    <input type="hidden" id="id">
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script>
        $(document).ready(function(){

            var categoryImage = '';
            $("#dropzone").dropzone({
                addRemoveLinks: true,
                maxFiles: 1, //change limit as per your requirements
                dictMaxFilesExceeded: "Maximum upload limit reached",
                acceptedFiles: "image/*",
                dictInvalidFileType: "upload only JPG/PNG",
                init: function () {
                    $(this.element).addClass("dropzone");
                    this.on("success", function (file, response) {
                        if(response.status === 'success'){
                            categoryImage =  response.url;
                            toastr.success('Image Uploade Successfull');
                        }else{
                            toastr.error('Image Uploade Failed !');
                        }
                    });
                    this.on('addedfile', function(file) {
                        if (this.files.length > 1) {
                            this.removeFile(this.files[0]);
                        }
                    });
                }
            });

            var token = $("input[name='_token']").val();

            var table = $("#table").DataTable({
                ajax: "{{url('admin/category/create')}}",
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
                    {data: "id"},
                    {data: "image",title: "Image"},
                    {data: "categoryName",title: "Category Name"},
                    {data: "status",title: "Status"},
                    {data: "action",title: "Action"}
                ],
                drawCallback:function(){
                    $(".dataTables_paginate > .pagination").addClass("pagination-sm")
                },
                initComplete: function(settings, json) {
                    $('#table_length').append('<button type="button" class="btn btn-danger waves-effect btn-sm waves-light mx-2">Danger</button>');
                },
                footerCallback : function ( row, data, start, end, display ) {
                    var api = this.api();
                    var numRows = api.rows( ).count();
                    $('.total').empty().append(numRows);
                }
            });

            $(document).on("click", ".btn-add", function () {
                var modal = $('#modal');
                modal.find('.modal-title').text('Add New Category');
                modal.find('.modal-footer .btn-info').text('Save');
                modal.find('.modal-footer .btn-info').val('Save');
                modal.modal('show');
            });

            $(document).on("click", ".btn-edit", function () {
                var id = $(this).attr('data-id');
                $.ajax({
                    url: "{{url('admin/category')}}/" + id + "/edit",
                    headers: {
                        'X-CSRF-TOKEN': token
                    },
                    contentType: 'application/json',
                    success: function (data) {
                        $('#id').val(id);
                        $('#categoryName').val(data['categoryName']);
                        $('#categorySlug').val(data['categorySlug']);
                        var html = ('<div class="image-holder image-"><img src="{{url("/media/")}}/' +data['categoryImage']+ '"><button type="button" class="btn btn-danger btn-xs waves-effect waves-light remove-image float-right"><i class="mdi mdi-close"></i></button><input type="hidden" name="imageID" value="' + data['categoryImage'] + '"></div>');
                        $('.single-image').find(".image-holder.placeholder").remove();
                        $('.single-image').empty().append(html);

                        var modal = $('#modal');
                        modal.find('.modal-title').text('Edit Category');
                        modal.find('.modal-footer .btn-info').text('Update');
                        modal.find('.modal-footer .btn-info').val('Update');
                        modal.modal('show');
                    }
                });
            });

            $(document).on("click", "#submit", function (e) {
                var type = $(this).val();
                var categoryName = $('#categoryName');
                var categorySlug = $('#categorySlug');
                var imageID = $('input[name="imageID"]').val();
                var id = $('#id').val();
                var count = 0;
                if (!categoryName.val()) {
                    categoryName.addClass("parsley-error");
                    toastr.error('Category Name should not empty !');
                    count++;
                }
                if (!categorySlug.val()) {
                    categorySlug.addClass("parsley-error");
                    toastr.error('Category Slug should not empty !');
                    count++;
                }
                if(count>0){
                    return;
                }
                // Add Data
                if (type === 'Save') {
                    $.ajax({
                        type: "post",
                        url: "{{url('admin/category')}}",
                        data: {
                            'categoryName': categoryName.val(),
                            'categorySlug': categorySlug.val(),
                            'categoryImage': imageID,
                            '_token': token
                        },
                        success: function (response) {
                            if (response['status'] === 'success') {
                                toastr.success(response["message"]);
                                $('#modal').modal('toggle');
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
                    return;
                }
                // ID check
                if (!id) {
                    toastr.error('Something wrong ! Please try again.');
                    return;
                }
                // Update data
                if (type === 'Update') {
                    $.ajax({
                        type: "PUT",
                        url: "{{url('admin/category')}}/" + id,
                        data: {
                            'categoryName': categoryName.val(),
                            'categorySlug': categorySlug.val(),
                            'categoryImage': imageID,
                            '_token': token
                        },
                        success: function (data) {
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

            $(document).on('click', '.btn-status', function () {
                var status = $(this).attr('data-status');
                var id = $(this).val();
                $.ajax({
                    type: "post",
                    url: "{{url('admin/category/status')}}",
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

            $(document).on("click", ".btn-delete", function () {
                var id = $(this).attr('data-id');

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
                            url: "{{url('admin/category/')}}/" + id,
                            data: {
                                '_token': token
                            },
                            contentType: "application/json",
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
                    }
                })

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
                                url: "{{url('admin/category/delete')}}",
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

