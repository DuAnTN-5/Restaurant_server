@extends('admin.dashboard.layoutadmin')

@section('content')
    <div class="row wrapper border-bottom white-bg page-heading">
        <div class="col-lg-10">
            <h2>Danh Sách Thanh Toán</h2>
            <ol class="breadcrumb">
                <li>
                    <a href="{{ route('admin.index') }}">Trang Chủ</a>
                </li>
                <li class="active">
                    <strong>Danh Sách Thanh Toán</strong>
                </li>
            </ol>
        </div>
        <div class="col-lg-2 text-right">
            <a href="{{ route('payments.create') }}" class="btn btn-primary" style="margin-top: 20px;">Tạo Thanh Toán</a>
        </div>
    </div>

    <div class="wrapper wrapper-content animated fadeInRight">
        <div class="row">
            <div class="col-lg-12">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5>Danh Sách Thanh Toán</h5>
                    </div>
                    <div class="ibox-content">
                        <!-- Form tìm kiếm và lọc thanh toán -->
                        {{-- @include('admin.payments.component.filter') <!-- Form tìm kiếm --> --}}

                        @include('admin.payments.component.table') <!-- Bảng hiển thị thanh toán -->

                        <!-- Phân trang -->
                        {{-- @include('admin.payments.component.paginate') <!-- Phân trang --> --}}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    {{-- @include('admin.payments.component.script') <!-- Script cho thanh toán --> --}}
@endpush
