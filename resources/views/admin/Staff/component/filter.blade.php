<div class="row">
    <div class="col-lg-12 text-right">
        <form method="GET" action="{{ route('staff.index') }}" class="form-inline">
            <div class="form-group mb-2">
                <input type="date" name="hire_date" class="form-control" value="{{ request()->input('hire_date') }}">
            </div>
            <!-- Dropdown chọn tình trạng -->
            <div class="form-group mb-2">
                <select name="status" class="form-control">
                    <option value="">-- Chọn Tình Trạng --</option>
                    <option value="active" {{ request()->input('status') == 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ request()->input('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                </select>
            </div>

            <!-- Tìm kiếm từ khóa -->
            <div class="form-group mx-sm-3 mb-2">
                <input type="text" name="search" class="form-control" placeholder="Tìm kiếm nhân viên..." value="{{ request()->input('search') }}">
            </div>

            <!-- Nút Tìm kiếm -->
            <button type="submit" class="btn btn-primary mb-2">Tìm kiếm</button>

            <!-- Nút Thêm Nhân Viên -->
            <a href="{{ route('staff.create') }}" class="btn btn-danger mb-2 ml-2">Thêm Nhân Viên</a>
        </form>
    </div>
</div>
