<div class="row">
    <div class="col-lg-12 text-right">
        <form method="GET" action="{{ route('products.index') }}" class="form-inline">

            <!-- Lọc theo ngày tạo -->
            <div class="form-group mb-2">
                <input type="date" name="created_at" class="form-control" value="{{ request()->input('created_at') }}">
            </div>

            <!-- Dropdown chọn danh mục sản phẩm -->
            <div class="form-group mx-sm-3 mb-2">
                <select name="category_id" class="form-control">
                    <option value="">-- Chọn Danh Mục --</option>
                    @foreach ($productCategories as $productCategory)
                        <option value="{{ $productCategory->id }}" {{ request()->input('category_id') == $productCategory->id ? 'selected' : '' }}>
                            {{ $productCategory->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Dropdown chọn tình trạng -->
            <div class="form-group mx-sm-3 mb-2">
                <select name="status" class="form-control">
                    <option value="">-- Chọn Tình Trạng --</option>
                    <option value="active" {{ request()->input('status') == 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ request()->input('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                </select>
            </div>

            <!-- Tìm kiếm từ khóa -->
            <div class="form-group mx-sm-3 mb-2">
                <input type="text" name="search" class="form-control" placeholder="Tìm kiếm sản phẩm..." value="{{ request()->input('search') }}">
            </div>

            <!-- Nút Tìm kiếm -->
            <button type="submit" class="btn btn-primary mb-2">Tìm kiếm</button>

            <!-- Nút Thêm Sản Phẩm -->
            <a href="{{ route('products.create') }}" class="btn btn-danger mb-2 ml-2">Tạo Mới Sản Phẩm</a>
        </form>
    </div>
</div>
