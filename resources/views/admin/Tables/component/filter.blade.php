<div class="row">
    <div class="col-lg-12 text-right">
        <form method="GET" action="{{ route('tables.index') }}" class="form-inline">

            <!-- Filter by Creation Date -->
            <div class="form-group mb-2">
                <input type="date" name="created_at" class="form-control" value="{{ request()->input('created_at') }}">
            </div>

            <!-- Dropdown for Table Status -->
            <div class="form-group mx-sm-3 mb-2">
                <select name="status" class="form-control">
                    <option value="">-- Chọn Trạng Thái --</option>
                    <option value="available" {{ request()->input('status') == 'available' ? 'selected' : '' }}>Có Sẵn</option>
                    <option value="reserved" {{ request()->input('status') == 'reserved' ? 'selected' : '' }}>Đã Đặt</option>
                    <option value="occupied" {{ request()->input('status') == 'occupied' ? 'selected' : '' }}>Đang Sử Dụng</option>
                </select>
            </div>

            <!-- Keyword Search -->
            <div class="form-group mx-sm-3 mb-2">
                <input type="text" name="search" class="form-control" placeholder="Tìm kiếm bàn..." value="{{ request()->input('search') }}">
            </div>

            <!-- Search Button -->
            <button type="submit" class="btn btn-primary mb-2">Tìm kiếm</button>

            <!-- Button to Add New Table -->
            <a href="{{ route('tables.create') }}" class="btn btn-danger mb-2 ml-2">Tạo Mới Bàn</a>
        </form>
    </div>
</div>
