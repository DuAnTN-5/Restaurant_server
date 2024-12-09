@extends('admin.dashboard.layoutadmin') <!-- Kế thừa layout nếu có -->

@section('content')
    <div class="wrapper wrapper-content">
        <div class="row">
            <div class="col-lg-3">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <span class="label label-success pull-right">Tổng</span>
                        <h5>Tổng số người dùng</h5>
                    </div>
                    <div class="ibox-content">
                        <h1 class="no-margins">{{ $totalUsers }}</h1> <!-- Hiển thị tổng số người dùng -->
                        <div class="stat-percent font-bold text-success">100% <i class="fa fa-users"></i></div>
                        <small>Tổng số người dùng</small>
                    </div>
                </div>
            </div>
            <div class="col-lg-3">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <span class="label label-warning pull-right">Tổng</span>
                        <h5> User mới trong tháng</h5>
                    </div>
                    <div class="ibox-content">
                        <h1 class="no-margins">{{ $newUsersThisMonth }}</h1> <!-- Hiển thị tổng số khách hàng mới -->
                        <div class="stat-percent font-bold text-warning">100% <i class="fa fa-user-plus"></i></div>
                        <small>Khách hàng mới trong tháng này</small>
                    </div>
                </div>
            </div>
            <div class="col-lg-3">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <span class="label label-primary pull-right">Tổng</span>
                        <h5>Tổng số đơn hàng</h5>
                    </div>
                    <div class="ibox-content">
                        <h1 class="no-margins">{{ $totalOrders }}</h1> <!-- Hiển thị tổng số đơn hàng -->
                        <div class="stat-percent font-bold text-primary">100% <i class="fa fa-shopping-cart"></i></div>
                        <small>Tổng số đơn hàng</small>
                    </div>
                </div>
            </div>
            <div class="col-lg-3">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <span class="label label-info pull-right">Tháng này</span>
                        <h5>Doanh thu tháng</h5>
                    </div>
                    <div class="ibox-content">
                        <h1 class="no-margins">{{ $monthlyRevenue }}</h1> <!-- Hiển thị tổng doanh thu tháng -->
                        <div class="stat-percent font-bold text-info">100% <i class="fa fa-dollar"></i></div>
                        <small>Doanh thu tháng này</small>
                    </div>
                </div>
            </div>
        </div>
        {{-- <div class="row">
            <div class="col-lg-12">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5>Đơn hàng</h5>
                        <div class="pull-right">
                            <div class="btn-group">
                                <button type="button" class="btn btn-xs btn-white active">Hôm nay</button>
                                <button type="button" class="btn btn-xs btn-white">Theo tháng</button>
                                <button type="button" class="btn btn-xs btn-white">Theo năm</button>
                            </div>
                        </div>
                    </div>
                    <div class="ibox-content">
                        <div class="row">
                            <div class="col-lg-9">
                                <canvas id="ordersRevenueChart" width="400" height="150"></canvas>
                                <!-- Canvas chứa biểu đồ -->
                            </div>
                            <div class="col-lg-3">
                                <ul class="stat-list">
                                    <li>
                                        <h2 class="no-margins">2,346</h2>
                                        <small>Tổng số đơn hàng trong kỳ</small>
                                        <div class="stat-percent">48% <i class="fa fa-level-up text-navy"></i></div>
                                        <div class="progress progress-mini">
                                            <div style="width: 48%;" class="progress-bar"></div>
                                        </div>
                                    </li>
                                    <li>
                                        <h2 class="no-margins ">4,422</h2>
                                        <small>Đơn hàng trong tháng trước</small>
                                        <div class="stat-percent">60% <i class="fa fa-level-down text-navy"></i></div>
                                        <div class="progress progress-mini">
                                            <div style="width: 60%;" class="progress-bar"></div>
                                        </div>
                                    </li>
                                    <li>
                                        <h2 class="no-margins ">9,180</h2>
                                        <small>Doanh thu hàng tháng từ đơn hàng</small>
                                        <div class="stat-percent">22% <i class="fa fa-bolt text-navy"></i></div>
                                        <div class="progress progress-mini">
                                            <div style="width: 22%;" class="progress-bar"></div>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div> --}}
        <div>
            <select name="" id="bieudo">
                <option value="/admin/thongketheongay" selected>Theo Ngày</option>
                <option value="/admin/thongketheothang">Theo tháng</option>
                <option value="/admin/thongketheonam">Theo năm</option>
            </select>
            <canvas id="myChart"></canvas>

        </div>
        

        <div class="row">
            <!-- Cột Danh sách mã giảm giá -->
            <div class="col-lg-6 col-md-6 col-sm-12">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h3 class="text-info">Danh sách mã giảm giá</h3>
                    </div>
                    <div class="ibox-content">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Mã</th>
                                    <th>Giá trị (%)</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($coupons as $coupon)
                                    <tr>
                                        <td>{{ $coupon->code }}</td>
                                        <td>{{ $coupon->value }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Cột Top 10 sản phẩm bán chạy -->
            <div class="col-lg-6 col-md-6 col-sm-12">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <ul class="nav nav-tabs">
                            <li class="active"><a data-toggle="tab" href="#day">Ngày</a></li>
                            <li><a data-toggle="tab" href="#week">Tuần</a></li>
                            <li><a data-toggle="tab" href="#month">Tháng</a></li>
                            <li><a data-toggle="tab" href="#year">Năm</a></li>
                        </ul>
                    </div>
                    <div class="ibox-content">
                        <div class="tab-content">
                            <!-- Tab Ngày -->
                            <div id="day" class="tab-pane fade in active">
                                <h3 class="text-danger">Top 5 sản phẩm bán chạy trong ngày</h3>
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Tên</th>
                                            <th>Số lượng</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($topProductsDay->take(5) as $product)
                                            <tr>
                                                <td>{{ $product->product->name }}</td> <!-- Hiển thị tên sản phẩm -->
                                                <td>{{ $product->quantity }}</td> <!-- Hiển thị số lượng bán -->
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <!-- Tab Tuần -->
                            <div id="week" class="tab-pane fade">
                                <h3 class="text-danger">Top 5 sản phẩm bán chạy trong tuần</h3>
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Tên</th>
                                            <th>Số lượng</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($topProductsWeek->take(5) as $product)
                                            <tr>
                                                <td>{{ $product->product->name }}</td> <!-- Hiển thị tên sản phẩm -->
                                                <td>{{ $product->quantity }}</td> <!-- Hiển thị số lượng bán -->
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <!-- Tab Tháng -->
                            <div id="month" class="tab-pane fade">
                                <h3 class="text-danger">Top 5 sản phẩm bán chạy trong tháng</h3>
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Tên</th>
                                            <th>Số lượng</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($topProductsMonth->take(5) as $product)
                                            <tr>
                                                <td>{{ $product->product->name }}</td> <!-- Hiển thị tên sản phẩm -->
                                                <td>{{ $product->quantity }}</td> <!-- Hiển thị số lượng bán -->
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <!-- Tab Năm -->
                            <div id="year" class="tab-pane fade">
                                <h3 class="text-danger">Top 5 sản phẩm bán chạy trong năm</h3>
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Tên</th>
                                            <th>Số lượng</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($topProductsYear->take(5) as $product)
                                            <tr>
                                                <td>{{ $product->product->name }}</td> <!-- Hiển thị tên sản phẩm -->
                                                <td>{{ $product->quantity }}</td> <!-- Hiển thị số lượng bán -->
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
<script>
    @include('admin.dashboard.component.script')
</script>