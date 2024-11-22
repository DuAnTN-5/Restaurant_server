{{-- resources/views/admin/staff/edit.blade.php --}}
@extends('admin.dashboard.layoutadmin')

@section('content')
<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
        <h2>Chỉnh Sửa Nhân Viên</h2>
        <ol class="breadcrumb">
            <li><a href="{{ route('admin.index') }}">Trang Chủ</a></li>
            <li><a href="{{ route('staff.index') }}">Quản Lý Nhân Viên</a></li>
            <li class="active"><strong>Chỉnh Sửa Nhân Viên</strong></li>
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
                <div class="ibox-title">
                    <h5>Chỉnh Sửa Nhân Viên</h5>
                </div>
                <div class="ibox-content">
                    <form action="{{ route('staffs.update', $staff->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <!-- Các trường của form -->
                        <div class="form-group">
                            <label for="name">Tên</label>
                            <input type="text" class="form-control" id="name" name="name" value="{{ $staff->user->name }}" required>
                        </div>

                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" class="form-control" id="email" name="email" value="{{ $staff->user->email }}" required>
                        </div>

                        <!-- Các trường bổ sung cho Staff -->
                        <div class="form-group">
                            <label for="department">Phòng Ban</label>
                            <input type="text" class="form-control" id="department" name="department" value="{{ $staff->department }}">
                        </div>

                        <div class="form-group">
                            <label for="position">Chức Vụ</label>
                            <input type="text" class="form-control" id="position" name="position" value="{{ $staff->position }}">
                        </div>

                        <button type="submit" class="btn btn-primary">Cập Nhật</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
