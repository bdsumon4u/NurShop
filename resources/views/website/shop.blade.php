@extends('website.layout')
@section('content')

    <div class="product-area most-popular pt-4">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header card-warning">
                            <h2 class="float-left" style="font-size: large">All products</h2>
                        </div>
                        <div class="card-body">
                         
                            <div class="row">
                                @foreach($shop as $product)
                                    <div class="col-md-2 col-6">
                                        <div class="single-product">
                                            <div class="product-img">
                                                <a href="{{ $product->url() }}">
                                                    <img class="default-img" src="{{ asset('/public/product/'.$product->productImage)  }}" alt="{{ $product->productName  }}">
                                                </a>
                                            </div>
                                            <div class="product-content">
                                                <h3><a href="{{ $product->url() }}">{{ $product->productName  }}</a></h3>
                                                {!! $product->htmlPrice() !!}
                                                <button class="add-to-cart btn"  onclick="buyNow({{ $product->id }})" ><i class="fa fa-shopping-bag" aria-hidden="true"></i> অর্ডার করুন</button>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach

                            </div>
                            <div style=" display: flex; justify-content: center; margin-top: 39px; ">
                                {{ $shop->links() }}
                            </div>
                          
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

@endsection
