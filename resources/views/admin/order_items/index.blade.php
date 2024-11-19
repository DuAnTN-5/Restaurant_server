@extends('admin.dashboard.layoutadmin')

@section('content')
    <div class="row wrapper border-bottom white-bg page-heading">
        <div class="col-lg-10">
            <h2>Danh Sách Mục Đơn Hàng</h2>
            <ol class="breadcrumb">
                <li>
                    <a href="{{ route('admin.index') }}">Trang Chủ</a>
                </li>
                <li>
                    <a href="{{ route('orders.index') }}">Danh Sách Đơn Hàng</a>
                </li>
                <li class="active">
                    <strong>Danh Sách Mục Đơn Hàng</strong>
                </li>
            </ol>
        </div>
        <div class="col-lg-2 text-right">
            <a href="{{ route('order_items.create', ['orderId' => $orderId]) }}" class="btn btn-primary" style="margin-top: 20px;">Thêm Mục Đơn Hàng</a>
        </div>
    </div>

    <div class="wrapper wrapper-content animated fadeInRight">
        <div class="row">
            <div class="col-lg-12">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5>Danh Sách Mục Đơn Hàng</h5>
                    </div>
                    <div class="ibox-content">
                        <!-- Form tìm kiếm và lọc các mục trong đơn hàng -->
                        @include('admin.order_items.component.filter', ['orderId' => $orderId]) <!-- Form tìm kiếm -->

                        @include('admin.order_items.component.table', ['orderId' => $orderId, 'items' => $items]) <!-- Bảng hiển thị các mục trong đơn hàng -->

                        <!-- Phân trang -->
                        @include('admin.order_items.component.paginate', ['items' => $items]) <!-- Phân trang -->
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    @include('admin.order_items.component.script', ['orderId' => $orderId]) <!-- Script cho mục đơn hàng -->
@endpush
