<form method="POST" action="{{ isset($post) ? route('posts.update', $post->id) : route('posts.store') }}"
    enctype="multipart/form-data">
    @csrf
    @if (isset($post))
        @method('PUT')
    @endif

    <div class="row">
        <!-- Phần bên trái - 8 cột -->
        <div class="col-lg-8">
            <!-- Tiêu đề -->
            <div class="form-group">
                <label for="title">Tiêu đề:</label>
                <input type="text" name="title" class="form-control" value="{{ old('title', $post->title ?? '') }}"
                    required>
            </div>

            <!-- Mã bài viết (code) -->
            {{-- <div class="form-group">
                <label for="code">Mã bài viết:</label>
                <input type="text" name="code" class="form-control" value="{{ old('code', $post->code ?? '') }}"
                    required>
            </div> --}}

            <!-- Thứ tự -->
            <div class="form-group">
                <label for="position">Thứ tự:</label>
                <input type="number" name="position" class="form-control"
                    value="{{ old('position', $post->position ?? '') }}" required>
            </div>

            <!-- Nội dung -->
            <div class="form-group">
                <label for="body">Nội dung:</label>
                <textarea name="body" class="form-control" rows="5" required>{{ old('body', $post->body ?? '') }}</textarea>
            </div>

            <!-- Tóm tắt -->
            <div class="form-group">
                <label for="summary">Tóm tắt:</label>
                <textarea name="summary" class="form-control" rows="3">{{ old('summary', $post->summary ?? '') }}</textarea>
            </div>
        </div>

        <!-- Phần bên phải - 4 cột -->
        <div class="col-lg-4">
            <!-- Danh mục -->
            <div class="form-group">
                <label for="category_id">Danh mục:</label>
                <select name="category_id" class="form-control" required>
                    <option value="">-- Chọn danh mục --</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}"
                            {{ old('category_id', $post->category_id ?? '') == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group text-center">
                <label for="image_url">Chọn ảnh:</label>
                <div class="image-preview text-center">
                    <label for="image_url">
                        <!-- Hình ảnh thay thế cho input -->
                        <img id="preview-image" src="{{ asset('logo/anh-chon.jpg') }}" alt="Chọn ảnh" width="300" style="cursor: pointer;">
                    </label>
                    <!-- Input chọn file thực sự, ẩn đi -->
                    <input type="file" name="image_url" id="image_url" style="display: none;" onchange="previewImage(event)">
                </div>
                <!-- Hiển thị ảnh được chọn -->
                <div id="preview-images" class="text-center"></div>
            </div>
            

            <!-- Trạng thái -->
            <div class="form-group">
                <label for="status">Trạng thái:</label>
                <select name="status" class="form-control" required>
                    <option value="draft" {{ old('status', $post->status ?? '') == 'draft' ? 'selected' : '' }}>Nháp
                    </option>
                    <option value="published"
                        {{ old('status', $post->status ?? '') == 'published' ? 'selected' : '' }}>Đã xuất bản</option>
                    <option value="archived" {{ old('status', $post->status ?? '') == 'archived' ? 'selected' : '' }}>
                        Lưu trữ</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Nút hành động -->
    <div class="form-group text-right">
        <button type="submit"
            class="btn btn-primary">{{ isset($post) ? 'Cập Nhật Bài Viết' : 'Lưu Bài Viết' }}</button>
        <a href="{{ route('posts.index') }}" class="btn btn-secondary">Hủy</a>
    </div>
</form>
<!-- JavaScript để hiển thị trước các ảnh -->
<script>
    function previewImage(event) {
    const file = event.target.files[0];  // Lấy file đã chọn
    const preview = document.getElementById('preview-image');

    const reader = new FileReader();
    reader.onload = function(e) {
        // Thay thế src của hình ảnh bằng file đã chọn
        preview.src = e.target.result;
    };

    // Kiểm tra nếu file là ảnh hợp lệ
    if (file) {
        reader.readAsDataURL(file);
    }
}

</script>
