<form method="POST" action="{{ isset($category) ? route('product-categories.update', $category->id) : route('product-categories.store') }}" enctype="multipart/form-data">
    @csrf
    @if (isset($category))
        @method('PUT')
    @endif

    <div class="row">
        <!-- Left column (8/12): Category name and description -->
        <div class="col-md-8">
            <!-- Category Name -->
            <div class="form-group">
                <label for="name">Tên Danh Mục <span class="text-danger">(*)</span></label>
                <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $category->name ?? '') }}" required>
            </div>

            <!-- Description -->
            <div class="form-group">
                <label for="description">Mô Tả</label>
                <textarea name="description" id="description" class="form-control" rows="3">{{ old('description', $category->description ?? '') }}</textarea>
            </div>
        </div>

        <!-- Right column (4/12): Parent category, status, and position -->
        <div class="col-md-4">
            <!-- Parent Category -->
            <div class="form-group">
                <label for="parent_id">Danh Mục Cha</label>
                <select name="parent_id" id="parent_id" class="form-control select2">
                    <option value="">[Chọn Danh Mục Cha]</option>
                    @foreach ($categories as $parentCategory)
                        <option value="{{ $parentCategory->id }}" {{ old('parent_id', $category->parent_id ?? '') == $parentCategory->id ? 'selected' : '' }}>
                            {{ $parentCategory->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Status -->
            <div class="form-group">
                <label for="status">Trạng Thái</label>
                <select name="status" id="status" class="form-control">
                    <option value="active" {{ old('status', $category->status ?? 'active') == 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ old('status', $category->status ?? 'inactive') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                </select>
            </div>

            <!-- Position -->
            <div class="form-group">
                <label for="position">Thứ Tự</label>
                <input type="number" name="position" id="position" class="form-control" value="{{ old('position', $category->position ?? 0) }}">
            </div>
        </div>
    </div>

    <!-- Save Button -->
    <div class="form-group text-right">
        <button type="submit" class="btn btn-primary">{{ isset($category) ? 'Cập Nhật' : 'Lưu' }}</button>
        <a href="{{ route('product-categories.index') }}" class="btn btn-secondary">Hủy</a>
    </div>
</form>
