<nav class="navbar-default navbar-static-side" role="navigation">
    <div class="sidebar-collapse">
        <ul class="nav metismenu" id="side-menu">
            <li class="nav-header">
                <div class="dropdown profile-element">
                    <span>
                        <img style="width: 100px; height: 100px;" src="{{ asset('logo/LogoPNG.png') }}"
                        alt="">
                    </span>
                    <a data-toggle="dropdown" class="dropdown-toggle" href="#">
                        <span class="clear"> <span class="block m-t-xs"> <strong
                                    class="font-bold">{{ Auth::user()->name }}</strong>
                            </span> <span class="text-muted text-xs block">Admin HightFive <b
                                    class="caret"></b></span> </span> </a>
                    <ul class="dropdown-menu animated fadeInRight m-t-xs">
                        <li><a href="profile.html">Profile</a></li>
                        <li><a href="contacts.html">Contacts</a></li>
                        <li><a href="mailbox.html">Mailbox</a></li>
                        <li class="divider"></li>
                        <li><a href="route('logout')">Logout</a></li>
                    </ul>
                </div>
                <div class="logo-element">
                    <img style="width: 70px; height: 70px;" src="{{ asset('logo/LogoPNG.png') }}"
                        alt="">

                </div>
            </li>
            @can('view dashboard')
            <li class="{{ Request::is('/') ? 'active' : '' }}">
                <a href="{{ url('/') }}">
                    <i class="fa fa-home"></i>
                    <span class="nav-label">Trang Chủ</span>
                </a>
            </li>
            @endcan
            @can('manage users')
                <li class="{{ Request::is('admin/users*') ? 'active' : '' }}">
                    <a href="{{ route('users.index') }}">
                        <i class="fa fa-users"></i>
                        <span class="nav-label">Quản Lý Người Dùng</span>
                        <span class="fa arrow"></span>
                    </a>
                    <ul class="nav nav-second-level">
                        <li class="{{ Request::is('admin/users') ? 'active' : '' }}">
                            <a href="{{ route('users.index') }}">
                                <i class="fa fa-list"></i> Danh Sách Người Dùng
                            </a>
                        </li>

                        <li class="{{ Request::is('admin/staff') ? 'active' : '' }}">
                            <a href="{{ route('staff.index') }}">
                                <i class="fa fa-briefcase"></i> Danh Sách Nhân Viên
                            </a>
                        </li>

                        <li class="{{ Request::is('admin/roleDetails') ? 'active' : '' }}">
                            <a href="{{ route('roleDetail.index') }}">
                                <i class="fa fa-shield"></i> Phân Quyền Tài Khoản
                            </a>
                        </li>
                    </ul>
                </li>
            @endcan

            @can('manage posts')
            <!-- Quản Lý Tin Tức -->
            <li class="{{ Request::is('admin/posts*') || Request::is('admin/PostCategories') ? 'active' : '' }}">
                <a href="#">
                    <i class="fa fa-newspaper-o"></i> <span class="nav-label">Quản Lý Bài Viết</span>
                    <span class="fa arrow"></span>
                </a>
                <ul class="nav nav-second-level">
                    <li class="{{ Request::is('admin/PostCategories') ? 'active' : '' }}">
                        <a href="{{ route('postCategories.index') }}"><i class="fa fa-list"></i> Loại Bài Viết</a>
                    </li>
                    <li class="{{ Request::is('admin/posts') ? 'active' : '' }}">
                        <a href="{{ route('posts.index') }}"><i class="fa fa-list"></i> Danh Sách Bài Viết</a>
                    </li>
                    <li class="{{ Request::is('admin/posts/create') ? 'active' : '' }}">
                        <a href="{{ route('posts.create') }}"><i class="fa fa-plus-circle"></i> Thêm Bài Viết</a>
                    </li>
                </ul>
            </li>
            @endcan

            @can('manage products')
            <!-- Quản Lý Sản Phẩm -->
            <li class="{{ Request::is('admin/products*') || Request::is('admin/ProductCategories') ? 'active' : '' }}">
                <a href="#"><i class="fa fa-cutlery"></i> <span class="nav-label">Quản Lý Sản Phẩm</span> <span class="fa arrow"></span></a>
                <ul class="nav nav-second-level">
                    <li class="{{ Request::is('admin/ProductCategories') ? 'active' : '' }}">
                        <a href="{{ route('ProductCategories.index') }}"><i class="fa fa-list"></i> Loại Sản Phẩm</a>
                    </li>
                    <li class="{{ Request::is('admin/products') ? 'active' : '' }}">
                        <a href="{{ route('products.index') }}"><i class="fa fa-list"></i> Danh Sách Sản Phẩm</a>
                    </li>
                    <li class="{{ Request::is('admin/products/create') ? 'active' : '' }}">
                        <a href="{{ route('products.create') }}"><i class="fa fa-plus-circle"></i> Thêm Sản Phẩm</a>
                    </li>
                </ul>
            </li>
            @endcan

            @can('manage tables')
            <li class="{{ Request::is('admin/tables*') || Request::is('admin/reservations*') ? 'active' : '' }}">
                <a href="#"><i class="fa fa-table"></i> <span class="nav-label">Quản Lý Bàn & Đặt Bàn</span> <span class="fa arrow"></span></a>
                <ul class="nav nav-second-level">
                    <!-- Quản lý Bàn -->
                    <li class="{{ Request::is('admin/tables') ? 'active' : '' }}">
                        <a href="{{ route('tables.index') }}"><i class="fa fa-list"></i> Danh Sách Bàn</a>
                    </li>
                    <!-- Quản lý Đặt Bàn -->
                    <li class="{{ Request::is('admin/reservations') ? 'active' : '' }}">
                        <a href="{{ route('reservations.index') }}"><i class="fa fa-calendar"></i> Danh Sách Đặt Bàn</a>
                    </li>
                    <li class="{{ Request::is('admin/reservations/create') ? 'active' : '' }}">
                        <a href="{{ route('reservations.create') }}"><i class="fa fa-plus-circle"></i> Thêm Đặt Bàn</a>
                    </li>
                </ul>
            </li>
            @endcan

            @can('manage orders')
            <li class="{{ Request::is('admin/orders*') || Request::is('admin/orders/*/items*') ? 'active' : '' }}">
                <a href="#"><i class="fa fa-shopping-cart"></i> <span class="nav-label">Quản Lý Đơn Hàng</span> <span class="fa arrow"></span></a>
                <ul class="nav nav-second-level">
                    <!-- Quản lý Đơn Hàng -->
                    <li class="{{ Request::is('admin/orders') ? 'active' : '' }}">
                        <a href="{{ route('orders.index') }}"><i class="fa fa-list"></i> Danh Sách Đơn Hàng</a>
                    </li>
                    <!-- Quản lý Mục Đơn Hàng -->
                    @isset($orderId)
                        <li class="{{ Request::is("admin/orders/{$orderId}/items") ? 'active' : '' }}">
                            <a href="{{ route('order_items.index', ['orderId' => $orderId]) }}"><i class="fa fa-list-alt"></i> Danh Sách Mục Đơn Hàng</a>
                        </li>
                        <li class="{{ Request::is("admin/orders/{$orderId}/items/create") ? 'active' : '' }}">
                            <a href="{{ route('order_items.create', ['orderId' => $orderId]) }}"><i class="fa fa-plus-circle"></i> Thêm Mục Đơn Hàng</a>
                        </li>
                    @endisset
                </ul>
            </li>
            @endcan

            @can('manage coupons')
            <li class="{{ Request::is('admin/coupons*') || Request::is('admin/discount_promotions*') ? 'active' : '' }}">
                <a href="#"><i class="fa fa-tags"></i> <span class="nav-label">Quản Lý Coupons & Khuyến Mãi</span> <span class="fa arrow"></span></a>
                <ul class="nav nav-second-level">
                    <!-- Quản lý Coupons -->
                    <li class="{{ Request::is('admin/coupons') ? 'active' : '' }}">
                        <a href="{{ route('coupons.index') }}"><i class="fa fa-list"></i> Danh Sách Coupons</a>
                    </li>

                </ul>
            </li>
            @endcan

            @can('manage payments')
            <li class="{{ Request::is('admin/payments*') || Request::is('admin/payment_methods*') ? 'active' : '' }}">
                <a href="#"><i class="fa fa-credit-card"></i> <span class="nav-label">Quản Lý Thanh Toán</span> <span class="fa arrow"></span></a>
                <ul class="nav nav-second-level">
                    <!-- Quản lý Danh Sách Thanh Toán -->
                    <li class="{{ Request::is('admin/payments') ? 'active' : '' }}">
                        <a href="{{ route('payments.index') }}"><i class="fa fa-list"></i> Danh Sách Thanh Toán</a>
                    </li>

                    <!-- Quản lý Phương Thức Thanh Toán -->
                    <li class="{{ Request::is('admin/payment_methods') ? 'active' : '' }}">
                        <a href="{{ route('payment_methods.index') }}"><i class="fa fa-list-alt"></i>Phương Thức Thanh Toán</a>
                    </li>

                </ul>
            </li>
            @endcan


        </ul>
    </div>
</nav>
