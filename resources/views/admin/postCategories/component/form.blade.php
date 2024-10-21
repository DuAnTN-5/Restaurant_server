<form action="{{ isset($category) ? route('PostCategories.update', $category->id) : route('PostCategories.store') }}" method="POST">
    @csrf
    @if (isset($category))
        @method('PUT')
    @endif

    <div class="form-group">
        <label for="name">Tên Loại</label>
        <input type="text" name="name" class="form-control" id="name" value="{{ old('name', $category->name ?? '') }}" required>
    </div>

    {{-- <div class="form-group">
        <label for="slug">Slug</label>
        <input type="text" name="slug" class="form-control" id="slug" value="{{ old('slug', $category->slug ?? '') }}" required>
    </div> --}}

    <div class="form-group">
        <label for="description">Mô Tả</label>
        <textarea name="description" class="form-control" id="description" rows="4">{{ old('description', $category->description ?? '') }}</textarea>
    </div>

    <div class="form-group">
        <label for="position">Thứ Tự</label>
        <input type="number" name="position" class="form-control" id="position" value="{{ old('position', $category->position ?? '') }}" required>
    </div>

    <button type="submit" class="btn btn-primary">{{ isset($category) ? 'Cập Nhật' : 'Lưu Loại Bài Viết' }}</button>
    <a href="{{ route('PostCategories.index') }}" class="btn btn-secondary">Hủy</a>
</form>