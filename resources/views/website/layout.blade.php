<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Meta Tag -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name='copyright' content=''>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- Title Tag  -->
    <title>{{ Settings::get('site_name') }}</title>
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('/public/'.Settings::get('site_logo')) }}">
    <!-- Web Font -->
    <link href="https://fonts.googleapis.com/css?family=Poppins:200i,300,300i,400,400i,500,500i,600,600i,700,700i,800,800i,900,900i&display=swap" rel="stylesheet">
    <!-- Bootstrap -->
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.css') }}">
    <!-- Magnific Popup -->
    <link rel="stylesheet" href="{{ asset('assets/css/magnific-popup.min.css') }}">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ asset('assets/css/font-awesome.css') }}">
    <!-- Fancybox -->
    <link rel="stylesheet" href="{{ asset('assets/css/themify-icons.css') }}">
    <!-- Owl Carousel -->
    <link rel="stylesheet" href="{{ asset('assets/css/owl-carousel.css') }}">
    <!-- Slicknav -->
    <link rel="stylesheet" href="{{ asset('assets/css/slicknav.min.css') }}">
    <!-- Eshop StyleSheet -->
    <link rel="stylesheet" href="{{ asset('assets/css/reset.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/style.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/responsive.css') }}">
    @stack('css')
    {!! Settings::get('facebook_pixels') !!}

</head>
<body class="js">
<!-- Header -->
<header class="header shop">
    <div class="middle-inner">
        <div class="container">
            <div class="row">
                <div class="col-lg-2 col-md-2 col-12">
                    <!-- Logo -->
                    <div class="logo">
                        <a href="{{ url('/') }}"><img  style="max-width: 100px;"  src="{{ asset('/public/'.Settings::get('site_logo')) }}" alt="{{ Settings::get('site_name') }}"></a>
                    </div>
                    <!--/ End Logo -->
                    <!-- Search Form -->
                    <div class="search-top">
                        <div class="sinlge-bar shopping">
                            <a href="{{ url('/checkout') }}" class="single-icon"><i class="ti-bag"></i> <span class="total-count">0</span></a>
                        </div> 
                        
                        <!-- Search Form -->
                        <div class="search-top">
                            <form action="{{ url('/shop')}}" method="get">
                                <input type="text" placeholder="Search here..." name="q" value="<?php if(isset($_REQUEST['q'])){echo $_REQUEST['q'];} ?>">
                                <button value="search" type="submit"><i class="ti-search"></i></button>
                            </form>
                        </div>
                        <!--/ End Search Form -->
                    </div>
                    <!--/ End Search Form -->
                    <div class="mobile-nav"></div>
                    
                </div>
                <div class="col-lg-9 col-md-7 col-12">
                    <div class="search-bar-top">
                        <div class="search-bar">
                            <form action="{{ url('/shop')}}" method="get">
                                <input name="q" placeholder="Search Products Here....." type="search" value="<?php if(isset($_REQUEST['q'])){echo $_REQUEST['q'];} ?>">
                                <button class="btnn"><i class="ti-search"></i></button>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-lg-1 col-md-3 col-12">
                    <div class="right-bar">
                        <!-- Search Form -->
                        <div class="sinlge-bar shopping">
                            <a href="{{ url('/checkout') }}" class="single-icon"><i class="ti-bag"></i> <span class="total-count">0</span></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Header Inner -->
    <div class="header-inner">
        <div class="container">
            <div class="cat-nav-head">
                <div class="row">
                    <div class="col-lg-12 col-12">
                        <div class="menu-area">
                            <!-- Main Menu -->
                            <nav class="navbar navbar-expand-lg">
                                <div class="navbar-collapse">
                                    <div class="nav-inner">
                                        <ul class="nav main-menu menu navbar-nav">
                                            <?php $category = Menu::getByName('Category Menu') ?>
                                            @foreach($category as $menu)
                                                <li>
                                                    <a href="{{ $menu['link'] }}">{{ $menu['label'] }} @if( $menu['child'] ) <i class="ti-angle-down"></i> @endif
                                                    </a>
                                                    @if( $menu['child'] )
                                                        <ul class="dropdown">
                                                            @foreach( $menu['child'] as $child )
                                                                <li><a href="{{ $child['link'] }}">{{ $child['label'] }}</a></li>
                                                            @endforeach
                                                        </ul><!-- /.sub-menu -->
                                                    @endif
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                            </nav>
                            <!--/ End Main Menu -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--/ End Header Inner -->
</header>
<!--/ End Header -->
@yield('content')
<!-- Modal -->
<div class="modal fade show" id="quickBuyNow" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span class="ti-close" aria-hidden="true"></span></button>
            </div>
            <div class="modal-body">
                <div class="row no-gutters">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="quickview-content">
                            <h2>Please Order Now</h2>
                            <br>
                                <div class="form-group">
                                    <label for="name">আপনার নাম</label>
                                    <input type="text" class="form-control" id="name" placeholder="Customer Name">
                                </div>
                                <div class="form-group">
                                    <label for="phone">আপনার মোবাইল নম্বার</label>
                                    <input type="number" class="form-control" id="phone" title="Phone number should be 11 digit number" placeholder="Format: 01......">
                                </div>
                                <div class="form-group">
                                    <label for="address">আপনার ঠিকানা</label>
                                    <textarea name="address"  class="form-control" id="address" rows="3" placeholder="Your address here"></textarea>
                                </div>
                                <div class="form-group">
                                    <label>Shipping Method<span>*</span></label>
                                    <select class="form-control" id="shipping" >
                                       <option value="{{ Settings::get('inside_dhaka') }}">ঢাকার ভিতর </option>
                                                    <option value="{{ Settings::get('outside_dhaka') }}">ঢাকার বাহির </option>
                                    </select>
                                </div>
                                <button type="submit" value="" id="submitBuyNow" class="add-to-cart  btn">Click To Confirm</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Modal end -->

<!-- Start Footer Area -->
<footer class="footer">
    <div class="copyright">
        <div class="container">
            <div class="inner">
                <div class="row">
                    <div class="col-lg-6 col-12">
                        <div class="left">
                            <p> {!! Settings::get('footer_copyright_text') !!}</p>
                        </div>
                    </div>
                    <div class="col-lg-6 col-12">
                        <div class="right">
                            <img src="{{ asset('assets/images/payments.png') }}" alt="#">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</footer>
<!-- /End Footer Area -->

<!-- Jquery -->
<script src="{{ asset('assets/js/jquery.min.js') }}"></script>
<script src="{{ asset('assets/js/jquery-migrate-3.0.0.js') }}"></script>
<!-- Popper JS -->
<script src="{{ asset('assets/js/popper.min.js') }}"></script>
<!-- Bootstrap JS -->
<script src="{{ asset('assets/js/bootstrap.min.js') }}"></script>
<!-- Slicknav JS -->
<script src="{{ asset('assets/js/slicknav.min.js') }}"></script>
<!-- Owl Carousel JS -->
<script src="{{ asset('assets/js/owl-carousel.js') }}"></script>
<!-- ScrollUp JS -->
<script src="{{ asset('assets/js/scrollup.js') }}"></script>

<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>

<!-- Active JS -->
<script src="{{ asset('assets/js/active.js') }}"></script>
<script>
    
    function quickBuyNow(id){
        $('#submitBuyNow').val(id);
        $('#name').val('');
        $('#phone').val('');
        $('#address').val('');
        
        $('#quickBuyNow').modal('show');

    }

    $('#submitBuyNow').click(function (){
        
        var id=  $('#submitBuyNow').val();
        var name = $('#name');
        var phone = $('#phone');
        var address = $('#address');
        var shipping = $('#shipping option:selected');
        var valid = 0;
        if(id === ''){
            swal("Somethings is wrong !");
            valid = 1;
        }
        if(name.val() === ''){
            name.css('border','1px solid #ed423e');
            valid = 1;
        }else{
            name.css('border','1px solid #28a745');
        }
        if(phone.val() === '' || phone.val().length < 11   || valiateNumber(phone.val()) ){
            phone.css('border','1px solid #ed423e');
            swal("Enter Valid Phone Number");
            valid = 1;
        }else{
            phone.css('border','1px solid #28a745');
        }
        if(address.val() === ''){
            address.css('border','1px solid #ed423e');
            valid = 1;
        }else{
            address.css('border','1px solid #28a745');
         }
        if(shipping.val() === 'Select Shipping Method'){
            $('#shipping').css('border','1px solid #ed423e');
            valid = 1;
        }else{
            $('#shipping').css('border','1px solid #28a745');
        }
        if(valid !== 1){
            $(this).prop("disabled",true);
            $.post("{{url('/quickPlaceOrder')}}", {
                _token: '{{ csrf_token() }}',
                id: id,
                name: name.val(),
                phone: phone.val(),
                address: address.val(),
                shipping: shipping.val()
            }, function (data) {
                if(data['status'] === 'success'){
                    window.location.href = data['link'];
                }else{
                    swal("Somethings is wrong !");
                    $(this).prop("disabled",false);
                }
                // showFrontendAlert('success', 'Successfully Product add to cart');
                // updateNavCart();
                
            });
        }
        return;
    });

    function valiateNumber(number){
        var pattern =/^(?:\\+88|88)?(01[3-9]\\d{8})$/;
        if (pattern.test(number)) {
            return true;
        }
        return false;
    }


    $(document).ready(function() {
        updateNavCart();
    });
    function showFrontendAlert(type, message) {
        if (type === 'danger') {
            type = 'error';
        }
        swal({
            position: 'top-end',
            type: type,
            title: message,
            showConfirmButton: false,
            timer: 500
        });
    }
    function updateNavCart() {
        $.ajax({
            type: "get",
            url: "{{url('/mini_cart')}}",
            contentType: "application/json",
            success: function (response) {
                $('.total-count').empty().prepend(response.count);
            }
        });
    }

    function removeFromCart(key) {
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            type: "DELETE",
            url: "{{url('/checkout')}}/" + key,
            data: {
                '_token': '{{ csrf_token() }}'
            },
            contentType: "application/json",
            success: function (response) {
                


                showFrontendAlert('success', 'Successfully Product Removed from Cart');
                updateNavCart();
                updateQuantity(key,0);
                if(response['reload'] === 'true'){
                    location.reload();
                }

            }
        });
    }

    function updateQuantity(key, element){
        $.get("{{url('/updateQuantity')}}", { _token:'{{ csrf_token() }}', key:key, quantity: element.value}, function(data){
            updateNavCart();
            $('.orderDetails').html(data);
        });
    }

    function addToCart(id) {
        $.post("{{url('/checkout')}}", {
            _token: '{{ csrf_token() }}',
            id: id
        }, function (data) {
            showFrontendAlert('success', 'Successfully Product add to cart');
            updateNavCart();
        });
    }

    function buyNow(id) {
        $.post("{{url('/checkout')}}", {
            _token: '{{ csrf_token() }}',
            id: id
        }, function (data) {
            showFrontendAlert('success', 'Successfully Product add to cart');
            updateNavCart();
            window.location.href = '{{url('/checkout')}}';
        });
    }

    $(".btn-number").click(function (e) {
        e.preventDefault();

        var fieldName = $(this).attr("data-field");
        type = $(this).attr("data-type");
        var input = $("input[name='" + fieldName + "']");
        var currentVal = parseInt(input.val());

        if (!isNaN(currentVal)) {
            if (type === "minus") {
                if (currentVal > input.attr("min")) {
                    input.val(currentVal - 1).change();
                }
                if (parseInt(input.val()) === input.attr("min")) {
                    $(this).attr("disabled", true);
                }
            } else if (type === "plus") {
                if (currentVal < input.attr("max")) {
                    input.val(currentVal + 1).change();
                }
                if (parseInt(input.val()) === input.attr("max")) {
                    $(this).attr("disabled", true);
                }
            }
        } else {
            input.val(0);
        }
    });

        function cartQuantityInitialize() {
            $('.btn-number').click(function (e) {
                e.preventDefault();

                fieldName = $(this).attr('data-field');
                type = $(this).attr('data-type');
                var input = $("input[name='" + fieldName + "']");
                var currentVal = parseInt(input.val());

                if (!isNaN(currentVal)) {
                    if (type == 'minus') {

                        if (currentVal > input.attr('min')) {
                            input.val(currentVal - 1).change();
                        }
                        if (parseInt(input.val()) == input.attr('min')) {
                            $(this).attr('disabled', true);
                        }

                    } else if (type == 'plus') {

                        if (currentVal < input.attr('max')) {
                            input.val(currentVal + 1).change();
                        }
                        if (parseInt(input.val()) == input.attr('max')) {
                            $(this).attr('disabled', true);
                        }

                    }
                } else {
                    input.val(0);
                }
            });

            $('.input-number').focusin(function () {
                $(this).data('oldValue', $(this).val());
            });

            $('.input-number').change(function () {

                minValue = parseInt($(this).attr('min'));
                maxValue = parseInt($(this).attr('max'));
                valueCurrent = parseInt($(this).val());

                name = $(this).attr('name');
                if (valueCurrent >= minValue) {
                    $(".btn-number[data-type='minus'][data-field='" + name + "']").removeAttr('disabled')
                } else {
                    alert('Sorry, the minimum value was reached');
                    $(this).val($(this).data('oldValue'));
                }
                if (valueCurrent <= maxValue) {
                    $(".btn-number[data-type='plus'][data-field='" + name + "']").removeAttr('disabled')
                } else {
                    alert('Sorry, the maximum value was reached');
                    $(this).val($(this).data('oldValue'));
                }


            });
            $(".input-number").keydown(function (e) {
                // Allow: backspace, delete, tab, escape, enter and .
                if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 190]) !== -1 ||
                    // Allow: Ctrl+A
                    (e.keyCode == 65 && e.ctrlKey === true) ||
                    // Allow: home, end, left, right
                    (e.keyCode >= 35 && e.keyCode <= 39)) {
                    // let it happen, don't do anything
                    return;
                }
                // Ensure that it is a number and stop the keypress
                if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
                    e.preventDefault();
                }
            });
        }


</script>
@stack('js')
</body>
</html>