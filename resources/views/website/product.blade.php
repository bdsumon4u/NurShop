@extends('website.layout')
@section('content')
	<style>
		.carousel-control-next-icon, .carousel-control-prev-icon {
			display: inline-block;
			width: 30px;
			height: 50px;
			background: black no-repeat center center;
			background-size: 15px;
		}
		.carousel-control-prev-icon {
			background-image: url("data:image/svg+xml;charset=utf8,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='%23fff' viewBox='0 0 8 8'%3E%3Cpath d='M5.25 0l-4 4 4 4 1.5-1.5-2.5-2.5 2.5-2.5-1.5-1.5z'/%3E%3C/svg%3E") !important;
		}

		.carousel-control-next-icon {
			background-image: url("data:image/svg+xml;charset=utf8,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='%23fff' viewBox='0 0 8 8'%3E%3Cpath d='M2.75 0l-1.5 1.5 2.5 2.5-2.5 2.5 1.5 1.5 4-4-4-4z'/%3E%3C/svg%3E") !important;
		}
	</style>
	<div class="product-area most-popular pt-4">
		<div class="container">
			<div class="row">
				<div class="col-12">
					<div class="card">
						<div class="card-body product-gallery">
							 
								<div class="row no-gutters">
									<div class="col-lg-4 col-md-12 col-sm-12 col-xs-12">
										<!-- Product Slider -->
										<div class="product-gallery">
											<div id="carouselExampleControls" class="carousel slide" data-ride="carousel">
												<div class="carousel-inner">
													@foreach($product->gallery() as $gallery)
														<div class="carousel-item  @if ($loop->first) active @endif">
															<img class="d-block w-100" src="{{ url('/public/product/'.$gallery)  }}" alt="First slide">
														</div>
													@endforeach
												</div>
												<a class="carousel-control-prev" href="#carouselExampleControls" role="button" data-slide="prev">
													<span class="carousel-control-prev-icon" aria-hidden="true"></span>
													<span class="sr-only">Previous</span>
												</a>
												<a class="carousel-control-next" href="#carouselExampleControls" role="button" data-slide="next">
													<span class="carousel-control-next-icon" aria-hidden="true"></span>
													<span class="sr-only">Next</span>
												</a>
											</div>
										</div>
										<!-- End Product slider -->
									</div>
									<div class="col-lg-8 col-md-12 col-sm-12 col-xs-12">
										<div class="product-content">
											<h3>{{ $product->productName  }}</h3>
											{!! $product->htmlPrice() !!}

											<div class="size">

												<!--<div class="quantity  mb-2"> -->
												<!--			<div class="input-group qty-box">-->
												<!--				<div class="button plus ">-->
												<!--					<button type="button" class="btn btn-primary btn-number" data-type="minus" data-field="quant[1]">-->
												<!--						<i class="fa fa-minus"></i>-->
												<!--					</button>-->
												<!--				</div>-->
												<!--				<input type="text" name="quantity" class="input-number"  data-min="1" data-max="1000" value="1">-->
												<!--				<div class="button minus">-->
												<!--					<button type="button" class="btn btn-primary btn-number" data-type="plus" data-field="quant[1]">-->
												<!--						<i class="ti-plus"></i>-->
												<!--					</button>-->
												<!--				</div>-->
												<!--			</div>-->
														 
												<!--		</div>-->
												 
												<div style="display: flex">
													<a href="#" onclick="buyNow({{ $product->id }})" class="add-to-cart btn"> অর্ডার করুন</a>
													<a href="#" style="background: #fc5403;color: white !important;" onclick="addToCart({{ $product->id }})"  class="add-to-cart btn"> Add to cart</a>
												</div>

													<a href="tel:{{ Settings::get('phone_number') }}" class="mt-2 btn btn-block btn-primary text-center">
														কল করতে ক্লিক করুন <br>
														<i class="fa fa-phone" aria-hidden="true"></i> {{ Settings::get('phone_number') }}
													</a>
											</div>

											<div class="quickview-peragraph">
												{!!  Settings::get('product_bottom_text')  !!}
												
											</div>
										</div>
									</div>
								</div>
							 
						</div>
					</div>

				</div>
			</div>
		</div>
	</div>

	<div class="product-area most-popular pt-4">
		<div class="container">
			<div class="row">
				<div class="col-12">
					<div class="card">
						<div class="card-header card-warning">
							<h2 class="float-left" >Discription</h2>
						</div>
						<div class="card-body product-gallery">
							<div class="container">
								<div class="row">
									<div class="col-lg-12">
										{!! $product->productDetails !!}
									</div>
								</div>
							</div>
						</div>
					</div>

				</div>
			</div>
		</div>
	</div>


<div class="product-area most-popular pt-4">
		<div class="container">
			<div class="row">
				<div class="col-12">
					<div class="card">
						<div class="card-header card-warning">
							<h2 class="float-left" >Related products</h2>
							<a href="" class="float-right btn btn-danger see-more btn-sm"> See More</a>
						</div>
						<div class="card-body">
							 
							<div class="row">
									@foreach($product->related as $product)
										<div class="col-md-2 col-6">
											<div class="single-product">
												<div class="product-img">
													<a href="{{ $product->url()  }}">
														<img class="default-img" src="{{ asset('/public/product/thumbnail/'.$product->productImage)  }}" alt="{{ $product->productName  }}">
													</a>
												</div>
												<div class="product-content">
													<h3><a href="{{ $product->url() }}">{{ $product->productName  }}</a></h3>
													{!! $product->htmlPrice() !!}
													<button class="add-to-cart btn"  onclick="quickBuyNow({{ $product->id }})" ><i class="fa fa-shopping-bag" aria-hidden="true"></i> অর্ডার করুন</button>
												</div>
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
	<div class="product-area most-popular pt-4">
		<div class="container">
			<div class="row">
				<div class="col-12">
					<div class="card">
						<div class="card-header card-warning">
							<h2 class="float-left" >Other products</h2>
							<a href="" class="float-right btn btn-danger see-more btn-sm"> See More</a>
						</div>
						<div class="card-body">
							 
							<div class="row">
									@foreach($relatedProducts as $product)
										<div class="col-md-2 col-6">
											<div class="single-product">
												<div class="product-img">
													<a href="{{ $product->url()  }}">
														<img class="default-img" src="{{ asset('/public/product/'.$product->productImage)  }}" alt="{{ $product->productName  }}">
													</a>
												</div>
												<div class="product-content">
													<h3><a href="{{ $product->url() }}">{{ $product->productName  }}</a></h3>
													{!! $product->htmlPrice() !!}
													<button class="add-to-cart btn"  onclick="quickBuyNow({{ $product->id }})" ><i class="fa fa-shopping-bag" aria-hidden="true"></i> অর্ডার করুন</button>
												</div>
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

@endsection
