@extends('layouts.app')
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
                                <button type="button" class="btn btn-blue btn-add btn-xs waves-effect waves-light float-right"><i class="fas fa-plus"></i> Add New Product</button>
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
        <div class="modal-dialog modal-full">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myLargeModalLabel">Title</h4>
                    <div class="pull-right">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="button" id="submit" class="btn btn-primary">Save</button>
                        <input type="hidden" name="" id="productID">
                    </div>
                    @csrf
                 </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-6">
                            <h5 class="text-uppercase bg-light p-2 mt-0 mb-3">General</h5>

                            <div class="form-group mb-3">
                                <label for="productName">Product Name <span class="text-danger">*</span></label>
                                <input type="text" id="productName" class="form-control" >
                            </div>
                            <div class="form-group mb-3">
                                <label for="productSlug">Product Slug <span class="text-danger">*</span></label>
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="inputGroupPrepend">{{ url('/product/') }}/</span>
                                    <input type="text" id="productSlug" class="form-control">
                                </div>
                            </div>

                            <div class="form-group mb-3">
                                <label for="productCode">Product Code <span class="text-danger">*</span></label>
                                <input type="text" id="productCode" class="form-control">
                            </div>

                            <div class="form-group mb-3">
                                <label for="productCategory">Categories <span class="text-danger">*</span></label>
                                <select class="form-control" id="productCategory" data-toggle="select2" multiple="multiple" data-placeholder="Choose ...">
                                    <option>Select</option>
                                </select>
                            </div>
                             <div class="form-group mb-3">
                                <label for="relatedProducts">Related Products</label>
                                <select class="form-control" id="relatedProducts" data-toggle="select2" multiple="multiple" data-placeholder="Choose ...">
                                    <option>Select</option>
                                </select>
                            </div>


                            <div class="form-group mb-3">
                                <label for="productDetails">Product Description <span class="text-danger">*</span></label>
                                <textarea class="form-control" id="productDetails" rows="5" ></textarea>
                            </div>
                        </div>

                        <div class="col-lg-6">


                            <h5 class="text-uppercase mt-0 mb-3 bg-light p-2">Product Images</h5>

                            <div class="row">
                                <div class="col-6">
                                    <div class="form-group mb-3">
                                        <label for="productRegularPrice">Regular Price <span class="text-danger">*</span></label>
                                        <input type="number" id="productRegularPrice" class="form-control">
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group mb-3">
                                        <label for="productSalePrice">Sale Price <span class="text-danger">*</span></label>
                                        <input type="number" id="productSalePrice" class="form-control">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-3">
                                    <div class="form-group mb-3">
                                        <label for="productDetails">Feature Image <span class="text-danger">*</span></label>
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
                                <div class="col-9">
                                    <div class="form-group mb-3">
                                        <label for="productDetails">Gallery Image <span class="text-danger">*</span></label>
                                        <button type="button" class="image-picker btn btn-success d-block" data-input-name="files[additional_images][]" data-multiple="">
                                            <i class="mdi mdi-cloud-upload"></i> Browse
                                        </button>
                                        <div class="image-list image-holder-wrapper clearfix">
                                            <div class="image-holder placeholder cursor-auto">
                                                <i class="mdi mdi-folder-image"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card-box">
                                <h5 class="text-uppercase mt-0 mb-3 bg-light p-2">Meta Data</h5>
                                <div class="form-group mb-3">
                                    <label for="MetaTitle">Meta title</label>
                                    <input type="text" class="form-control" id="MetaTitle" placeholder="Enter title">
                                </div>

                                <div class="form-group mb-3">
                                    <label for="MetaKeywords">Meta Keywords</label>
                                    <input type="text" class="form-control" id="MetaKeywords" placeholder="Enter keywords">
                                </div>

                                <div class="form-group mb-0">
                                    <label for="MetaDescription">Meta Description </label>
                                    <textarea class="form-control" rows="5" id="MetaDescription" placeholder="Please enter description"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    
                </div>
            </div>
        </div>
    </div>
@endsection
@push('js')
    <script>
        $(document).ready(function(){
            $("#productDetails").summernote({height:180,minHeight:null,maxHeight:null,focus:!1});
            var token = $("input[name='_token']").val();
            var table = $("#table").DataTable({
                ajax: "{{ url('admin/product/show') }}",
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
                    {data: "productImage",title:"Image"},
                    {data: "productName",title:"Product Name"},
                    {data: "categories",title:"Categories Name"},
                    {data: "productCode",title:"SKU"},
                    {data: "productRegularPrice",title:"Price"},
                    {data: "productSalePrice",title:"Sale Price"},
                    {data: "status",title:"Status"},
                    {data: "action",title:"Action"}
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
                
                modal.find('.modal-title').text('Add New Product');
                modal.find('.modal-header .btn-primary').text('Save');
                modal.find('.modal-header .btn-primary').val('Save');
                modal.modal('show');
            });

            $("#productCategory").select2({
                placeholder: "Select a Category",
                multiple:true,
                ajax: {
                    type: "post",
                    url:'{{url('admin/product/category')}}',
                    data: {
                        '_token': token
                    },
                    processResults: function (data) {
                        return {
                            results: $.parseJSON(data)
                        };
                    }
                }
            });

            $("#relatedProducts").select2({
                placeholder: "Select a Related Produtcs",
                multiple:true,
                ajax: {
                    type: "post",
                    url:'{{url('admin/product/relatedProducts')}}',
                    data: {
                        '_token': token
                    },
                    processResults: function (data) {
                        return {
                            results: $.parseJSON(data)
                        };
                    }
                }
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
                        $('#productSlug').val(data['productSlug']);
                        $('#productCode').val(data['productCode']);
                        $("#productDetails").summernote("code", data['productDetails']);
                        $("#productCategory").empty();
                        $.each( data['categories'], function( key, value ) {
                            $("#productCategory").append('<option value="'+value.id+'" selected>'+value.categoryName+'</option>');
                        });
                        $("#productCategory").select2({
                            placeholder: "Select a Category",
                            multiple:true,
                            ajax: {
                                type: "post",
                                url:'{{url('admin/product/category')}}',
                                data: {
                                    '_token': token
                                },
                                processResults: function (data) {
                                    return {
                                        results: $.parseJSON(data)
                                    };
                                }
                            }
                        });
                        
                        $.each( data['related'], function( key, value ) {
                            $("#relatedProducts").append('<option value="'+value.id+'" selected>'+value.productName+'</option>');
                        });

                        $("#relatedProducts").select2({
                            placeholder: "Select a Related Produtcs",
                            multiple:true,
                            ajax: {
                                type: "post",
                                url:'{{url('admin/product/relatedProducts')}}',
                                data: {
                                    '_token': token
                                },
                                processResults: function (data) {
                                    return {
                                        results: $.parseJSON(data)
                                    };
                                }
                            }
                        });

                        var html = ('<div class="image-holder image-"><img src="{{ asset('/public/product/thumbnail/') }}/' +data['productImage']+ '"><button type="button" class="btn btn-danger btn-xs waves-effect waves-light remove-image float-right"><i class="mdi mdi-close"></i></button><input type="hidden" name="imageID" value="' + data['productImage'] + '"></div>');
                        $('.single-image').find(".image-holder.placeholder").remove();
                        $('.single-image').empty().append(html);

                        jQuery.each( data['gallery'], function( key, value ) {
                            // alert( key + ": " + value.media_id );
                                var html = ('<div class="image-holder image-"><img src="{{ asset('/public/product/thumbnail/') }}/' +value.url+ '"><button type="button" data-multiple="" class="btn btn-danger btn-xs waves-effect waves-light remove-image float-right"><i class="mdi mdi-close"></i></button><input type="hidden" name="imageID[]" value="' + value.media_id + '"></div>');
                                $('.image-list').find(".image-holder.placeholder").remove();
                                $('.image-list').append(html);
                        });


                        $('#productRegularPrice').val(data['productRegularPrice']);
                        $('#productSalePrice').val(data['productSalePrice']);
                        $('#MetaTitle').val(data['MetaTitle']);
                        $('#MetaKeywords').val(data['MetaKeywords']);
                        $('#MetaDescription').val(data['MetaDescription']);
                        $('#modal .modal-title').empty().text('Edit product');
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
                var productSlug = $('#productSlug');
                var productCode = $('#productCode');
                var productCategory = $('#productCategory');
                var relatedProducts = $('#relatedProducts');
                var productDetails = $('#productDetails');
                var productRegularPrice = $('#productRegularPrice');
                var productSalePrice = $('#productSalePrice');
                var MetaTitle = $('#MetaTitle');
                var MetaKeywords = $('#MetaKeywords');
                var MetaDescription = $('#MetaDescription');
                var imageID = $('input[name="imageID"]');
                var gallery = [];
                $('input[name="imageID[]"]').each(function(){
                    gallery.push($(this).val());
                });
                var productID = $('#productID').val();

                if (!productName.val()) {
                    toastr.error('product Name should not empty !');
                    productName.addClass("parsley-error");
                    return;
                }
                if (!productSlug.val()) {
                    toastr.error('product Slug should not empty !');
                    productSlug.addClass("parsley-error");
                    return;
                }
                if (!productCode.val()) {
                    toastr.error('product Code should not empty !');
                    productCode.addClass("parsley-error");
                    return;
                }
                if (!productCategory.val()) {
                    toastr.error('product Category should not empty !');
                    productCategory.addClass("parsley-error");
                    return;
                }
                if (!productDetails.val()) {
                    toastr.error('product Details should not empty !');
                    productDetails.addClass("parsley-error");
                    return;
                }
                if (!productRegularPrice.val()) {
                    toastr.error('product Regular Price should not empty !');
                    productRegularPrice.addClass("parsley-error");
                    return;
                }
                if (!imageID.val()) {
                    toastr.error('product Image should not empty !');
                    return;
                }
                if (+productRegularPrice.val() < +productSalePrice.val()) {
                    toastr.error('Sale price should not greater than Regular price message  !');
                    productRegularPrice.addClass("parsley-error");
                    productSalePrice.addClass("parsley-error");
                    return;
                }
                var data = {
                            'productName': productName.val(),
                            'productSlug': productSlug.val(),
                            'productCode': productCode.val(),
                            'productCategory': productCategory.val(),
                            'productDetails': productDetails.val(),
                            'productRegularPrice': productRegularPrice.val(),
                            'productSalePrice': productSalePrice.val(),
                            'MetaTitle': MetaTitle.val(),
                            'MetaKeywords': MetaKeywords.val(),
                            'MetaDescription': MetaDescription.val(),
                            'imageID': imageID.val(),
                            'gallery': gallery,
                            '_token': token,
                            'type': type
                        };
                        
                        console.log(data);

                // Add Data
                if (type === 'Save') {
                    $.ajax({
                        type: "post",
                        url: "{{url('admin/product')}}",
                        data: {
                            'productName': productName.val(),
                            'productSlug': productSlug.val(),
                            'productCode': productCode.val(),
                            'productCategory': productCategory.val(),
                            'relatedProducts': relatedProducts.val(),
                            'productDetails': productDetails.val(),
                            'productRegularPrice': productRegularPrice.val(),
                            'productSalePrice': productSalePrice.val(),
                            'MetaTitle': MetaTitle.val(),
                            'MetaKeywords': MetaKeywords.val(),
                            'MetaDescription': MetaDescription.val(),
                            'imageID': imageID.val(),
                            'gallery': gallery,
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
                    return;
                }
                // ID check
                if (!productID) {
                    swal("Oops...!", "Product ID missing ! Please try again.", "error");
                    return;
                }
                // Update data
                if (type === 'Update') {

                    $.ajax({
                        type: "PUT",
                        url: "{{url('admin/product')}}/" + productID,
                        data: {
                            'productName': productName.val(),
                            'productSlug': productSlug.val(),
                            'productCode': productCode.val(),
                            'productCategory': productCategory.val(),
                            'relatedProducts': relatedProducts.val(),
                            'productDetails': productDetails.val(),
                            'productRegularPrice': productRegularPrice.val(),
                            'productSalePrice': productSalePrice.val(),
                            'MetaTitle': MetaTitle.val(),
                            'MetaKeywords': MetaKeywords.val(),
                            'MetaDescription': MetaDescription.val(),
                            'imageID': imageID.val(),
                            'gallery': gallery,
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
                            url: "{{url('admin/product/')}}/" + id,
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
                                url: "{{url('admin/product/delete')}}",
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
