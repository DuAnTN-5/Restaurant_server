@extends('admin.dashboard.layoutadmin')

@section('content')
    <div class="row wrapper border-bottom white-bg page-heading">
        <div class="col-lg-10">
            <h2>Danh Sách Đặt Chỗ</h2>
            <ol class="breadcrumb">
                <li><a href="{{ route('admin.index') }}">Trang Chủ</a></li>
                <li><a>Quản Lý Đặt Chỗ</a></li>
                <li class="active"><strong>Danh Sách Đặt Chỗ</strong></li>
            </ol>
        </div>
        <div class="col-lg-2 text-right">
            <a href="{{ route('reservations.create') }}" class="btn btn-primary" style="margin-top: 20px;">Thêm Đặt Chỗ Mới</a>
        </div>
    </div>

    @if ($errors->has('error'))
        <div class="alert alert-danger">{{ $errors->first('error') }}</div>
    @endif

    <div class="wrapper wrapper-content animated fadeInRight">
        @flasher_render
        <div class="row">
            <div class="col-lg-12">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5>Danh Sách Đặt Chỗ</h5>
                        <div class="ibox-tools">
                            <a class="collapse-link">
                                <i class="fa fa-chevron-up"></i>
                            </a>
                            <a class="close-link">
                                <i class="fa fa-times"></i>
                            </a>
                        </div>
                    </div>
                    <div class="ibox-content">
                        <div class="table-responsive">
                            <!-- Bao gồm form tìm kiếm -->
                            @include('admin.reservations.component.filter')

                            <!-- Bao gồm bảng dữ liệu -->
                            @include('admin.reservations.component.table')

                            <!-- Bao gồm phân trang -->
                            @include('admin.reservations.component.paginate')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <!-- Bao gồm script thay đổi trạng thái -->
    @include('admin.reservations.component.script')
@endpush
