<div class="row">
    <div class="col-lg-12 text-right">
        <form method="GET" action="{{ route('orders.index') }}" class="form-inline">
            <!-- Lọc theo Order ID -->
            <div class="form-group mb-2">
                {{-- <label for="order_id">Order ID</label> --}}
                <input type="text" name="order_id" class="form-control" placeholder="Nhập Order ID"
                    value="{{ request()->input('order_id') }}">
            </div>

            <!-- Dropdown chọn trạng thái đơn hàng -->
            <div class="form-group mb-2 mx-sm-3">
                <select name="status" class="form-control">
                    <option value="">-- Chọn Trạng Thái --</option>
                    <option value="pending" {{ request()->input('status') == 'pending' ? 'selected' : '' }}>Đang Chờ
                    </option>
                    <option value="confirmed" {{ request()->input('status') == 'confirmed' ? 'selected' : '' }}>Xác Nhận
                    </option>
                    <option value="canceled" {{ request()->input('status') == 'canceled' ? 'selected' : '' }}>Hủy
                    </option>
                    <option value="completed" {{ request()->input('status') == 'completed' ? 'selected' : '' }}>Hoàn
                        Thành</option>
                </select>
            </div>
            <!-- Lọc theo ngày đặt -->
            <div class="form-group mb-2 mx-sm-3">
                <label for="order_date">Ngày Đặt</label>
                <input type="date" name="order_date" class="form-control"
                    value="{{ request()->input('order_date') }}">
            </div>

            <!-- Nút Tìm kiếm -->
            <button type="submit" class="btn btn-primary mb-2">Tìm kiếm</button>

            <!-- Nút Tạo Đơn Hàng -->
            <a href="{{ route('orders.create') }}" class="btn btn-danger mb-2 ml-2">Tạo Đơn Hàng</a>

        </form>
    </div>
</div>
