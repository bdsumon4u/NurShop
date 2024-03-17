@extends('layouts.app')
    @section('content')
        <style>
            .navbar-custom , .enlarged .left-side-menu{
                display: none;
                opacity: 0;
            }
            .enlarged .content-page{
                margin: 0px !important;
            }
        </style>
    <div class="content-page w-100 m-0">
        <div class="content">
            <div class="row mt-3">
                <div class="col-12">
                    <div class="row mb-2">
                        <div class="col-12">
                            <form method="post" class="dropzone" enctype="multipart/form-data">
                                @csrf
                                <div class="fallback">
                                    <input name="file" type="file" multiple />
                                </div>
                                <div class="dz-message needsclick">
                                    <i class="h1 text-muted dripicons-cloud-upload"></i>
                                    <h3>Drop files here or click to upload.</h3>
                                </div>
                            </form>
                        </div>

                    </div>
                    <div class="card">
                        <div class="card-body">
                            <table id="table" class="table table-hover table-bordered" style="width: 100%"></table>
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
                                <label for="attr">Name <span class="text-danger">*</span></label>
                                <input type="text" id="attr" class="form-control" >
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endsection
@push('js')
<script>

    Dropzone.autoDiscover = false;

    $(document).ready(function(){

        var token = $("input[name='_token']").val();



        var table = $("#table").DataTable({
            ajax: "{{ url('admin/media/iframeget?multiple=') }}{{ $multiple }}",
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
                {data: "id",},
                {data: "image",title: "Image"},
                {data: "name",title: "Name"},
                {data: "created_at",title: "Create"},
                {data: "action",title: "Action"},
            ],
            initComplete: function(settings, json) {
                $('#table_length').append('<button type="button" class="btn btn-danger waves-effect btn-sm waves-light mx-2">Danger</button>');
            },
            drawCallback:function(){
                $(".dataTables_paginate > .pagination").addClass("pagination-sm");
            }
        });

        $("form.dropzone").dropzone({
            addRemoveLinks: true,
            acceptedFiles: "image/*",
            url: "{{ url('admin/media') }}",
            dictInvalidFileType: "upload only JPG/PNG",
            init: function () {
                this.on("success", function (file, response) {
                    if(response.status == 'success'){
                        toastr.success('Image Uploade Successfull');
                    }else{
                        toastr.error('Image Uploade Failed !');
                    }
                    table.ajax.reload();
                    this.removeFile(file);
                });
            }
        });
        $(document).on("click", ".btn-single-delete", function () {
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
                        url: "{{url('admin/media/')}}/" + id,
                        data: {
                            '_token': token
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

        });
        $(document).on("click", ".btn-danger", function (e) {
            e.preventDefault();
            var rows_selected = table.column(0).checkboxes.selected();
            var ids = [];
            $.each(rows_selected, function (index, rowId) {
                ids[index] = rowId;
            });
            if(ids.length > 0) {
                Swal.fire({
                    title: "Are you sure?",
                    text: "You won't be able to revert this!",
                    type: "warning",
                    showCancelButton: !0,
                    confirmButtonColor: "#3085d6"
                    , cancelButtonColor: "#d33"
                    , confirmButtonText: "Yes, delete it!"
                }).then(function (t) {
                    e.preventDefault();
                    if (t.value) {
                        $.ajax({
                            type: "get",
                            url: "{{url('admin/media/delete')}}",
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

        $(document).on("click", ".btn-edit", function () {
            var id = $(this).attr('data-id');
            $.ajax({
                url: "{{url('admin/media')}}/" + id + "/edit",
                headers: {
                    'X-CSRF-TOKEN': token
                },
                contentType: 'application/json',
                success: function (data) {
                    $('#id').val(id);
                    $('#attr').val(data['name']);
                    $('.modal .modal-title').empty().text('Edit Image');
                    $('.modal #submit').empty().text('Update');
                    $('.modal #submit').val('Update');
                    $(".modal").modal();
                }
            });
        });

        $(document).on("click", "#submit", function () {
            var attr = $('#attr');
            var id = $('#id').val();
            if (!attr.val()) {
                toastr.error('Name should not empty !');
                return;
            }
            if (!id) {
                toastr.error('Something wrong ! Please try again.');
                return;
            }
            $.ajax({
                type: "PUT",
                url: "{{url('admin/media')}}/" + id,
                data: {
                    'name': attr.val(),
                    '_token': token
                },
                success: function (data) {
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

        });

    });
</script>
@endpush
