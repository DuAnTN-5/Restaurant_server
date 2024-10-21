@extends('admin.dashboard.layoutadmin')
@section('content')
<style>
    .h3{
        color:red;
    }
</style>
<div class="row wrapper border-bottom white-bg page-heading">
    @flasher_render
    <div class="col-lg-10">
        <h2>Chỉnh Sửa Người Dùng</h2>
        <ol class="breadcrumb">
            <li><a href="{{ route('admin.index') }}">Trang Chủ</a></li>
            <li><a>Quản Lý Người Dùng</a></li>
            <li class="active"><strong>Chỉnh Sửa</strong></li>
        </ol>
    </div>
</div>

<div class="wrapper wrapper-content animated fadeInRight">
    <!-- General Information -->
    {{-- <div class="row">
        <div class="col-lg-5">
            <div class="panel-head">
                <div class="panel-title"><h3>Thông Tin Chung</h3></div>
                <div class="panel-description">
                    <p>Chỉnh sửa thông tin của người dùng</p>
                    <p>Lưu ý: Những trường đánh dấu <span class="text-danger">(*)</span> là bắt buộc</p>
                </div>
            </div>
        </div>
        <div class="col-lg-7">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>Thông tin chung</h5>
                </div>
                <div class="ibox-content">
                    <form method="POST" action="{{ route('users.update', $user->id) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <!-- Email and Name Row -->
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="email">Email <span class="text-danger">(*)</span></label>
                                    <input type="email" name="email" id="email" class="form-control" value="{{ $user->email }}" readonly>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="name">Họ Tên <span class="text-danger">(*)</span></label>
                                    <input type="text" name="name" id="name" class="form-control" value="{{ $user->name }}" required>
                                </div>
                            </div>
                        </div>

                        <!-- Date of Birth and Gender Row -->
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="date_of_birth">Ngày Sinh</label>
                                    <input type="date" name="date_of_birth" id="date_of_birth" class="form-control" value="{{ $user->date_of_birth }}">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="sex">Giới Tính</label>
                                    <select name="sex" class="form-control">
                                        <option value="Nam" {{ $user->sex == 'Nam' ? 'selected' : '' }}>Nam</option>
                                        <option value="Nữ" {{ $user->sex == 'Nữ' ? 'selected' : '' }}>Nữ</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Avatar Upload Row -->
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label for="image">Ảnh đại diện</label>
                                    <input type="file" name="image" id="image" class="form-control">
                                    @if ($user->image)
                                        <img src="{{ asset($user->image) }}" height="64" alt="Current Image">
                                    @endif
                                </div>
                            </div>
                        </div>
                </div>
            </div>
        </div>
    </div> --}}

    <!-- Contact Information -->
    {{-- <div class="row">
        <div class="col-lg-5">
            <div class="panel-head">
                <div class="panel-title"><h3>Thông Tin Liên Hệ</h3></div>
                <div class="panel-description">
                    <p>Nhập thông tin liên hệ của người sử dụng</p>
                </div>
            </div>
        </div>
        <div class="col-lg-7">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>Thông tin liên hệ</h5>
                </div>
                <div class="ibox-content">
                    <form method="POST" action="{{ route('users.update', $user->id) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
        
                        <div class="row">
                            <!-- Thành Phố -->
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="province_code">Mã Tỉnh/Thành Phố</label>
                                    <input type="text" name="province_code" id="province_code" class="form-control" value="{{ old('province_code') }}">
                                </div>
                            </div>
                            <!-- Quận/Huyện -->
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="district_code">Mã Quận/Huyện</label>
                                    <input type="text" name="district_code" id="district_code" class="form-control" value="{{ old('district_code') }}">
                                </div>
                            </div>
                        </div>
        
                        <!-- Phường/Xã và Địa chỉ -->
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="ward_code">Mã Phường/Xã</label>
                                    <input type="text" name="ward_code" id="ward_code" class="form-control" value="{{ old('ward_code') }}">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="address">Địa chỉ</label>
                                    <input type="text" name="address" id="address" class="form-control" value="{{ old('address') }}">
                                </div>
                            </div>
                        </div>
        
                        <!-- Số điện thoại -->
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="phone_number">Số điện thoại</label>
                                    <input type="text" name="phone_number" id="phone_number" class="form-control" value="{{ old('phone_number') }}">
                                </div>
                            </div>
                        </div>
        
                        <!-- Facebook ID và Google ID -->
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="facebook_id">Facebook ID</label>
                                    <input type="text" name="facebook_id" id="facebook_id" class="form-control" value="{{ old('facebook_id') }}">
                                    <!-- Hiển thị liên kết Facebook nếu có -->
                                    @if(old('facebook_id'))
                                        <a href="https://www.facebook.com/{{ old('facebook_id') }}" target="_blank">Xem tài khoản Facebook</a>
                                    @endif
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="google_id">Google ID</label>
                                    <input type="text" name="google_id" id="google_id" class="form-control" value="{{ old('google_id') }}">
                                    <!-- Chỉ hiển thị Google ID, không có URL -->
                                    @if(old('google_id'))
                                        <p>ID Google: {{ old('google_id') }}</p>
                                    @endif
                                </div>
                            </div>
                        </div>
        
                        <!-- Action Buttons -->
                        <div class="form-group text-right">
                            <button type="submit" class="btn btn-primary">Cập Nhật</button>
                            <a href="{{ route('users.index') }}" class="btn btn-secondary">Hủy</a>
                        </div>
                    </form> <!-- Form đóng ở đây -->
                </div>
            </div>
        </div>
        
    </div> --}}
        @include('admin.users.component.form') <!-- Sử dụng form chung -->
</div>
@endsection
