<form method="POST" action="{{ isset($coupon) ? route('coupons.update', $coupon->id) : route('coupons.store') }}" enctype="multipart/form-data">
    @csrf
    @if (isset($coupon))
        @method('PUT')
    @endif

    <div class="row">
        <!-- Phần bên trái - 8 cột -->
        <div class="col-lg-8">
            <!-- User ID -->
            {{-- <div class="form-group">
                <label for="user_id">ID Người Dùng:</label>
                <input type="number" name="user_id" class="form-control" value="{{ old('user_id', $coupon->user_id ?? '') }}" required>
            </div> --}}

            <!-- Code -->
            <div class="form-group">
                <label for="code">Mã Coupon:</label>
                <input type="text" name="code" class="form-control" value="{{ old('code', $coupon->code ?? '') }}" required>
            </div>

            <!-- Discount Type -->
            <div class="form-group">
                <label for="discount_type">Loại Giảm Giá:</label>
                <select name="discount_type" class="form-control" required>
                    <option value="">-- Chọn loại giảm giá --</option>
                    <option value="percentage" {{ old('discount_type', $coupon->discount_type ?? '') == 'percentage' ? 'selected' : '' }}>Phần Trăm</option>
                    <option value="fixed" {{ old('discount_type', $coupon->discount_type ?? '') == 'fixed' ? 'selected' : '' }}>Cố Định</option>
                </select>
            </div>

            <!-- Value -->
            <div class="form-group">
                <label for="value">Giá Trị:</label>
                <input type="number" name="value" class="form-control" value="{{ old('value', $coupon->value ?? '') }}" required>
            </div>

            <!-- Start Date -->
            <div class="form-group">
                <label for="start_date">Ngày Bắt Đầu:</label>
                <input type="date" name="start_date" class="form-control" value="{{ old('start_date', $coupon->start_date ?? '') }}" required>
            </div>

            <!-- End Date -->
            <div class="form-group">
                <label for="end_date">Ngày Kết Thúc:</label>
                <input type="date" name="end_date" class="form-control" value="{{ old('end_date', $coupon->end_date ?? '') }}" required>
            </div>
        </div>

        <!-- Phần bên phải - 4 cột -->
        <div class="col-lg-4">
            <!-- Usage Limit -->
            <div class="form-group">
                <label for="usage_limit">Giới Hạn Sử Dụng:</label>
                <input type="number" name="usage_limit" class="form-control" value="{{ old('usage_limit', $coupon->usage_limit ?? '') }}">
            </div>

            <div class="form-group">
                <label for="minimum_order_value">Giá Trị Đơn Hàng Tối Thiểu:</label>
                <input type="number" name="minimum_order_value" class="form-control" value="{{ old('minimum_order_value', $coupon->minimum_order_value ?? '') }}" min="0" step="1000" placeholder="Nhập giá trị tối thiểu">
            </div>
            

            <!-- Status -->
            <div class="form-group">
                <label for="status">Trạng Thái:</label>
                <select name="status" class="form-control" required>
                    <option value="active" {{ old('status', $coupon->status ?? 'active') == 'active' ? 'selected' : '' }}>Hoạt Động</option>
                    <option value="inactive" {{ old('status', $coupon->status ?? '') == 'inactive' ? 'selected' : '' }}>Không Hoạt Động</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Nút hành động -->
    <div class="form-group text-right">
        <button type="submit" class="btn btn-primary">{{ isset($coupon) ? 'Cập Nhật' : 'Lưu Mới' }}</button>
        <a href="{{ route('coupons.index') }}" class="btn btn-secondary">Hủy</a>
    </div>
</form>
