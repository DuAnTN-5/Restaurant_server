<div class="row">
    <div class="col-lg-12 text-right">
        <form method="GET" action="{{ route('staff.index') }}" class="form-inline">
            <!-- Tìm kiếm từ khóa cho tên hoặc thông tin liên hệ của staff -->
            <div class="form-group mx-sm-3 mb-2">
                <input type="text" name="search" class="form-control" placeholder="Tìm kiếm nhân viên..." value="{{ request()->input('search') }}">
            </div>

            <!-- Nút Tìm kiếm -->
            <button type="submit" class="btn btn-primary mb-2">Tìm kiếm</button>

            <!-- Nút Thêm Nhân Viên (liên kết đến trang tạo người dùng) -->
            <a href="{{ route('users.create') }}" class="btn btn-danger mb-2 ml-2">Thêm Nhân Viên</a>
        </form>
    </div>
</div>
