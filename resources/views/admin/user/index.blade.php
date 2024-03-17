@extends('layouts.app')
@push('css')
    <link href="{{asset('libs/select2/select2.min.css')}}" rel="stylesheet" type="text/css" />

@endpush
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row mb-2">
                        <div class="col-md-6">
                            <h4 class="page-title mt-0 d-inline">Total <span class="total">0</span> Users </h4>
                        </div>
                        <div class="col-md-6">
                            <div class="text-md-right">
                                <a href="#" class="btn btn-blue btn-add btn-xs waves-effect waves-light float-right"><i class="fas fa-plus mr-1"></i> Add New User</a>
                            </div>
                        </div><!-- end col-->
                    </div>
                    <div class="table-responsive">
                        <table class="table table-centered table-borderless table-hover mb-0" id="stockTable" width="100%">
                            <thead class="thead-light">
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Phone</th>
                                <th>Email</th>
                                <th>Type</th>
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
                        <label for="name">Name</label>
                        <input type="text" name="name" class="form-control" id="name">
                    </div>
                    <div class="form-group">
                        <label for="phone">Phone</label>
                        <input type="text" name="phone" class="form-control" id="phone">
                    </div>
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" name="email" class="form-control" id="email">
                    </div>
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="text" name="password" class="form-control" id="password">
                    </div>
                    <div class="form-group">
                        <label for="roleID">Role</label>
                        <select name="roleID" class="form-control" id="roleID"></select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-submit btn-primary">Save</button>
                    <input type="hidden" id="userID">
                    @csrf
                </div>
            </div>
        </div>
    </div>

@endsection

@push('js')
    <script src="{{asset('libs/select2/select2.min.js')}}"></script>


    <script>

        $(document).ready(function(){
            var table = $("#stockTable").DataTable({
                ajax: "{{url('admin/user/users')}}",
                "pageLength": 50,
                ordering: false,
                columns: [
                    {data: "id"},
                    {
                        "data": null,
                        render: function (data) {
                             return '<a href="{{url("admin/user/login/")}}/' + data.id + '">' + data.name + '</button>';
                        }
                    }, 
                    {data: "phone"},
                    {data: "email"},
                    {data: "roleName"},
                    {
                        "data": null,
                        render: function (data) {
                            if($('#user_id').val() == data.id ){
                                return data.status;
                            }else{
                                if (data.status == 'Active') {
                                    return '<button type="button" class="btn btn-success btn-xs btn-status" data-status="Inactive" name="status" value="' + data.id + '">Active</button>';
                                } else {
                                    return '<button type="button" class="btn btn-warning btn-xs btn-status" data-status="Active" name="status" value="' + data.id + '" >Inactive</button>';
                                }
                            }

                        }
                    },
                    {
                        data: null,
                        render: function (data) {
                            if($('#user_id').val() == data.id ){
                                return "<a href='javascript:void(0);' data-id='" + data.id + "' class='action-icon btn-edit mr-2'> <i class='fas fa-edit'></i></a>";
                            }else{
                                return "<a href='javascript:void(0);' data-id='" + data.id + "' class='action-icon btn-edit mr-2'> <i class='fas fa-edit'></i></a>" +
                                    "<a href='javascript:void(0);' data-id='" + data.id + "' class='action-icon btn-delete'> <i class='fas fa-trash-alt'></i></a>";
                            }

                        }
                    }
                ],
                language:{
                    paginate:{
                        previous:"<i class='mdi mdi-chevron-left'>",
                        next:"<i class='mdi mdi-chevron-right'>"
                    },
                    emptyTable: "No Stock Data available in table"
                },
                drawCallback:function(){
                    $(".dataTables_paginate > .pagination").addClass("pagination-sm")
                },
                footerCallback : function () {
                    var api = this.api();
                    var numRows = api.rows( ).count();
                    $('.total').empty().append(numRows);
                }
            });

            var token = $("input[name='_token']").val();

            $(document).on("click", ".btn-add", function () {
                $('.modal-title').text('Add New User');
                $('.modal-footer .btn-submit').text('Save');
                $('.modal-footer .btn-submit').val('Save');
                $('.modal').modal('toggle');
            });

            $(document).on("click", ".btn-edit", function (e) {
                e.preventDefault();
                var id = $(this).attr('data-id');
                $.ajax({
                    url: "{{url('admin/user/')}}/" + id + "/edit",
                    headers: {
                        'X-CSRF-TOKEN': token
                    },
                    contentType: 'application/json',
                    success: function (response) {
                        var data = JSON.parse(response);
                        $('#userID').val(id);
                        $('#name').val(data['name']);
                        $('#phone').val(data['phone']);
                        $('#email').val(data['email']);
                        $("#email"). prop("disabled", true);
                        $("#roleID").empty().append('<option value="' + data['role_id'] + '"  >' + data['roleName'] + '</option>');
                        $('.modal .modal-title').empty().text('Edit User');
                        $(".modal .btn-submit").empty().text('Update');
                        $('.modal .btn-submit').val('Update');
                        $(".modal").modal();
                    }
                });
            });

            // Save and update data
            $(document).on("click", ".btn-submit", function () {

                var type = $(this).val();
                var name = $('#name');
                var phone = $('#phone');
                var email = $('#email');
                var password = $('#password');
                var roleID = $('#roleID');
                var userID = $('#userID').val();
                var error = 0;

                if (!name.val()) {
                    toastr.error('User Name should not empty !');
                    name.css('border','1px solid red');
                    error++;
                }
                if (!phone.val()) {
                    toastr.error('Phone should not empty !');
                    phone.css('border','1px solid red');

                    error++;
                }
                if (!email.val()) {
                    if(isValidEmail(email.val())){
                        toastr.error('Enter a Valid Email Address ');
                        email.css('border','1px solid red');
                    }else{
                        toastr.error('Email should not empty !');
                        email.css('border','1px solid red');
                    }

                    error++;
                }
                function isValidEmail(emailText) {
                    var pattern = new RegExp(/^((([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+(\.([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+)*)|((\x22)((((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(([\x01-\x08\x0b\x0c\x0e-\x1f\x7f]|\x21|[\x23-\x5b]|[\x5d-\x7e]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(\\([\x01-\x09\x0b\x0c\x0d-\x7f]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))))*(((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(\x22)))@((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?$/i);
                    return pattern.test(emailText);
                }

                if (!roleID.val()) {
                    toastr.error('User Role should not empty !');
                    error++;
                }
                if(type == 'Save' && !password.val()){
                    toastr.error('User Password should not empty !');
                    password.css('border','1px solid red');
                    error++;
                }

                if(error > 0 ){
                    return;
                }

                // Add Data
                if (type === 'Save') {
                    $.ajax({
                        type: "post",
                        url: "{{url('admin/user')}}",
                        data: {
                            'name': name.val(),
                            'phone': phone.val(),
                            'email': email.val(),
                            'password': password.val(),
                            'roleID': roleID.val(),
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
                if (!userID) {
                    toastr.error('Something wrong ! Please try again.');
                    return;
                }
                // Update data
                if (type === 'Update') {
                    $.ajax({
                        type: "PUT",
                        url: "{{url('admin/user')}}/" + userID,
                        data: {
                            'name': name.val(),
                            'phone': phone.val(),
                            'email': email.val(),
                            'password': password.val(),
                            'roleID': roleID.val(),
                            '_token': token
                        },
                        success: function (response) {
                            var data = JSON.parse(response);
                            if (data['status'] === 'success') {
                                $("#email"). prop("disabled", false);
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
                            url: "{{url('admin/user')}}/" + id,
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
                    type: "get",
                    url: "{{url('admin/user/status')}}",
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

            $("#roleID").select2({
                placeholder: "Select a Role",
                ajax: {
                    url:'{{url('admin/user/role')}}',
                    processResults: function (data) {
                        var data = $.parseJSON(data);
                        return {
                            results: data
                        };
                    }
                }
            });
        });
    </script>
@endpush
