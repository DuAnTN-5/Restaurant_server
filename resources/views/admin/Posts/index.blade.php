@extends('admin.dashboard.layoutadmin')

@section('content')
<div class="row wrapper border-bottom white-bg page-heading">
    @flasher_render
    <div class="col-lg-10">
        <h2>Danh Sách Bài Viết</h2>
        <ol class="breadcrumb">
            <li>
                <a href="{{ route('admin.index') }}">Trang Chủ</a>
            </li>
            <li>
                <a>Quản Lý Bài Viết</a>
            </li>
            <li class="active">
                <strong>Danh Sách Bài Viết</strong>
            </li>
        </ol>
    </div>
    <div class="col-lg-2 text-right">
        <!-- Nút Xem Danh Sách Bài Viết Đã Xóa -->
        <a href="{{ route('posts.trashed') }}" class="btn btn-warning" style="margin-top: 20px; margin-left: 10px;">Xem Danh Sách Đã Xóa</a>
    </div>
</div>

@if ($errors->has('error'))
    <div class="alert alert-danger">{{ $errors->first('error') }}</div>
@endif

<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>Danh Sách Bài Viết</h5>
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
                        <!-- Include filter form -->
                        @include('admin.posts.component.filter')

                        <!-- Include table content -->
                        @include('admin.posts.component.table')

                        <!-- Include pagination -->
                        @include('admin.posts.component.paginate')
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

{{-- @push('scripts')
    @include('admin.posts.component.script')
@endpush --}}
