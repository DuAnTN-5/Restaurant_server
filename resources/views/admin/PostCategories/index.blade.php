@extends('admin.dashboard.layoutadmin')

@section('content')
    <div class="row wrapper border-bottom white-bg page-heading">
        <div class="col-lg-10">
            <h2>Danh Sách Loại Bài Viết</h2>
            <ol class="breadcrumb">
                <li><a href="{{ route('admin.index') }}">Trang Chủ</a></li>
                <li><a>Quản Lý Loại</a></li>
                <li class="active"><strong>Danh Sách Loại Bài Viết</strong></li>
            </ol>
        </div>
        <div class="col-lg-2 text-right">
            <a href="{{ route('PostCategories.create') }}" class="btn btn-primary" style="margin-top: 20px;">Thêm Loại Bài Viết</a>
        </div>
    </div>

    <div class="wrapper wrapper-content animated fadeInRight">
        @flasher_render
        <div class="row">
            <div class="col-lg-12">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5>Danh Sách Loại Bài Viết</h5>
                        <div class="ibox-tools">
                            <a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                            <a class="close-link"><i class="fa fa-times"></i></a>
                        </div>
                    </div>
                    <div class="ibox-content">
                        <div class="table-responsive">
                            @include('admin.PostCategories.component.filter') <!-- Form tìm kiếm -->
                            @include('admin.PostCategories.component.table')  <!-- Bảng dữ liệu -->
                            @include('admin.PostCategories.component.paginate') <!-- Phân trang -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    @include('admin.PostCategories.component.script') <!-- Script xử lý trạng thái -->
@endpush
