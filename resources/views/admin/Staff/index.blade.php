{{-- resources/views/admin/staff/index.blade.php --}}
@extends('admin.dashboard.layoutadmin')

@section('content')
<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
        <h2>Quản Lý Nhân Viên</h2>
        <ol class="breadcrumb">
            <li><a href="{{ route('admin.index') }}">Trang Chủ</a></li>
            <li class="active"><strong>Danh Sách Nhân Viên</strong></li>
        </ol>
    </div>
</div>

    <div class="wrapper wrapper-content animated fadeInRight">
        <div class="row">
            <div class="col-sm-8">
                <div class="ibox">
                    @include('admin.Staff.component.table')
                </div>
            </div>
            <div class="col-sm-4">
                <div class="ibox">
                    @include('admin.Staff.component.detail')
                </div>
            </div>
        </div>
    </div>
@endsection

{{-- @section('scripts')
    @include('admin.staff.styles')
@endsection --}}
