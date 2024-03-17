@extends('website.layout')
@section('content')

 <div class="product-area most-popular pt-4">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header card-warning">
                            <h2 class="float-left" style="font-size: large">{{ $page->pageTitle }}</h2>
                        </div>
                        <div class="card-body">
                            
                             {!! $page->pageContent !!} 
                            
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div> 

@endsection
