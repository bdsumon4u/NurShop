<div class="left-side-menu">

    <div class="slimscroll-menu">

        <!--- Sidemenu -->
        <div id="sidebar-menu">
            @if(Auth::check() && Auth::user()->role->id == 1)
                <ul class="metismenu" id="side-menu">
                <li class="menu-title">Navigation</li>
                <li class="{{ (request()->is('admin/dashboard')) ? 'active' : '' }}">
                    <a href="{{route('admin.dashboard')}}" class="{{ (request()->is('admin/dashboard')) ? 'active' : '' }}">
                        <i class="fe-airplay"></i>
                        <span> Dashboards </span>
                    </a>
                </li>
                    <li>
                        <a href="javascript: void(0);">
                            <i class="fe-globe"></i>
                            <span> Website </span>
                            <span class="menu-arrow"></span>
                        </a>
                        <ul class="nav-second-level" aria-expanded="false">
                            <li class="{{ (request()->is('admin/product')) ? 'active' : '' }}">
                                <a href="{{url('admin/product ')}}" class="{{ (request()->is('admin/product')) ? 'active' : '' }}">Products</a>
                            </li>
                            <li class="{{ (request()->is('admin/media')) ? 'active' : '' }}">
                                <a href="{{url('admin/media ')}}" class="{{ (request()->is('admin/media')) ? 'active' : '' }}">Media</a>
                            </li>
                            <li class="{{ (request()->is('admin/category')) ? 'active' : '' }}">
                                <a href="{{url('admin/category ')}}" class="{{ (request()->is('admin/category')) ? 'active' : '' }}">Category</a>
                            </li>
                            <li class="{{ (request()->is('admin/menu')) ? 'active' : '' }}">
                                <a href="{{url('admin/menu ')}}" class="{{ (request()->is('admin/menu')) ? 'active' : '' }}">Menu</a>
                            </li>
                            <li class="{{ (request()->is('admin/page')) ? 'active' : '' }}">
                                <a href="{{url('admin/page ')}}" class="{{ (request()->is('admin/page')) ? 'active' : '' }}">Page</a>
                            </li>
                            <li class="{{ (request()->is('admin/slider')) ? 'active' : '' }}">
                                <a href="{{url('admin/slider ')}}" class="{{ (request()->is('admin/slider')) ? 'active' : '' }}">Slider</a>
                            </li>
                            <li class="{{ (request()->is('admin/setting')) ? 'active' : '' }}">
                                <a href="{{url('admin/setting ')}}" class="{{ (request()->is('admin/setting')) ? 'active' : '' }}">Settings</a>
                            </li>
                        </ul>
                    </li>
                <li>
                    <a href="javascript: void(0);">
                        <i class="fe-package"></i>
                        <span> Store </span>
                        <span class="menu-arrow"></span>
                    </a>
                    <ul class="nav-second-level" aria-expanded="false">

                        <li class="{{ (request()->is('admin/store')) ? 'active' : '' }}">
                            <a href="{{url('admin/store ')}}" class="{{ (request()->is('admin/store')) ? 'active' : '' }}">Store</a>
                        </li>
                        <li >
                            <a href="{{url('admin/purchase ')}}" class="{{ (request()->is('admin/purchase')) ? 'active' : '' }}">Purchase</a>
                        </li>
                        <li>
                            <a href="{{url('admin/stock ')}}" class="{{ (request()->is('admin/stock')) ? 'active' : '' }}">Stock</a>
                        </li>
                        <li>
                            <a href="{{url('admin/supplier ')}}" class="{{ (request()->is('admin/supplier')) ? 'active' : '' }}">Supplier</a>
                        </li>
                        <li>
                            <a href="{{url('admin/payment ')}}" class="{{ (request()->is('admin/payment')) ? 'active' : '' }}">Payment</a>
                        </li>
                        <li>
                            <a href="{{url('admin/payment/type ')}}" class="{{ (request()->is('admin/payment/type')) ? 'active' : '' }}">Payment Method</a>
                        </li>
                    </ul>
                </li>
                <li>
                    <a href="javascript: void(0);">
                        <i class="fe-truck"></i>
                        <span> Courier </span>
                        <span class="menu-arrow"></span>
                    </a>
                    <ul class="nav-second-level" aria-expanded="false">
                        <li class="{{ (request()->is('admin/courier')) ? 'active' : '' }}">
                            <a href="{{url('admin/courier ')}}" class="{{ (request()->is('admin/courier')) ? 'active' : '' }}">Courier</a>
                        </li>
                        <li class="{{ (request()->is('admin/city')) ? 'active' : '' }}">
                            <a href="{{url('admin/city ')}}" class="{{ (request()->is('admin/city')) ? 'active' : '' }}">City</a>
                        </li>
                        <li class="{{ (request()->is('admin/zone')) ? 'active' : '' }}">
                            <a href="{{url('admin/zone ')}}" class="{{ (request()->is('admin/zone')) ? 'active' : '' }}">Zone</a>
                        </li>
                    </ul>
                </li>
                <li class="{{ (request()->routeIs('admin/order')) ? 'active' : '' }}">
                    <a href="{{url('admin/order/status/Processing ')}}" class="{{ (request()->is('admin/order')) ? 'active' : '' }}">
                        <i class="fe-shopping-cart"></i>
                        <span> Order </span>
                    </a>
                </li>
                <li class="{{ (request()->is('admin/order/Pending Invoiced')) ? 'active' : '' }}">
                    <a href="{{url('admin/order/status/Pending Invoiced ')}}" class="{{ (request()->is('admin/order/status/Pending Invoiced')) ? 'active' : '' }}">
                        <i class="fas fa-file-invoice"></i>
                        <span> Invoiced </span>
                    </a>
                </li>
                <li class="{{ (request()->is('admin/order/Delivered')) ? 'active' : '' }}">
                    <a href="{{url('admin/order/status/Delivered ')}}" class="{{ (request()->is('admin/order/status/Delivered')) ? 'active' : '' }}">
                        <i class="mdi mdi-truck-check"></i>
                        <span> Delivered </span>
                    </a>
                </li>
                <li class="{{ (request()->is('admin/user')) ? 'active' : '' }}">
                    <a href="{{url('admin/user ')}}" class="{{ (request()->is('admin/user')) ? 'active' : '' }}">
                        <i class="fas fa-user"></i>
                        <span> User </span>
                    </a>
                </li>
                <li>
                    <a href="javascript: void(0);">
                        <i class="fe-truck"></i>
                        <span> Report </span>
                        <span class="menu-arrow"></span>
                    </a>
                    <ul class="nav-second-level" aria-expanded="false">
                        <li class="{{ (request()->is('admin/report/multipleDateCourierUser')) ? 'active' : '' }}">
                            <a href="{{url('admin/report/multipleDateCourierUser ')}}" class="{{ (request()->is('admin/report/multipleDateCourierUser')) ? 'active' : '' }}">Courier User Report</a>
                        </li>
                        <li class="{{ (request()->is('admin/report/dateCourier')) ? 'active' : '' }}">
                            <a href="{{url('admin/report/dateCourier ')}}" class="{{ (request()->is('admin/report/dateCourier')) ? 'active' : '' }}">Courier Report</a>
                        </li>
                        <li class="{{ (request()->is('admin/report/dateUser')) ? 'active' : '' }}">
                            <a href="{{url('admin/report/dateUser ')}}" class="{{ (request()->is('admin/report/dateUser')) ? 'active' : '' }}">User Report</a>
                        </li>
                        <li class="{{ (request()->is('admin/report/product')) ? 'active' : '' }}">
                            <a href="{{url('admin/report/product ')}}" class="{{ (request()->is('admin/report/product')) ? 'active' : '' }}">Product</a>
                        </li>
                        <li class="{{ (request()->is('admin/report/payment')) ? 'active' : '' }}">
                            <a href="{{url('admin/report/payment ')}}" class="{{ (request()->is('admin/report/payment')) ? 'active' : '' }}">Payment</a>
                        </li>
                    </ul>
                </li>

            </ul>
            @endif
            @if(Auth::user()->role->id == 2)
                    <ul class="metismenu" id="side-menu">
                        <li class="menu-title">Navigation</li>
                        <li class="{{ (request()->is('manager/dashboard')) ? 'active' : '' }}">
                            <a href="{{route('manager.dashboard')}}" class="{{ (request()->is('manager/dashboard')) ? 'active' : '' }}">
                                <i class="fe-airplay"></i>
                                <span> Dashboards </span>
                            </a>
                        </li>
                        <li>
                            <a href="javascript: void(0);">
                                <i class="fe-package"></i>
                                <span> Store </span>
                                <span class="menu-arrow"></span>
                            </a>
                            <ul class="nav-second-level" aria-expanded="false">
                                <li class="{{ (request()->is('manager/product')) ? 'active' : '' }}">
                                    <a href="{{url('manager/product ')}}" class="{{ (request()->is('manager/product')) ? 'active' : '' }}">Products</a>
                                </li>
                                <li class="{{ (request()->is('manager/store')) ? 'active' : '' }}">
                                    <a href="{{url('manager/store ')}}" class="{{ (request()->is('manager/store')) ? 'active' : '' }}">Store</a>
                                </li>
                                <li >
                                    <a href="{{url('manager/purchase ')}}" class="{{ (request()->is('manager/purchase')) ? 'active' : '' }}">Purchase</a>
                                </li>
                                <li>
                                    <a href="{{url('manager/stock ')}}" class="{{ (request()->is('manager/stock')) ? 'active' : '' }}">Stock</a>
                                </li>
                                <li>
                                    <a href="{{url('manager/supplier ')}}" class="{{ (request()->is('manager/supplier')) ? 'active' : '' }}">Supplier</a>
                                </li>
                                <li>
                                    <a href="{{url('manager/payment ')}}" class="{{ (request()->is('manager/payment')) ? 'active' : '' }}">Payment</a>
                                </li>
                                <li>
                                    <a href="{{url('manager/payment/type ')}}" class="{{ (request()->is('manager/payment/type')) ? 'active' : '' }}">Payment Method</a>
                                </li>
                            </ul>
                        </li>
                        <li>
                            <a href="javascript: void(0);">
                                <i class="fe-truck"></i>
                                <span> Courier </span>
                                <span class="menu-arrow"></span>
                            </a>
                            <ul class="nav-second-level" aria-expanded="false">
                                <li class="{{ (request()->is('manager/courier')) ? 'active' : '' }}">
                                    <a href="{{url('manager/courier ')}}" class="{{ (request()->is('manager/courier')) ? 'active' : '' }}">Courier</a>
                                </li>
                                <li class="{{ (request()->is('manager/city')) ? 'active' : '' }}">
                                    <a href="{{url('manager/city ')}}" class="{{ (request()->is('manager/city')) ? 'active' : '' }}">City</a>
                                </li>
                                <li class="{{ (request()->is('manager/zone')) ? 'active' : '' }}">
                                    <a href="{{url('manager/zone ')}}" class="{{ (request()->is('manager/zone')) ? 'active' : '' }}">Zone</a>
                                </li>
                            </ul>
                        </li>
                        <li class="{{ (request()->routeIs('manager/order')) ? 'active' : '' }}">
                            <a href="{{url('manager/order/status/Processing ')}}" class="{{ (request()->is('manager/order')) ? 'active' : '' }}">
                                <i class="fe-shopping-cart"></i>
                                <span> Order </span>
                            </a>
                        </li>
                        <li class="{{ (request()->is('manager/order/Pending Invoiced')) ? 'active' : '' }}">
                            <a href="{{url('manager/order/status/Pending Invoiced ')}}" class="{{ (request()->is('manager/order/status/Pending Invoiced')) ? 'active' : '' }}">
                                <i class="fas fa-file-invoice"></i>
                                <span> Invoiced </span>
                            </a>
                        </li>
                        <li class="{{ (request()->is('manager/order/Delivered')) ? 'active' : '' }}">
                            <a href="{{url('manager/order/status/Delivered ')}}" class="{{ (request()->is('manager/order/status/Delivered')) ? 'active' : '' }}">
                                <i class="mdi mdi-truck-check"></i>
                                <span> Delivered </span>
                            </a>
                        </li>
                        <li class="{{ (request()->is('manager/user')) ? 'active' : '' }}">
                            <a href="{{url('manager/user ')}}" class="{{ (request()->is('manager/user')) ? 'active' : '' }}">
                                <i class="fas fa-user"></i>
                                <span> User </span>
                            </a>
                        </li>
                        <li>
                            <a href="javascript: void(0);">
                                <i class="fe-truck"></i>
                                <span> Report </span>
                                <span class="menu-arrow"></span>
                            </a>
                            <ul class="nav-second-level" aria-expanded="false">
                                <li class="{{ (request()->is('manager/report/multipleDateCourierUser')) ? 'active' : '' }}">
                                    <a href="{{url('manager/report/multipleDateCourierUser ')}}" class="{{ (request()->is('manager/report/multipleDateCourierUser')) ? 'active' : '' }}">Courier User Report</a>
                                </li>
                                <li class="{{ (request()->is('manager/report/dateCourier')) ? 'active' : '' }}">
                                    <a href="{{url('manager/report/dateCourier ')}}" class="{{ (request()->is('manager/report/dateCourier')) ? 'active' : '' }}">Courier Report</a>
                                </li>
                                <li class="{{ (request()->is('manager/report/dateUser')) ? 'active' : '' }}">
                                    <a href="{{url('manager/report/dateUser ')}}" class="{{ (request()->is('manager/report/dateUser')) ? 'active' : '' }}">User Report</a>
                                </li>
                                <li class="{{ (request()->is('manager/report/product')) ? 'active' : '' }}">
                                    <a href="{{url('manager/report/product ')}}" class="{{ (request()->is('manager/report/product')) ? 'active' : '' }}">Product</a>
                                </li>
                                <li class="{{ (request()->is('manager/report/payment')) ? 'active' : '' }}">
                                    <a href="{{url('manager/report/payment ')}}" class="{{ (request()->is('manager/report/payment')) ? 'active' : '' }}">Payment</a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                @endif
            @if(Auth::check() && Auth::user()->role->id == 3 )
                <ul class="metismenu" id="side-menu">
                    <li class="menu-title">Navigation</li>
                    <li class="{{ (request()->is('user/dashboard')) ? 'active' : '' }}">
                        <a href="{{route('user.dashboard')}}" class="{{ (request()->is('user/dashboard')) ? 'active' : '' }}">
                            <i class="fe-airplay"></i>
                            <span> Dashboards </span>
                        </a>
                    </li>
                    <li class="{{ (request()->routeIs('user/order')) ? 'active' : '' }}">
                        <a href="{{url('user/order/status/Processing')}}" class="{{ (request()->is('user/order')) ? 'active' : '' }}">
                            <i class="fe-shopping-cart"></i>
                            <span> Order </span>
                        </a>
                    </li>
                    <li class="{{ (request()->is('user/order/Pending Invoiced')) ? 'active' : '' }}">
                        <a href="{{url('user/order/status/Pending Invoiced')}}" class="{{ (request()->is('user/order/status/Pending Invoiced')) ? 'active' : '' }}">
                            <i class="fas fa-file-invoice"></i>
                            <span> Invoiced </span>
                        </a>
                    </li>
                    <li class="{{ (request()->is('user/order/Delivered')) ? 'active' : '' }}">
                        <a href="{{url('user/order/status/Delivered')}}" class="{{ (request()->is('user/order/status/Delivered')) ? 'active' : '' }}">
                            <i class="mdi mdi-truck-check"></i>
                            <span> Delivered </span>
                        </a>
                    </li>
                    <li class="{{ (request()->is('user/order/complain')) ? 'active' : '' }}">
                        <a href="{{url('user/order/complain')}}" class="{{ (request()->is('user/order/complain')) ? 'active' : '' }}">
                            <i class="fas fa-bullhorn"></i>
                            <span> Complain </span>
                        </a>
                    </li>
                </ul>
            @endif
        </div>
        <!-- End Sidebar -->
        <div class="clearfix"></div>

    </div>
    <!-- Sidebar -left -->

</div>
