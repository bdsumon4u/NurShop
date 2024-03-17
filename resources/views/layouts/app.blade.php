<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }}</title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <!-- App favicon -->
    <link rel="shortcut icon" href="{{asset('images/favicon.ico')}}">
    <!-- App css -->
    <link href="{{asset('css/bootstrap.min.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('css/icons.min.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('libs/select2/select2.min.css')}}" rel="stylesheet" type="text/css"/>


    <link href="{{asset('libs/datatables/dataTables.bootstrap4.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('libs/datatables/responsive.bootstrap4.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('libs/toastr/toastr.min.css')}}" rel="stylesheet" type="text/css" >

    <link href="{{asset('libs/sweetalert2/sweetalert2.min.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('css/dataTables.checkboxes.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('libs/dropzone/dropzone.min.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{asset('libs/summernote/summernote-bs4.css')}}" rel="stylesheet" type="text/css"/>
    <link href="{{asset('css/app.min.css')}}" rel="stylesheet" type="text/css" />

    <script>
        var siteURL = "{{ url('/') }}";
    </script>
    @stack('css')
    <link href="{{asset('css/style.css')}}" rel="stylesheet" type="text/css" />
</head>

<body  data-keep-enlarged="true" class="enlarged">
{{--class="enlarged"--}}
<!-- Begin page -->
<div id="wrapper">

    <!-- Topbar Start -->
    @include('layouts.topbar')
    <!-- end Topbar -->

    <!-- Left Sidebar Start -->
    @include('layouts.sidebar')
    <!-- Left Sidebar End -->

    <!-- ============================================================== -->
    <!-- Start Page Content here -->
    <!-- ============================================================== -->

    <div class="content-page">
        <div class="content">
            @if(request()->is('admin/dashboard') || request()->is('user/dashboard') || request()->is('manager/dashboard'))
            <!-- Start Content-->
            <div class="container-fluid">
                <!-- start page title -->
                <div class="row">
                    <div class="col-12">
                        <div class="page-title-box">
                            <div class="page-title-right">
                                <ol class="breadcrumb m-0">
                                    <li class="breadcrumb-item">
                                        <input type="text" id="datepicker" class="form-control" value="<?php echo date('Y-m-d')?>" placeholder="2018-10-03 to 2018-10-10">
                                    </li>
                                </ol>
                            </div>
                            <h4 class="page-title">
                                Welcome {{Auth::user()->name}}
                            </h4>
                        </div>
                    </div>
                </div>

                @else
                    <br>

            @endif


            @yield('content')
        </div> <!-- content -->
    </div>

    <!-- ============================================================== -->
    <!-- End Page content -->
    <!-- ============================================================== -->

</div>
    <script src="{{asset('js/jquery.min.js')}}"></script>
    <script src="{{asset('js/vendor.min.js')}}"></script>
    <!-- App js -->
    <script src="{{asset('js/app.min.js')}}"></script>
    <!-- third party js -->
    <script src="{{asset('libs/datatables/jquery.dataTables.min.js')}}"></script>
    <script src="{{asset('libs/datatables/dataTables.bootstrap4.js')}}"></script>
    <script src="{{asset('libs/datatables/dataTables.responsive.min.js')}}"></script>
    <script src="{{asset('libs/datatables/responsive.bootstrap4.min.js')}}"></script>
    <script src="{{asset('libs/toastr/toastr.min.js')}}"></script>
    <script src="{{ asset('libs/dropzone/dropzone.min.js') }}"></script>
    <script src="{{ asset('libs/summernote/summernote-bs4.min.js') }}"></script>
    <script src="{{asset('libs/select2/select2.min.js')}}"></script>

    <script src="{{asset('libs/sweetalert2/sweetalert2.min.js')}}"></script>
    <script src="{{asset('js/dataTables.checkboxes.min.js')}}"></script>

    <script src="{{asset('js/media.js')}}"></script>
    <script>
    $(document).ready(function () {
$('#media').on('hidden.bs.modal',function(){ 
        $('body').addClass('modal-open');
      
}); 
    });
        @if(Auth::user()->role_id == 1)
        setInterval(function(){
            $.ajax({
                type : "get",
                url: "{{url('admin/dashboard/getNotification')}}",
                success : function(response){
                    var data = JSON.parse(response);
                    if(data["status"] !== 'empty'){
                        console.log(response);
                        toastr.success(data["notificaton"]);
                        console.log(response)
                    }
                }
            });
        },3000);
        @endif
        @if(Auth::user()->role_id == 2)
        setInterval(function(){
            $.ajax({
                type : "get",
                url: "{{url('manager/dashboard/getNotification')}}",
                success : function(response){
                    var data = JSON.parse(response);
                    if(data["status"] !== 'empty'){
                        console.log(response);
                        toastr.success(data["notificaton"]);
                        console.log(response)
                    }
                }
            });
        },3000);
        @endif
    </script>
    @stack('js')
</body>
</html>
