@extends('admin.dashboard.layoutadmin')

@section('content')
    <div class="row wrapper border-bottom white-bg page-heading">
        <div class="col-lg-10">
            <h2>Danh Sách Loại Sản Phẩm</h2>
            <ol class="breadcrumb">
                <li><a href="{{ route('admin.index') }}">Trang Chủ</a></li>
                <li><a>Quản Lý Loại Sản Phẩm</a></li>
                <li class="active"><strong>Danh Sách Loại Sản Phẩm</strong></li>
            </ol>
        </div>
        <div class="col-lg-2 text-right">
            <a href="{{ route('ProductCategories.trashed') }}" class="btn btn-primary" style="margin-top: 20px;">Xem Danh Sách Đã Xóa</a>
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
                        <h5>Danh Sách Loại Sản Phẩm</h5>
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
                            @include('admin.ProductCategories.component.filter')

                            <!-- Bao gồm bảng dữ liệu -->
                            @include('admin.ProductCategories.component.table')

                            <!-- Bao gồm phân trang -->
                            @include('admin.ProductCategories.component.paginate')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

{{-- @push('scripts')
    <!-- Bao gồm script thay đổi trạng thái -->
    @include('admin.productcategories.component.script')
@endpush --}}
