@extends('layouts.app')
@push('css')
    <link href="{{asset('libs/flatpickr/flatpickr.min.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('libs/select2/select2.min.css')}}" rel="stylesheet" type="text/css" />
@endpush
@section('content')
    <section class="content">
        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <strong>Customer Info</strong>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="storeID">Store Name</label>
                                    <select id="storeID"  class="form-control">
                                        <option value="">Select Store</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="invoiceID">Invoice Number</label>
                                    <input type="text" readonly class="form-control" style="cursor: not-allowed;" id="invoiceID" value="{{ $unique }}">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="customerName">Customer Name</label>
                                    <input type="text" class="form-control" id="customerName">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="customerPhone">Customer Phone</label>
                                    <input type="text" class="form-control" id="customerPhone">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label for="customerAddress">Customer Address</label>
                                    <textarea name="" class="form-control" placeholder="Customer Address" id="customerAddress" rows="2"></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label for="courierID">Courier Name</label>
                                    <select id="courierID"  class="form-control">
                                        <option value="">Courier Name</option>
                                    </select>
                                    <script>
                                        var couriers = <?php echo json_encode($couriers) ?>;
                                    </script>
                                </div>
                            </div>
                            <div class="col-lg-12 hasCity">
                                <div class="form-group">
                                    <label for="cityID">City Name</label>
                                    <select id="cityID"  class="form-control">
                                        <option value="">City Name</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-12 hasZone">
                                <div class="form-group">
                                    <label for="zoneID">Zone Name</label>
                                    <select id="zoneID"  class="form-control">
                                        <option value="">Zone Name</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label for="orderDate">Order Date</label>
                                    <input type="text" class="form-control datepicker" value="{{ date('Y-m-d') }}" id="orderDate">
                                </div>
                            </div>
                            {{--                        <div class="col-lg-4">--}}
                            {{--                            <div class="form-group">--}}
                            {{--                                <label for="deliveryDate">Delivery Date</label>--}}
                            {{--                                <input type="text" class="form-control datepicker" id="deliveryDate">--}}
                            {{--                            </div>--}}
                            {{--                        </div>--}}
                            {{--                        <div class="col-lg-4">--}}
                            {{--                            <div class="form-group">--}}
                            {{--                                <label for="completeDate">Complete Name</label>--}}
                            {{--                                <input type="text" class="form-control datepicker" id="completeDate">--}}
                            {{--                            </div>--}}
                            {{--                        </div>--}}
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <strong>Product Info</strong>
                    </div>
                    <div class="card-body">
                        <table id="productTable" style="width: 100% !important;" class="table table-bordered table-striped">
                            <thead>
                            <tr>
                                <th>Code</th>
                                <th>Product Name</th>
                                <th>Quantity</th>
                                <th>Price</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody>
                            </tbody>
                            <tfoot>
                            <tr>
                                <td colspan="5">
                                    <select id="productID" style="width: 100%;">
                                        <option value="">Select Product</option>
                                    </select>
                                </td>
                            </tr>
                            </tfoot>

                        </table>
                        <br>
                        <br>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Payment</label>
                                    <select id="paymentTypeID" class="form-control select2">
                                        <option value="">Select payment Type</option>
                                    </select>
                                </div>
                                <div class="form-group paymentID">
                                    <select id="paymentID" class="form-control" style="width: 100%;">
                                        <option value="">Select Number</option>
                                    </select>
                                </div>
                                <div class="form-group paymentAgentNumber">
                                    <input type="text" class="form-control" id="paymentAgentNumber" placeholder="Enter Bkash Agent Number">
                                </div>
                                <div class="form-group hide">
                                    <label>Memo Number</label>
                                    <input type="text" class="form-control" id="memoNumber" placeholder="Enter Memo Number">
                                </div>

                            </div>
                            <div class="col-md-6">
                                <div class="form-group row">
                                    <label for="fname" class="col-sm-4 text-right control-label col-form-label">Sub Total</label>
                                    <div class="col-sm-8">
                                        <span class="form-control" id="subtotal" style="cursor: not-allowed;">100</span>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="fname" class="col-sm-4 text-right control-label col-form-label">Delivery</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control" value="100" id="deliveryCharge">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="fname" class="col-sm-4 text-right control-label col-form-label">Discount</label>
                                    <div class="col-sm-8">
                                        <input type="text" value="0" class="form-control" id="discountCharge">
                                    </div>
                                </div>

                                <div class="form-group row paymentAmount">
                                    <label for="fname" class="col-sm-4 text-right control-label col-form-label">Payment</label>
                                    <div class="col-sm-8">
                                        <input type="text" value="0" class="form-control" id="paymentAmount">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="fname" class="col-sm-4 text-right control-label col-form-label">Total</label>
                                    <div class="col-sm-8">
                                        <span class="form-control" id="total" style="cursor: not-allowed;"   >100</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="button" id="submit" class="btn btn-primary btn-block" data-style="expand-left">Save</button>
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection

@push('js')
    <script src="{{asset('libs/flatpickr/flatpickr.min.js')}}"></script>
    <script src="{{asset('libs/select2/select2.min.js')}}"></script>
    <script>
        $(document).ready(function () {

            $(document).on("click", "#submit", function () {

                var invoiceID = $("#invoiceID");
                var customerName = $("#customerName");
                var customerPhone = $("#customerPhone");
                var customerAddress = $("#customerAddress");
                var storeID = $("#storeID");
                var total = +$("#total").text();
                var deliveryCharge = +$("#deliveryCharge").val();
                var discountCharge = +$("#discountCharge").val();
                var paymentTypeID = $("#paymentTypeID").val();
                var paymentID = $("#paymentID").val();
                var paymentAmount = +$("#paymentAmount").val();
                var paymentAgentNumber = $("#paymentAgentNumber").val();
                var orderDate = $("#orderDate");
                var courierID = $("#courierID");
                var cityID = +$("#cityID").val();
                var zoneID = +$("#zoneID").val();
                var product = [];
                var productCount = 0 ;
                $("#productTable tbody tr").each(function (index, value) {
                    var currentRow = $(this);
                    var obj = {};
                    obj.productID = currentRow.find(".productID").val();
                    obj.productCode = currentRow.find(".productCode").text();
                    obj.productName = currentRow.find(".productName").text();
                    obj.productQuantity = currentRow.find(".productQuantity").val();
                    obj.productPrice = currentRow.find(".productPrice").text();
                    product.push(obj);
                    productCount++;
                });

                if(storeID.val() == ''){
                    toastr.error('Store Should Not Be Empty');
                    storeID.closest('.form-group').find('.select2-selection').css('border','1px solid red');
                    return;
                }
                storeID.closest('.form-group').find('.select2-selection').css('border','1px solid #ced4da');

                if(invoiceID.val() == ''){
                    toastr.error('Invoice ID Should Not Be Empty');
                    invoiceID.css('border','1px solid red');
                    return;
                }
                invoiceID.css('border','1px solid #ced4da');

                if(customerName.val() == ''){
                    toastr.error('Customer Name Should Not Be Empty');
                    customerName.css('border','1px solid red');
                    return;
                }
                customerName.css('border','1px solid #ced4da');

                if(customerPhone.val() == ''){
                    toastr.error('Customer Phone Should Not Be Empty');
                    customerPhone.css('border','1px solid red');
                    return;
                }
                customerPhone.css('border','1px solid #ced4da');

                if(customerAddress.val() == ''){
                    toastr.error('Customer Address Should Not Be Empty');
                    customerAddress.css('border','1px solid red');
                    return;
                }
                customerAddress.css('border','1px solid #ced4da');

                if(orderDate.val() == ''){
                    toastr.error('Order Date Should Not Be Empty');
                    orderDate.css('border','1px solid red');
                    return;
                }
                orderDate.css('border','1px solid #ced4da');

                if(courierID.val() == ''){
                    toastr.error('Courier Should Not Be Empty');
                    courierID.closest('.form-group').find('.select2-selection').css('border','1px solid red');
                    return;
                }
                courierID.css('border','1px solid #ced4da');

                if(productCount == 0){
                    toastr.error('Product Should Not Be Empty');
                    return;
                }

                var data = {};
                data["invoiceID"] = invoiceID.val();
                data["storeID"] = storeID.val();
                data["customerName"] = customerName.val();
                data["customerPhone"] = customerPhone.val();
                data["customerAddress"] = customerAddress.val();
                data["total"] = total;
                data["deliveryCharge"] = deliveryCharge;
                data["discountCharge"] = discountCharge;
                data["paymentTypeID"] = paymentTypeID;
                data["paymentID"] = paymentID;
                data["paymentAmount"] = paymentAmount;
                data["paymentAgentNumber"] = paymentAgentNumber;
                data["orderDate"] = orderDate.val();
                data["courierID"] = +courierID.val();
                data["cityID"] = cityID;
                data["zoneID"] = zoneID;
                data["userID"] = $('#user_id').val();
                data["products"] = product;
                $.ajax({
                    type: "POST",
                    url: '{{url('admin/order')}}',
                    data: {
                        'data': data,
                        '_token': token
                    },
                    success: function (response) {
                        var data = JSON.parse(response);
                        if (data["status"] === "success") {
                            toastr.success(data["message"]);
                            window.location.href = "{{ url('admin/order') }}";

                        } else {
                            toastr.error(data["message"])
                        }
                    }
                });



            });





            $(".datepicker").flatpickr();


            $("#productID").select2({
                placeholder: "Select a Product",
                templateResult: function (state) {
                    if (!state.id) {
                        return state.text;
                    }
                    var $state = $(
                        '<span><img width="60px" src="' +
                        state.image +
                        '" class="img-flag" /> ' +
                        state.text +
                        "</span>"
                    );
                    return $state;
                },
                ajax: {
                    url:'{{url('admin/order/product')}}',
                    processResults: function (data) {
                        var data = $.parseJSON(data);
                        return {
                            results: data.data
                        };
                    }
                }
            }).trigger("change").on("select2:select", function (e) {
                $("#productTable tbody").append(
                    "<tr>" +
                    '<td  style="display: none"><input type="text" class="productID" style="width:80px;" value="' + e.params.data.id + '"></td>' +
                    '<td><span class="productCode">' + e.params.data.productCode + '</span></td>' +
                    '<td><span class="productName">' + e.params.data.text + '</span></td>' +
                    '<td><input type="number" class="productQuantity form-control" style="width:80px;" value="1"></td>' +
                    '<td><span class="productPrice">' + e.params.data.productPrice + '</span></td>' +
                    '<td><button class="btn btn-sm btn-danger delete-btn"><i class="fa fa-trash"></i></button></td>\n' +
                    "</tr>"
                );
                calculation();
            });

            $("#storeID").select2({
                placeholder: "Select a Store",
                ajax: {
                    url:'{{url('admin/order/stores')}}',
                    processResults: function (data) {
                        var data = $.parseJSON(data);
                        return {
                            results: data
                        };
                    }
                }
            });
            $("#courierID").select2({
                placeholder: "Select a Courier",
                ajax: {
                    url: '{{url('admin/order/courier')}}',
                    processResults: function (data) {
                        var data = $.parseJSON(data);
                        return {
                            results: data
                        };
                    }
                }
            }).trigger("change").on("select2:select", function (e) {
                $("#zoneID").empty();
                for (var i = 0; i < couriers.length; i++) {
                    if (couriers[i]['courierName'] == e.params.data.text) {
                        if (couriers[i]['hasCity'] == 'on') {
                            jQuery(".hasCity").show();
                        } else {
                            jQuery(".hasCity").hide();
                        }
                        if (couriers[i]["hasZone"] == 'on') {
                            jQuery(".hasZone").show();
                        } else {
                            jQuery(".hasZone").hide();
                            $("#zoneID").empty();
                        }
                    }

                    if (e.params.data.text == 'Pathao') {
                        $("#cityID").empty().append('<option value="8">Dhaka</option>');
                    } else {
                        $("#cityID").empty();
                    }
                }

            });

            $("#cityID").select2({
                placeholder: "Select a City",
                ajax: {
                    data: function (params) {
                        var query = {
                            q: params.term,
                            courierID: $("#courierID").val()
                        };
                        return query;
                    },
                    url: '{{url('admin/order/city')}}',
                    processResults: function (data) {
                        var data = $.parseJSON(data);
                        return {
                            results: data
                        };
                    }
                }
            });

            $("#zoneID").select2({
                placeholder: "Select a Zone",
                ajax: {
                    data: function (params) {
                        var query = {
                            q: params.term,
                            courierID: $("#courierID").val(),
                            cityID: $("#cityID").val()
                        };
                        return query;
                    },
                    url: '{{url('admin/order/zone')}}',
                    processResults: function (data) {
                        var data = $.parseJSON(data);
                        return {
                            results: data
                        };
                    }
                }
            });

            $(".paymentID").hide();
            $(".paymentAgentNumber").hide();
            $(".paymentAmount").hide();

            $("#paymentTypeID").select2({
                placeholder: "Select a payment Type",
                allowClear:true,
                ajax: {
                    data: function (params) {
                        return {
                            q: params.term
                        };
                    },
                    url:'{{url('admin/order/paymenttype')}}',
                    processResults: function (data) {
                        var data = $.parseJSON(data);
                        return {
                            results: data
                        };
                    }
                }
            }).trigger("change").on("select2:select", function (e) {
                if (e.params.data.text == "") {
                    $(".paymentID").hide();
                    $(".paymentAgentNumber").hide();
                    $(".paymentAmount").hide();
                } else {
                    $(".paymentID").show();
                    $(".paymentAgentNumber").show();
                    $(".paymentAmount").show();
                }
            }).on("select2:unselect", function (e) {
                $(".paymentID").hide();
                $(".paymentAgentNumber").hide();
                $(".paymentAmount").hide();
                calculation();
            });

            $("#paymentID").select2({
                placeholder: "Select a payment Number",
                allowClear:true,
                ajax: {
                    data: function (params) {
                        return {
                            q: params.term,
                            paymentTypeID: $("#paymentTypeID").val(),
                        };
                    },
                    url:'{{url('admin/order/paymentnumber')}}',
                    processResults: function (data) {
                        var data = $.parseJSON(data);
                        return {
                            results: data
                        };
                    }
                }
            });


            $(document).on("change", ".productQuantity", function () {
                calculation();
            });
            $(document).on("input", "#paymentAmount", function () {
                calculation();
            });
            $(document).on("input", "#deliveryCharge", function () {
                calculation();
            });
            $(document).on("input", "#discountCharge", function () {
                calculation();
            });

            calculation();
            function calculation() {
                var subtotal = 0;
                var deliveryCharge = +$("#deliveryCharge").val();
                var discountCharge = +$("#discountCharge").val();
                var paymentAmount = +$("#paymentAmount").val();
                $("#productTable tbody tr").each(function (index) {
                    subtotal = subtotal + +$(this) .find(".productPrice") .text() *  +$(this).find(".productQuantity").val();
                });
                $("#subtotal").text(subtotal);
                $("#total").text(subtotal + deliveryCharge - paymentAmount - discountCharge);
            }
            var token = $( "input[name='_token']" ).val();

            $(document).on("click", ".delete-btn", function () {
                $(this).closest("tr").remove();
                calculation();
            });







        });
    </script>
@endpush

