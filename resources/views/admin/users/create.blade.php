@extends('admin.dashboard.layoutadmin')

@section('content')
    <div class="row wrapper border-bottom white-bg page-heading">
        <div class="col-lg-10">
            <h2>Thêm Người Dùng Mới</h2>
            <ol class="breadcrumb">
                <li><a href="{{ route('admin.index') }}">Trang Chủ</a></li>
                <li><a>Quản Lý Người Dùng</a></li>
                <li class="active"><strong>Thêm Mới</strong></li>
            </ol>
        </div>
    </div>

    <div class="wrapper wrapper-content animated fadeInRight">
        @include('admin.users.component.form') <!-- Sử dụng form chung -->
    </div>
@endsection
