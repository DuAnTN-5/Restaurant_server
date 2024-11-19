{{-- resources/views/admin/staff/create.blade.php --}}
@extends('admin.dashboard.layoutadmin')

@section('content')
<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
        <h2>Tạo Mới Nhân Viên</h2>
        <ol class="breadcrumb">
            <li><a href="{{ route('admin.index') }}">Trang Chủ</a></li>
            <li><a href="{{ route('staff.index') }}">Quản Lý Nhân Viên</a></li>
            <li class="active"><strong>Tạo Mới Nhân Viên</strong></li>
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
                    <h5>Tạo Mới Nhân Viên</h5>
                </div>
                <div class="ibox-content">
                    <form action="{{ route('staff.store') }}" method="POST">
                        @csrf

                        <!-- Các trường của form -->
                        <div class="form-group">
                            <label for="name">Tên</label>
                            <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}" required>
                        </div>

                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}" required>
                        </div>

                        <!-- Các trường bổ sung cho Staff -->
                        <div class="form-group">
                            <label for="department">Phòng Ban</label>
                            <input type="text" class="form-control" id="department" name="department" value="{{ old('department') }}">
                        </div>

                        <div class="form-group">
                            <label for="position">Chức Vụ</label>
                            <input type="text" class="form-control" id="position" name="position" value="{{ old('position') }}">
                        </div>

                        <button type="submit" class="btn btn-primary">Tạo Mới</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
