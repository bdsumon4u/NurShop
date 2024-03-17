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
                            <h4 class="page-title mt-0 d-inline">Total <span class="total">0</span> City </h4>
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
                                    <th>Courier Name</th>
                                    <th>City Name</th>
                                    <th>Status</th>
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
    <script src="{{asset('libs/select2/select2.min.js')}}"></script>

    <script !src="">

        $(document).ready(function(){
            var table = $("#storeTable").DataTable({
                ajax: "{{url('admin/city/show')}}",
                "pageLength": 50,
                ordering: false,
                columns: [
                    {data: "id"},
                    {data: "courierName"},
                    {data: "cityName"},
                    {
                        "data": null,
                        render: function (data) {
                            if (data.status == 'Active') {
                                return '<button type="button" class="btn btn-success btn-xs btn-status" data-status="Inactive" name="status" value="' + data.id + '">Active</button>';
                            } else {
                                return '<button type="button" class="btn btn-warning btn-xs btn-status" data-status="Active" name="status" value="' + data.id + '" >Inactive</button>';
                            }
                        }
                    },
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
                    "emptyTable": "No Courier available in table"
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

            $("#courierID").select2({
                placeholder: "Select a Courier",
                ajax: {
                    url:'{{url('admin/city/courier')}}',
                    processResults: function (data) {
                        var data = $.parseJSON(data);
                        return {
                            results: data
                        };
                    }
                }
            });

            var token = $("input[name='_token']").val();

            $(document).on("click", ".btn-add", function () {
                $('.modal-title').text('Add New City');
                $('.modal-footer .btn-primary').text('Save');
                $('.modal-footer .btn-primary').val('Save');
                $('.modal').modal('toggle');
            });

            $(document).on("click", ".btn-edit", function () {
                var id = $(this).val();
                $.ajax({
                    url: "{{url('admin/city')}}/" + id + "/edit",
                    headers: {
                        'X-CSRF-TOKEN': token
                    },
                    contentType: 'application/json',
                    success: function (response) {
                        var data = JSON.parse(response);
                        $('#cityID').val(id);
                        $('#cityName').val(data['cityName']);
                        $("#courierID").empty().append('<option value="' + data['courier_id'] + '"  >' + data['courierName'] + '</option>');
                        $('.modal .modal-title').empty().text('Edit City');
                        $('.modal #submit').empty().text('Update');
                        $('.modal #submit').val('Update');
                        $(".modal").modal();
                    }
                });
            });

            // Save and update data
            $(document).on("click", "#submit", function () {

                var type = $(this).val();
                var courierID = $('#courierID');
                var cityName = $('#cityName');
                var cityID = $('#cityID').val();

                if (!courierID.val()) {
                    toastr.error('Courier Name should not empty !');
                    return;
                }
                if (!cityName.val()) {
                    toastr.error('City Name should not empty !');
                    return;
                }
                // Add Data
                if (type === 'Save') {
                    $.ajax({
                        type: "post",
                        url: "{{url('admin/city')}}",
                        data: {
                            'courierID': courierID.val(),
                            'cityName': cityName.val(),
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
                if (!cityID) {
                    toastr.error('Something wrong ! Please try again.');
                    return;
                }
                // Update data
                if (type === 'Update') {
                    $.ajax({
                        type: "PUT",
                        url: "{{url('admin/city')}}/" + cityID,
                        data: {
                            'courierID': courierID.val(),
                            'cityName': cityName.val(),
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
                            url: "{{url('admin/city/')}}/" + id,
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
                    url: "{{url('admin/city/status')}}",
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
