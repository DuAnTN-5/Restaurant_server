<div class="row">
    <div class="col-lg-12 text-right">
        <form method="GET" action="{{ route('reservations.index') }}" class="form-inline">

            <!-- Lọc theo ngày đặt -->
            <div class="form-group mb-2">
                <input type="date" name="reservation_date" class="form-control" value="{{ request()->input('reservation_date') }}">
            </div>

            <!-- Dropdown chọn người dùng -->
            {{-- <div class="form-group mx-sm-3 mb-2">
                <select name="user_id" class="form-control">
                    <option value="">-- Chọn Người Dùng --</option>
                    @foreach ($users as $user)
                        <option value="{{ $user->id }}" {{ request()->input('user_id') == $user->id ? 'selected' : '' }}>
                            {{ $user->name }}
                        </option>
                    @endforeach
                </select>
            </div> --}}

            <!-- Dropdown chọn tình trạng -->
            <div class="form-group mx-sm-3 mb-2">
                <select name="status" class="form-control">
                    <option value="">-- Chọn Tình Trạng --</option>
                    <option value="active" {{ request()->input('status') == 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ request()->input('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                </select>
            </div>

            <!-- Tìm kiếm theo mã bàn -->
            <div class="form-group mx-sm-3 mb-2">
                <input type="text" name="table_id" class="form-control" placeholder="Tìm kiếm mã bàn..." value="{{ request()->input('table_id') }}">
            </div>

            <!-- Nút Tìm kiếm -->
            <button type="submit" class="btn btn-primary mb-2">Tìm kiếm</button>

            <!-- Nút Thêm Đặt Bàn -->
            <a href="{{ route('reservations.create') }}" class="btn btn-danger mb-2 ml-2">Tạo Mới Đặt Bàn</a>
        </form>
    </div>
</div>
