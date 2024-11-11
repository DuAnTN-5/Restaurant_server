@extends('admin.dashboard.layoutadmin')

@section('content')
    <div class="row wrapper border-bottom white-bg page-heading">
        <div class="col-lg-10">
            <h2>Chỉnh Sửa Coupon</h2>
            <ol class="breadcrumb">
                <li><a href="{{ route('admin.index') }}">Trang Chủ</a></li>
                <li><a href="{{ route('coupons.index') }}">Danh Sách Coupon</a></li>
                <li class="active"><strong>Chỉnh Sửa Coupon</strong></li>
            </ol>
        </div>
    </div>

    <div class="wrapper wrapper-content animated fadeInRight">
        <div class="row">
            <div class="col-lg-12">
                <div class="ibox float-e-margins">
                    <div class="ibox-title"><h5>Chỉnh Sửa Coupon</h5></div>
                    <div class="ibox-content">
                        <!-- Hiển thị lỗi nếu có -->
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <!-- Include form chỉnh sửa coupon -->
                        @include('admin.coupons.component.form', ['coupon' => $coupon])
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
