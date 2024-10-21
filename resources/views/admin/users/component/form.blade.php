<form method="POST" action="{{ isset($user) ? route('users.update', $user->id) : route('users.store') }}" enctype="multipart/form-data">
    @csrf
    @if (isset($user))
        @method('PUT')
    @endif

    <!-- Thông Tin Chung -->
    <div class="row">
        <div class="col-lg-5">
            <div class="panel-head">
                <div class="panel-title">
                    <h3>Thông Tin Chung</h3>
                </div>
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
                    <!-- Email and Name Row -->
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="email">Email <span class="text-danger">(*)</span></label>
                                <input type="email" name="email" id="email" class="form-control" value="{{ old('email', $user->email ?? '') }}" {{ isset($user) ? 'readonly' : 'required' }}>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="name">Họ Tên <span class="text-danger">(*)</span></label>
                                <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $user->name ?? '') }}" required>
                            </div>
                        </div>
                    </div>

                    <!-- Password and Password Confirmation Row -->
                    @if (!isset($user))
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="password">Mật Khẩu <span class="text-danger">(*)</span></label>
                                <input type="password" name="password" id="password" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="password_confirmation">Xác Nhận Mật Khẩu <span class="text-danger">(*)</span></label>
                                <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" required>
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Date of Birth and Gender Row -->
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="date_of_birth">Ngày Sinh</label>
                                <input type="date" name="date_of_birth" id="date_of_birth" class="form-control" value="{{ old('date_of_birth', $user->date_of_birth ?? '') }}">
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="sex">Giới Tính</label>
                                <select name="sex" class="form-control">
                                    <option value="Nam" {{ old('sex', $user->sex ?? '') == 'Nam' ? 'selected' : '' }}>Nam</option>
                                    <option value="Nữ" {{ old('sex', $user->sex ?? '') == 'Nữ' ? 'selected' : '' }}>Nữ</option>
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
                                @if (isset($user) && $user->image)
                                    <img src="{{ asset($user->image) }}" height="64" alt="Current Image">
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Phân Quyền -->
    <div class="row">
        <div class="col-lg-5">
            <div class="panel-head">
                <div class="panel-title">
                    <h3>Phân Quyền</h3>
                </div>
                <div class="panel-description">
                    <p>Chọn quyền hạn của người dùng</p>
                </div>
            </div>
        </div>
        <div class="col-lg-7">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>Phân quyền</h5>
                </div>
                <div class="ibox-content">
                    <div class="form-group">
                        <label for="role">Quyền hạn <span class="text-danger">(*)</span></label>
                        <select name="role" id="role" class="form-control" required>
                            <option value="" disabled {{ old('role', $user->role ?? '') == '' ? 'selected' : '' }}>Chọn quyền hạn</option>
                            <option value="1" {{ old('role', $user->role ?? '') == 1 ? 'selected' : '' }}>Admin</option>
                            <option value="0" {{ old('role', $user->role ?? '') == 0 ? 'selected' : '' }}>User</option>
                            <option value="2" {{ old('role', $user->role ?? '') == 2 ? 'selected' : '' }}>Manager</option>
                            <option value="3" {{ old('role', $user->role ?? '') == 3 ? 'selected' : '' }}>Staff</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
        
    </div>

    <!-- Thông Tin Liên Hệ -->
    <div class="row">
        <div class="col-lg-5">
            <div class="panel-head">
                <div class="panel-title">
                    <h3>Thông Tin Liên Hệ</h3>
                </div>
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
                    <!-- Province, District, Ward, and Address -->
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="province_code">Mã Tỉnh/Thành Phố</label>
                                <input type="text" name="province_code" id="province_code" class="form-control" value="{{ old('province_code', $user->province_code ?? '') }}">
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="district_code">Mã Quận/Huyện</label>
                                <input type="text" name="district_code" id="district_code" class="form-control" value="{{ old('district_code', $user->district_code ?? '') }}">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="ward_code">Mã Phường/Xã</label>
                                <input type="text" name="ward_code" id="ward_code" class="form-control" value="{{ old('ward_code', $user->ward_code ?? '') }}">
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="address">Địa chỉ</label>
                                <input type="text" name="address" id="address" class="form-control" value="{{ old('address', $user->address ?? '') }}">
                            </div>
                        </div>
                    </div>

                    <!-- Phone Number -->
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="phone_number">Số điện thoại</label>
                                <input type="text" name="phone_number" id="phone_number" class="form-control" value="{{ old('phone_number', $user->phone_number ?? '') }}">
                            </div>
                        </div>
                        
                    </div>
                    <!-- Facebook and Google -->
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="facebook">Facebook URL</label>
                                <input type="url" name="facebook" id="facebook" class="form-control" placeholder="Nhập đường dẫn Facebook của bạn" value="{{ old('facebook', $user->facebook ?? '') }}">
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="google">Google URL</label>
                                <input type="url" name="google" id="google" class="form-control" placeholder="Nhập đường dẫn Google của bạn" value="{{ old('google', $user->google ?? '') }}">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Submit Button -->
    <div class="row">
        <div class="col-lg-12 text-right">
            <button type="submit" class="btn btn-primary">{{ isset($user) ? 'Cập nhật' : 'Thêm mới' }}</button>
        </div>
    </div>
</form>
