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
                            <h4 class="page-title mt-0 d-inline">Total <span class="total">0</span> Product Stock </h4>
                        </div>
                        <div class="col-md-6">
                            <div class="text-md-right">
                                <a href="{{route('admin.purchase.index')}}" class="btn btn-blue btn-add btn-xs waves-effect waves-light float-right"><i class="mdi mdi-plus-circle mr-1"></i> Add New Purchase</a>

                             </div>
                        </div><!-- end col-->
                    </div>
                    <div class="table-responsive">
                        <table class="table table-centered table-borderless table-hover mb-0" id="stockTable" width="100%">
                            <thead class="thead-light">
                            <tr>
                                <th>ID</th>
                                <th>Product Name</th>
                                <th>Purchase</th>
                                <th>Stock</th>
                            </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div> <!-- end card-body-->
            </div> <!-- end card-->
        </div> <!-- end col -->
    </div>
 @endsection

@push('js')


    <script !src="">

         $(document).ready(function(){
             var table = $("#stockTable").DataTable({
                 ajax: "{{url('admin/stock/show')}}",
                 "pageLength": 50,
                 ordering: false,
                 columns: [
                     {data: "id"},
                     { data: "productName" },
                     {data: "purchase"},
                     {data: "stock"}
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
                 "initComplete": function(settings, json) {
                     var api = this.api();
                     var numRows = api.rows( ).count();
                     $('.total').empty().append(numRows);
                  }
             });
         });
    </script>
@endpush
