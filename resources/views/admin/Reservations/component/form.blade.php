<form method="POST" action="{{ isset($reservation) ? route('reservations.update', $reservation->id) : route('reservations.store') }}">
    @csrf
    @if (isset($reservation))
        @method('PUT')
    @endif

    <!-- Hiển thị thông báo lỗi nếu có -->
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="row">
        <!-- Phần bên trái - 8 cột -->
        <div class="col-lg-8">
            <div class="row">
                <!-- Tên Người Dùng -->
                <div class="form-group col-lg-12">
                    <label for="user_id">Tên Người Dùng:</label>
                    <select name="user_id" class="form-control" required>
                        <option value="">Chọn người dùng</option>
                        @foreach ($users as $user)
                            <option value="{{ $user->id }}" {{ old('user_id', $reservation->user_id ?? '') == $user->id ? 'selected' : '' }}>
                                {{ $user->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Tên Bàn -->
                <div class="form-group col-lg-12">
                    <label for="table_id">Tên Bàn:</label>
                    <select name="table_id" class="form-control" required>
                        <option value="">Chọn bàn</option>
                        @foreach ($tables as $table)
                            <option value="{{ $table->id }}" {{ old('table_id', $reservation->table_id ?? '') == $table->id ? 'selected' : '' }}>
                                Bàn {{ $table->number }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Ngày Đặt -->
                <div class="form-group col-lg-6">
                    <label for="reservation_date">Ngày Đặt & Giờ Đặt:</label>
                    <input type="datetime-local" name="reservation_date" class="form-control" 
                           value="{{ old('reservation_date', isset($reservation) ? $reservation->reservation_date->format('Y-m-d\TH:i:s') : '') }}" 
                           required>
                </div>
                
                <!-- Số Khách -->
                <div class="form-group col-lg-6">
                    <label for="guest_count">Số Khách:</label>
                    <input type="number" name="guest_count" class="form-control" value="{{ old('guest_count', $reservation->guest_count ?? '') }}" required>
                </div>
            </div>
        </div>

        <!-- Phần bên phải - 4 cột -->
        <div class="col-lg-4">
            <div class="row">
                <!-- Yêu Cầu Đặc Biệt -->
                <div class="form-group col-lg-12">
                    <label for="special_requests">Yêu Cầu Đặc Biệt:</label>
                    <textarea id="special_requests" name="special_requests" class="form-control">{{ old('special_requests', $reservation->special_requests ?? '') }}</textarea>
                </div>

                <!-- Trạng Thái -->
                <div class="form-group col-lg-12">
                    <label for="status">Trạng Thái:</label>
                    <select name="status" class="form-control" required>
                        <option value="reserved" {{ old('status', $reservation->status ?? '') == 'reserved' ? 'selected' : '' }}>Đặt Chỗ</option>
                        <option value="confirmed" {{ old('status', $reservation->status ?? '') == 'confirmed' ? 'selected' : '' }}>Xác Nhận</option>
                        <option value="canceled" {{ old('status', $reservation->status ?? '') == 'canceled' ? 'selected' : '' }}>Hủy</option>
                        <option value="pending" {{ old('status', $reservation->status ?? '') == 'pending' ? 'selected' : '' }}>Đang Chờ</option>
                        <option value="in_use" {{ old('status', $reservation->status ?? '') == 'in_use' ? 'selected' : '' }}>Đang Sử Dụng</option>
                    </select>
                </div>
                
            </div>
        </div>
    </div>

    <!-- Nút hành động -->
    <div class="form-group text-right">
        <button type="submit" class="btn btn-primary">{{ isset($reservation) ? 'Cập Nhật Đặt Bàn' : 'Lưu Đặt Bàn' }}</button>
        <a href="{{ route('reservations.index') }}" class="btn btn-secondary">Hủy</a>
    </div>
</form>
