@extends('admin.dashboard.layoutadmin') <!-- Kế thừa layout nếu có -->

@section('content')
<div class="wrapper wrapper-content">
    <div class="row">
        <div class="col-lg-2">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <span class="label label-success pull-right">Total</span>
                    <h5>Total Users</h5>
                </div>
                <div class="ibox-content">
                    <h1 class="no-margins">{{ $totalUsers }}</h1> <!-- Hiển thị tổng số người dùng -->
                    <div class="stat-percent font-bold text-success">100% <i class="fa fa-users"></i></div>
                    <small>Total users</small>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
