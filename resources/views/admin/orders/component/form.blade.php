<form method="POST" action="{{ isset($order) ? route('orders.update', $order->id) : route('orders.store') }}">
    @csrf
    @if (isset($order))
        @method('PUT')
    @endif

    <div class="row">
        <!-- Phần bên trái - 8 cột -->
        <div class="col-lg-8">
            <!-- Tên người dùng -->
            <div class="form-group">
                <label for="user_id">Tên người dùng:</label>
                <select name="user_id" class="form-control" required>
                    <option value="">-- Chọn người dùng --</option>
                    @foreach ($users as $user)
                        <option value="{{ $user->id }}"
                            {{ old('user_id', $order->user_id ?? '') == $user->id ? 'selected' : '' }}>
                            {{ $user->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Tên bàn -->
            <div class="form-group">
                <label for="table_id">Tên Bàn:</label>
                <select name="table_id" class="form-control">
                    <option value="">-- Chọn bàn --</option>
                    @foreach ($tables as $table)
                        <option value="{{ $table->id }}"
                            {{ old('table_id', $order->table_id ?? '') == $table->id ? 'selected' : '' }}>
                            Bàn {{ $table->number }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- ID Khuyến mãi -->
            <div class="form-group">
                <label for="discount_promotion_id">ID Khuyến mãi:</label>
                <input type="text" name="discount_promotion_id" class="form-control"
                    value="{{ old('discount_promotion_id', $order->discount_promotion_id ?? '') }}">
            </div>

            <!-- Mã Coupon -->
            <div class="form-group">
                <label for="coupon_code">Mã Coupon:</label>
                <input type="text" name="coupon_code" class="form-control"
                    value="{{ old('coupon_code', $order->coupon_code ?? '') }}">
            </div>

            <!-- Loại đơn hàng -->
            <div class="form-group">
                <label for="order_type">Loại đơn hàng:</label>
                <select name="order_type" class="form-control" required>
                    <option value="dine_in" {{ old('order_type', $order->order_type ?? '') == 'dine_in' ? 'selected' : '' }}>Tại Quán</option>
                    <option value="takeaway" {{ old('order_type', $order->order_type ?? '') == 'takeaway' ? 'selected' : '' }}>Mang Đi</option>
                </select>
            </div>

            <!-- Ghi chú -->
            <div class="form-group">
                <label for="note">Ghi chú:</label>
                <textarea name="note" id="note" class="form-control">{{ old('note', $order->note ?? '') }}</textarea>
            </div>
        </div>

        <!-- Phần bên phải - 4 cột -->
        <div class="col-lg-4">
            <!-- Ngày đặt hàng -->
            <div class="form-group">
                <label for="order_date">Ngày đặt hàng:</label>
                <input type="date" name="order_date" class="form-control"
                    value="{{ old('order_date', $order->order_date ?? '') }}" required>
            </div>

            <!-- Tổng giá -->
            <div class="form-group">
                <label for="total_price">Tổng giá:</label>
                <input type="number" id="total_price" name="total_price" class="form-control" placeholder="VND"
                    value="{{ old('total_price', $order->total_price ?? '') }}" required>
            </div>

            <!-- Trạng thái thanh toán -->
            <div class="form-group">
                <label for="payment_status">Trạng thái thanh toán:</label>
                <select name="payment_status" class="form-control" required>
                    <option value="paid" {{ old('payment_status', $order->payment_status ?? '') == 'paid' ? 'selected' : '' }}>Đã thanh toán</option>
                    <option value="unpaid" {{ old('payment_status', $order->payment_status ?? '') == 'unpaid' ? 'selected' : '' }}>Chưa thanh toán</option>
                </select>
            </div>

            <!-- Trạng thái đơn hàng -->
            <div class="form-group">
                <label for="status">Trạng thái đơn hàng:</label>
                <select name="status" class="form-control" required>
                    <option value="pending" {{ old('status', $order->status ?? 'pending') == 'pending' ? 'selected' : '' }}>Đang chờ</option>
                    <option value="completed" {{ old('status', $order->status ?? '') == 'completed' ? 'selected' : '' }}>Hoàn thành</option>
                    <option value="canceled" {{ old('status', $order->status ?? '') == 'canceled' ? 'selected' : '' }}>Hủy</option>
                </select>
            </div>

            <!-- Thời gian giao dự kiến -->
            {{-- <div class="form-group">
                <label for="estimated_delivery_time">Thời gian giao dự kiến:</label>
                <input type="time" name="estimated_delivery_time" class="form-control"
                    value="{{ old('estimated_delivery_time', $order->estimated_delivery_time ?? '') }}">
            </div> --}}
        </div>
    </div>

    <!-- Nút hành động -->
    <div class="form-group text-right">
        <button type="submit" class="btn btn-primary">{{ isset($order) ? 'Cập Nhật Đơn Hàng' : 'Lưu Đơn Hàng' }}</button>
        <a href="{{ route('orders.index') }}" class="btn btn-secondary">Hủy</a>
    </div>
</form>
