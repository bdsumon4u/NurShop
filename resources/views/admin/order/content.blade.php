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
                                <?php
                                $today = date("ym");
                                $rand = strtoupper(substr(uniqid(sha1(time())), 0, 3));
                                $unique = $today . $rand;
                                ?>
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
                                <?php
                                use App\Courier;
                                $couriers = Courier::all();

                                ?>
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
                    <button type="button" id="submit" class="btn btn-block btn-primary"><i class="fa fa-save"></i> Save</button>
                </div>
            </div>
        </div>
    </div>
</section>
