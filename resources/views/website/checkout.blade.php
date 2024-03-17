@extends('website.layout')
@section('content')
    @if(Cart::count() > 0)
        <section class="shop checkout section">
            <div class="container">
                <div class="row">
                    <div class="col-lg-6 col-12">
                        <div class="checkout-form card">
                            <p class="text-center" style="font-size: 16px;">অর্ডারটি কনফার্ম করতে আপনার নাম, ঠিকানা,
                                মোবাইল নাম্বার, লিখে <span class="text-danger">অর্ডার কনফার্ম করুন</span> বাটনে
                                ক্লিক করুন
                            </p>
                            <!-- Form -->
                                <div class="col-lg-12 col-md-12 col-12">
                                    <div class="form-group">
                                        <label>আপনার নাম<span>*</span></label>
                                        <input type="text" name="name" class="form-control"  id="customerName" placeholder="আপনার নাম লিখুন" required="required">
                                    </div>
                                </div>
                                <div class="col-lg-12 col-md-12 col-12">
                                    <div class="form-group">
                                        <label>আপনার ঠিকানা<span>*</span></label>
                                        <input type="text" name="address" class="form-control"   id="customerAddress" required="required"  placeholder="আপনার ঠিকানা লিখুন">
                                    </div>
                                </div>
                                <div class="col-lg-12 col-md-12 col-12">
                                    <div class="form-group">
                                        <label>আপনার মোবাইল<span>*</span></label>
                                        <input type="number" pattern="[0-9]*" id="customerPhone" class="form-control"  placeholder="আপনার মোবাইল লিখুন" />
                                    </div>
                                </div>
                                <div class="col-lg-12 col-md-12 col-12">
                                    <div class="form-group">
                                        <label>Shipping Method<span>*</span></label>
                                        <select class="form-control" id="selectCourier" >
                                             <option value="{{ Settings::get('inside_dhaka') }}">ঢাকার ভিতর </option>
                                                    <option value="{{ Settings::get('outside_dhaka') }}">ঢাকার বাহির </option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-12 col-md-12 col-12">
                                    <div class="form-group">
                                        <button id="orderConfirm" class="btn btn-block btn-success p-4"> অর্ডার কনফার্ম করুন</button>
                                    </div>
                                </div>
                            <!--/ End Form -->
                        </div>
                    </div>
                    <div class="col-lg-6 col-12 orderDetails">

                        <div class="order-details">

                            <!-- Order Widget -->
                            <div class="single-widget">
                                <h2>আপনার অর্ডার</h2>
                                <div class="content shopping-cart">
                                    <div class="table-responsive bg-white p-3">
                                        <table class="table border-bottom">
                                            <thead>
                                            <tr>
                                                <th class="product-image"></th>
                                                <th class="product-name">Product</th>
                                                <th class="product-price">Price</th>
                                                <th class="product-quanity">Quantity</th>
                                                <th class="product-total">Total</th>
                                                <th class="product-remove"></th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @foreach(Cart::content() as $item)
                                            <tr class="cart-item">
                                                <td class="product-image">
                                                    <button onclick="removeFromCart('{{ $item->rowId }}')" style=" padding: 6px; " class="btn btn-danger btn-sm">
                                                        <i class="fa fa-trash"></i>
                                                    </button>
                                                    <a href="#" class="mr-3">
                                                        <img class="lazyload" src="{{ url('/public/product/thumbnail/'.$item->model->productImage) }}" style="max-width: 50px">
                                                     </a>
                                                </td>

                                                <td class="product-name" style="max-width: 100px;">
                                                    <?php echo $item->model->productName ?>
                                                </td>

                                                <td class="product-price">
                                                    <span class="pr-3 d-block">৳ {{ $item->model->price() }}</span>
                                                </td>

                                                <td class="qty" data-title="Qty">
                                                    <div class="input-group input-spinner">
                                                        <div class="button plus ">
                                                            <button class="btn btn-primary btn-number" type="button" data-type="plus" data-field="quantity[<?php echo $item->id ?>]"> + </button>
                                                        </div>
                                                        <input type="text" name="quantity[<?php echo $item->id ?>]" class="form-control input-number" placeholder="1" value="<?php echo $item->qty ?>" min="1" max="10" onchange="updateQuantity('<?php echo $item->rowId ?>', this)">
                                                        <div class="button minus ">
                                                            <button class="btn btn-primary btn-number" type="button" data-type="minus"  data-field="quantity[<?php echo $item->id ?>]"> − </button>
                                                        </div>
                                                    </div>

                                                    <!--/ End Input Order -->
                                                </td>
                                                <td class="product-total" style="width: 80px;">
                                                    <span>৳ {{ $item->model->price()*$item->qty }}</span>
                                                </td>
                                            </tr>
                                            @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                    <ul>
                                        <li>Sub Total<span>৳ <?php echo Cart::total('0') ?></span></li>
                                        <li>(+) Shipping<span>৳ <?php echo $_SESSION['delivery'] ?></span></li>
                                        <li class="last">Total<span><?php echo Cart::subtotal('0','','')+$_SESSION['delivery']; ?></span></li>
                                    </ul>
                                </div>
                            </div>
                            <!--/ End Order Widget -->
                        </div>
                    </div>
                </div>
            </div>
        </section>
    @else
        <div class="container pb-5 mb-sm-4">
            <div class="pt-5">
                <div class="card py-3 mt-sm-3">
                    <div class="card-body text-center">
                        <h2 class="h4 pb-3">কোন প্রোডাক্ট নেই</h2>
	 		<a class="btn btn-primary mt-3" href="{{url('/')}}">অন্যান্য পণ্য দেখতে ক্লিক করুন</a>
                    </div>
                </div>
            </div>
        </div>
    @endif
@endsection

@push('js')
    <script>
        $(document).ready(function () {

            $('#selectCourier').on('change',function (e) {
                var selectCourier = +$('#selectCourier option:selected').val();
                $.ajax({
                    type: "get",
                    url: "{{url('/updateDeliveryCharge')}}",
                    data: {
                        'selectCourier':selectCourier,
                        '_token': '{{ csrf_token() }}'
                    },
                    success: function () {
                        updateQuantity(0,0);
                    }
                });
            });

            $(document).on("click", "#orderConfirm", function () {
                constantValue = 0;
                var customerName = $('#customerName');
                var customerAddress = $('#customerAddress');
                var customerPhone = $('#customerPhone');
                var selectCourier = $('#selectCourier option:selected');
                if (!customerName.val()) {
                    customerName.addClass("has-error");
                    constantValue = 1;
                }
                if (!customerAddress.val()) {
                    customerAddress.addClass("has-error");
                    constantValue = 1;
                }
                if (!customerPhone.val()) {
                    customerPhone.addClass("has-error");
                    constantValue = 1;
                }
                console.log(selectCourier.val())
                if (selectCourier.val() === '') {
                    selectCourier.addClass("has-error");
                    showFrontendAlert('error', 'Unsuccessful to Place order');
                    constantValue = 1;
                }
                if (constantValue === 1) {
                    $('html, body').animate(  {  scrollTop: $('body').position().top  },  500   );
                } else {
                $(this).prop("disabled",true);
                    $.ajax({
                        type: "post",
                        url: "{{url('/placeOrder')}}",
                        data: {
                            'customerName': customerName.val(),
                            'customerAddress': customerAddress.val(),
                            'customerPhone': customerPhone.val(),
                            'selectCourier': selectCourier.val(),
                            '_token': '{{ csrf_token() }}'
                        },
                        success: function (data) {
                            if(data['status'] === 'success'){
                                showFrontendAlert('success', 'Successfully Place order');
                                window.location.href = data['link'];

                            }else{
                                showFrontendAlert('error', 'Unsuccessful to Place order');
				$(this).prop("disabled",false);
                            }
                        }
                    });
                }
            });
        });
    </script>

@endpush

