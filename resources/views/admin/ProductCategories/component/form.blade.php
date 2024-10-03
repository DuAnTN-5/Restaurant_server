<form method="POST" action="{{ isset($category) ? route('product-categories.update', $category->id) : route('product-categories.store') }}" enctype="multipart/form-data">
    @csrf
    @if (isset($category))
        @method('PUT')
    @endif

    <div class="row">
        <!-- Left column (8/12): Category name, description, meta -->
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

            <!-- Meta Title -->
            <div class="form-group">
                <label for="meta_title">Thẻ Meta Title</label>
                <input type="text" name="meta_title" id="meta_title" class="form-control" value="{{ old('meta_title', $category->meta_title ?? '') }}">
            </div>

            <!-- Meta Description -->
            <div class="form-group">
                <label for="meta_description">Thẻ Meta Description</label>
                <textarea name="meta_description" id="meta_description" class="form-control" rows="3">{{ old('meta_description', $category->meta_description ?? '') }}</textarea>
            </div>
        </div>

        <!-- Right column (4/12): Parent category, status, position, and image_url -->
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

            <!-- Image Upload (Using image_url) -->
            <div class="form-group">
                <label for="image_url">Ảnh Danh Mục</label>
                <div class="image-preview text-center">
                    <label for="image_url">
                        <img id="preview-image" src="{{ isset($category) && $category->image_url ? asset($category->image_url) : asset('logo/anh-chon.jpg') }}" alt="Chọn ảnh" width="280" style="cursor: pointer;">
                    </label>
                    <input type="file" name="image_url" id="image_url" style="display: none;" onchange="previewImage(event)">
                </div>
                @if (isset($category) && $category->image_url)
                    <img src="{{ asset($category->image_url) }}" height="64" alt="Current Image">
                @endif
            </div>
        </div>
    </div>

    <!-- Save Button -->
    <div class="form-group text-right">
        <button type="submit" class="btn btn-primary">{{ isset($category) ? 'Cập Nhật' : 'Lưu' }}</button>
        <a href="{{ route('product-categories.index') }}" class="btn btn-secondary">Hủy</a>
    </div>
</form>

<script>
    function previewImage(event) {
        var reader = new FileReader();
        reader.onload = function() {
            var output = document.getElementById('preview-image');
            output.src = reader.result;
        };
        reader.readAsDataURL(event.target.files[0]);
    }
</script>
