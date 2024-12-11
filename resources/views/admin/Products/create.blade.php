@extends('admin.dashboard.layoutadmin')

@section('content')
<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
        <h2>Thêm Mới Sản Phẩm</h2>
        <ol class="breadcrumb">
            <li><a href="{{ route('admin.index') }}">Trang Chủ</a></li>
            <li><a>Quản Lý Sản Phẩm</a></li>
            <li class="active"><strong>Thêm Mới</strong></li>
        </ol>
    </div>
</div>

<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>Thêm Mới Sản Phẩm</h5>
                </div>
                <div class="ibox-content">
                    @include('admin.Products.component.form')
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@push('scripts')
    <!-- Bao gồm script thay đổi trạng thái -->
    @include('admin.products.component.script')
@endpush