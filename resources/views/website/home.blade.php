@extends('website.layout')
@section('content')

    <section class="hero-slider">
        <!-- Single Slider -->
            <div class="container">
                <div class="owl-carousel home-slider-4">
                    @foreach($slides as $key=>$slide)
                    <div>
                        <a href="{{ $slide->link }}"><img  src="{{ asset('/public/'.$slide->image) }}" alt="{{ $slide->name }}" /></a>
                    </div>
                    @endforeach
                </div>
        </div>
        <!--/ End Single Slider -->
    </section>

    @if($topProducts->count() > 0)
    <div class="product-area most-popular pt-2">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header card-warning">
                            <img src="{{ asset('assets/images/hot-deal-logo.gif') }}" class="float-left" style="max-width: 100px;">
                            <a href="{{ url('/category/'.$slug) }}" class="float-right see-more btn btn-danger btn-sm"> See More</a>
                        </div>
                        <div class="card-body">
                            <div class="owl-carousel popular-slider">
                                @foreach($topProducts as $product)
                                    <div class="single-product">
                                        <div class="product-img">
                                            <a href="{{ $product->url() }}">
                                                <img class="default-img lazyload" data-src="{{ asset('/public/product/'.$product->productImage)  }}" alt="{{ $product->productName  }}">
                                            </a>
                                        </div>
                                        <div class="product-content">
                                            <h3><a href="{{ $product->url()  }}">{{ $product->productName  }}</a></h3>
                                            {!! $product->htmlPrice() !!}
                                            <button class="add-to-cart btn" onclick="buyNow({{ $product->id }})"><i class="fa fa-shopping-bag"  aria-hidden="true"></i> অর্ডার করুন</button>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif
 
    <div class="product-area most-popular pt-4">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header card-warning">
                            <h2 class="float-left" >All Products</h2>
                            <a href="{{ url('/shop') }}" class="float-right btn btn-danger btn-sm see-more"> See More</a>
                        </div>
                        <div class="card-body">
                                <div class="row">
                                    @foreach($otherProducts as $product)
                                    <div class="col-md-2 col-6">
                                        <div class="single-product">
                                            <div class="product-img">
                                                <a href="{{ $product->url()  }}">
                                                    <img class="default-img lazyload" src="{{ asset('/public/product/'.$product->productImage)  }}" alt="{{ $product->productName  }}">
                                                </a>
                                            </div>
                                            <div class="product-content">
                                                <h3><a href="{{ $product->url()  }}">{{ $product->productName  }}</a></h3>
                                                {!! $product->htmlPrice() !!}
                                                <button class="add-to-cart btn"  onclick="buyNow({{ $product->id }})" ><i class="fa fa-shopping-bag" aria-hidden="true"></i> অর্ডার করুন</button>
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                    <div style="display: block;margin: 20px auto;">
                                        {{ $otherProducts->links() }}
                                    </div>
                                </div>
                           
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

@endsection
