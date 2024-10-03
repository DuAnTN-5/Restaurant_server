<div class="row">
    <div class="col-lg-12 text-right">
        <form method="GET" action="{{ route('posts.index') }}" class="form-inline">
            <!-- Lọc theo ngày tạo -->
            <div class="form-group mb-2">
                <label for="created_at">Ngày tạo</label>
                <input type="date" name="created_at" class="form-control" value="{{ request()->input('created_at') }}">
            </div>

            <!-- Dropdown chọn danh mục -->
            <div class="form-group mb-2 mx-sm-3">
                <select name="category_id" class="form-control">
                    <option value="">-- Chọn Danh Mục --</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}" {{ request()->input('category_id') == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Dropdown chọn tình trạng -->
            <div class="form-group mb-2 mx-sm-3">
                <select name="status" class="form-control">
                    <option value="">-- Chọn Tình Trạng --</option>
                    <option value="published" {{ request()->input('status') == 'published' ? 'selected' : '' }}>Đã xuất bản</option>
                    <option value="draft" {{ request()->input('status') == 'draft' ? 'selected' : '' }}>Nháp</option>
                    <option value="archived" {{ request()->input('status') == 'archived' ? 'selected' : '' }}>Lưu trữ</option>
                </select>
            </div>

            <!-- Tìm kiếm từ khóa -->
            <div class="form-group mx-sm-3 mb-2">
                <input type="text" name="search" class="form-control" placeholder="Tìm kiếm bài viết..." value="{{ request()->input('search') }}">
            </div>

            <!-- Nút Tìm kiếm -->
            <button type="submit" class="btn btn-primary mb-2">Tìm kiếm</button>

            <!-- Nút Tạo Bài Viết -->
            <a href="{{ route('posts.create') }}" class="btn btn-danger mb-2 ml-2">Tạo Bài Viết</a>
        </form>
    </div>
</div>
