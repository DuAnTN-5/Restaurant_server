@extends('admin.dashboard.layoutadmin')

@section('content')
<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
        <h2>Chỉnh Sửa Đặt Chỗ</h2>
        <ol class="breadcrumb">
            <li><a href="{{ route('admin.index') }}">Trang Chủ</a></li>
            <li><a>Quản Lý Đặt Chỗ</a></li>
            <li class="active"><strong>Chỉnh Sửa</strong></li>
        </ol>
    </div>
</div>

<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>Chỉnh Sửa Đặt Chỗ</h5>
                </div>
                <div class="ibox-content">
                    @include('admin.reservations.component.form', ['reservation' => $reservation])
                </div>
            </div>
        </div>
    </div>
</div>
@endsection