<form method="POST" action="{{ isset($productCategory) ? route('ProductCategories.update', $productCategory->id) : route('ProductCategories.store') }}" enctype="multipart/form-data">
    @csrf
    @if (isset($productCategory))
        @method('PUT')
    @endif

    <div class="row">
        <!-- Left column (8/12): Category name and description -->
        <div class="col-md-8">
            <!-- Category Name -->
            <div class="form-group">
                <label for="name">Tên Danh Mục <span class="text-danger">(*)</span></label>
                <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $productCategory->name ?? '') }}" required>
            </div>

            <!-- Description -->
            <div class="form-group">
                <label for="description">Mô Tả</label>
                <textarea name="description" id="description" class="form-control" rows="3">{{ old('description', $productCategory->description ?? '') }}</textarea>
            </div>
        </div>

        <!-- Right column (4/12): Parent category, status, and position -->
        <div class="col-md-4">
            <!-- Parent Category -->
            <div class="form-group">
                <label for="parent_id">Danh Mục Cha</label>
                <select name="parent_id" id="parent_id" class="form-control select2">
                    <option value="">[Chọn Danh Mục Cha]</option>
                    @foreach ($productCategories as $parentProductCategory)
                        <option value="{{ $parentProductCategory->id }}" {{ old('parent_id', $productCategory->parent_id ?? '') == $parentProductCategory->id ? 'selected' : '' }}>
                            {{ $parentProductCategory->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Status -->
            <div class="form-group">
                <label for="status">Trạng Thái</label>
                <select name="status" id="status" class="form-control">
                    <option value="active" {{ old('status', $productCategory->status ?? 'active') == 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ old('status', $productCategory->status ?? 'inactive') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                </select>
            </div>

            <!-- Position -->
            <div class="form-group">
                <label for="position">Thứ Tự</label>
                <input type="number" name="position" id="position" class="form-control" value="{{ old('position', $productCategory->position ?? 0) }}" min="1" step="1">
            </div>
            
        </div>
    </div>

    <!-- Save Button -->
    <div class="form-group text-right">
        <button type="submit" class="btn btn-primary">{{ isset($productCategory) ? 'Cập Nhật' : 'Lưu' }}</button>
        <a href="{{ route('ProductCategories.index') }}" class="btn btn-secondary">Hủy</a>
    </div>
</form>
