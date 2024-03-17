@extends('layouts.app')
@section('content')


    <div class="row">
        <div class="col-xl-12">
            <div class="card-box">
                <h4 class="header-title mb-4">Website Settings</h4>

                <div class="row">
                    <div class="col-sm-3">
                        <div class="nav flex-column nav-pills nav-pills-tab" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                            <a class="nav-link active show mb-1" id="v-pills-home-tab" data-toggle="pill" href="#v-pills-home" role="tab" aria-controls="v-pills-home" aria-selected="false">General</a>
                            <a class="nav-link mb-1" id="v-pills-profile-tab" data-toggle="pill" href="#v-pills-profile" role="tab" aria-controls="v-pills-profile" aria-selected="false">  Site Logo</a>
                            <a class="nav-link mb-1 " id="v-pills-product-bottom-tab" data-toggle="pill" href="#v-pills-product-bottom" role="tab" aria-controls="v-pills-product-bottom" aria-selected="false"> Product</a>
                            <a class="nav-link mb-1" id="v-pills-messages-tab" data-toggle="pill" href="#v-pills-messages" role="tab" aria-controls="v-pills-messages" aria-selected="false"> Footer</a>
                            <a class="nav-link mb-1 " id="v-pills-settings-tab" data-toggle="pill" href="#v-pills-settings" role="tab" aria-controls="v-pills-settings" aria-selected="false"> Social Links</a>
                            <a class="nav-link mb-1 " id="v-pills-settings-tab" data-toggle="pill" href="#v-pills-analytics" role="tab" aria-controls="v-pills-settings" aria-selected="false"> Analytics</a>
                            <a class="nav-link mb-1 " id="v-pills-settings-tab" data-toggle="pill" href="#v-pills-payments" role="tab" aria-controls="v-pills-settings" aria-selected="false"> Payments</a>
                        </div>
                    </div> <!-- end col-->
                    <div class="col-sm-9">
                        <div class="tab-content pt-0">
                            <div class="tab-pane fade active show" id="v-pills-home" role="tabpanel" aria-labelledby="v-pills-home-tab">
                                @include('admin.settings.includes.general')
                            </div>
                            <div class="tab-pane fade" id="v-pills-profile" role="tabpanel" aria-labelledby="v-pills-profile-tab">
                                @include('admin.settings.includes.logo')
                            </div>
                            <div class="tab-pane fade" id="v-pills-messages" role="tabpanel" aria-labelledby="v-pills-messages-tab">
                                @include('admin.settings.includes.footer_seo')
                            </div>
                            <div class="tab-pane fade  show" id="v-pills-settings" role="tabpanel" aria-labelledby="v-pills-settings-tab">
                                @include('admin.settings.includes.social_links')
                            </div>
                            <div class="tab-pane fade  show" id="v-pills-analytics" role="tabpanel" aria-labelledby="v-pills-settings-tab">
                                @include('admin.settings.includes.analytics')
                            </div>
                            <div class="tab-pane fade  show" id="v-pills-payments" role="tabpanel" aria-labelledby="v-pills-settings-tab">
                                @include('admin.settings.includes.payments')
                            </div>
                            <div class="tab-pane fade  show" id="v-pills-product-bottom" role="tabpanel" aria-labelledby="v-pills-settings-tab">
                                @include('admin.settings.includes.product')
                            </div>

                        </div>
                    </div> <!-- end col-->
                </div> <!-- end row-->

            </div>
        </div> <!-- end col -->


    </div>
@endsection

@push('js')
    <script>
        $(document).ready(function(){



        });
    </script>
@endpush

