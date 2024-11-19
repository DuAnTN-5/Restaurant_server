@extends('admin.dashboard.layoutadmin')

@section('content')
<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
        <h2>Thêm Mới Mục Đơn Hàng</h2>
        <ol class="breadcrumb">
            <li><a href="{{ route('admin.index') }}">Trang Chủ</a></li>
            <li><a href="{{ route('orders.index') }}">Quản Lý Đơn Hàng</a></li>
            <li><a href="{{ route('order_items.index', ['orderId' => $orderId]) }}">Danh Sách Mục Đơn Hàng</a></li>
            <li class="active"><strong>Thêm Mới</strong></li>
        </ol>
    </div>
</div>

<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>Thêm Mới Mục Đơn Hàng</h5>
                </div>
                <div class="ibox-content">
                    @include('admin.order_items.component.form', ['orderId' => $orderId])
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
