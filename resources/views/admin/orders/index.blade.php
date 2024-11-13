@extends('admin.dashboard.layoutadmin')

@section('content')
    <div class="row wrapper border-bottom white-bg page-heading">
        <div class="col-lg-10">
            <h2>Danh Sách Đơn Hàng</h2>
            <ol class="breadcrumb">
                <li>
                    <a href="{{ route('admin.index') }}">Trang Chủ</a>
                </li>
                <li class="active">
                    <strong>Danh Sách Đơn Hàng</strong>
                </li>
            </ol>
        </div>
        <div class="col-lg-2 text-right">
            <a href="{{ route('orders.create') }}" class="btn btn-primary" style="margin-top: 20px;">Tạo Đơn Hàng</a>
        </div>
    </div>

    <div class="wrapper wrapper-content animated fadeInRight">
        <div class="row">
            <div class="col-lg-12">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5>Danh Sách Đơn Hàng</h5>
                    </div>
                    <div class="ibox-content">
                        <!-- Form tìm kiếm và lọc đơn hàng -->
                        @include('admin.orders.component.filter') <!-- Form tìm kiếm -->

                        @include('admin.orders.component.table') <!-- Bảng hiển thị đơn hàng -->

                        <!-- Phân trang -->
                        @include('admin.orders.component.paginate') <!-- Phân trang -->
                        @include('admin.orders.component.modal')

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

{{-- @push('scripts')
    @include('admin.orders.component.script') <!-- Script cho đơn hàng -->
@endpush --}}
