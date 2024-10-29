<form method="POST" action="{{ isset($product) ? route('products.update', $product->id) : route('products.store') }}"
    enctype="multipart/form-data">
    @csrf
    @if (isset($product))
        @method('PUT')
    @endif

    <div class="row">
        <!-- Phần bên trái - 8 cột -->
        <div class="col-lg-8">
            <!-- Tên sản phẩm -->
            <div class="form-group">
                <label for="name">Tên sản phẩm:</label>
                <input type="text" name="name" class="form-control" value="{{ old('name', $product->name ?? '') }}"
                    required>
            </div>

            <!-- Mô tả -->
            <div class="form-group">
                <label for="description">Mô tả:</label>
                <textarea id="description" name="description" class="form-control summernote" required>{{ old('description', $product->description ?? '') }}</textarea>
            </div>

            <!-- Tóm tắt -->
            <div class="form-group">
                <label for="summary">Tóm tắt:</label>
                <textarea id="summary" name="summary" class="form-control summernote" required>{{ old('summary', $product->summary ?? '') }}</textarea>
            </div>

            <!-- Giá, Giá giảm, và Số lượng tồn kho -->
            <div class="form-group row">
                <div class="col-lg-4">
                    <label for="price">Giá:</label>
                    <input type="text" id="price" name="price" class="form-control" placeholder="VND"
                        value="{{ old('price', $product->price ?? '') }}" oninput="formatCurrency(this)" required>
                </div>

                <div class="col-lg-4">
                    <label for="discount_price">Giá giảm:</label>
                    <input type="text" id="discount_price" name="discount_price" class="form-control"
                        placeholder="VND" value="{{ old('discount_price', $product->discount_price ?? '') }}"
                        oninput="formatCurrency(this)">
                </div>

                <div class="col-lg-4">
                    <label for="stock_quantity">Số lượng tồn kho:</label>
                    <input type="number" name="stock_quantity" class="form-control"
                        value="{{ old('stock_quantity', $product->stock_quantity ?? '') }}" required>
                </div>
            </div>

            <!-- Nguyên liệu -->
            <div class="form-group">
                <label for="ingredients">Nguyên liệu:</label>
                <input type="text" id="ingredients" name="ingredients" class="form-control"
                    placeholder="Nhập nguyên liệu... (nhấn Enter để thêm)"
                    value="{{ old('ingredients', $product->ingredients ?? '') }}">
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
                            {{ old('category_id', $product->category_id ?? '') == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group text-center">
                <label for="image_url">Chọn ảnh:</label>
                <div class="image-preview text-center">
                    <label for="image_url">
                        <img id="preview-image"
                            src="{{ isset($product->image_url) ? asset('productfiles/' . $product->image_url) : asset('logo/anh-chon.jpg') }}"
                            alt="Chọn ảnh" width="300" style="cursor: pointer;">
                    </label>
                    <input type="file" name="image_url" id="image_url" style="display: none;"
                        onchange="previewImage(event)">
                </div>
            </div>


            <div class="form-group row">
                <!-- Mã sản phẩm -->
                <div class="col-lg-6">
                    <label for="product_code">Mã sản phẩm:</label>
                    <input type="text" name="product_code" class="form-control"
                        value="{{ old('product_code', $product->product_code ?? '') }}" required>
                </div>

                <!-- Thứ tự -->
                <div class="col-lg-6">
                    <label for="position">Thứ tự:</label>
                    <input type="number" name="position" class="form-control"
                        value="{{ old('position', $product->position ?? '') }}" required>
                </div>
            </div>

            <!-- Tags -->
            <div class="form-group">
                <label for="tags">Tags:</label>
                <input type="text" id="tags" name="tags" class="form-control"
                    placeholder="Nhập tags... (nhấn Enter để thêm)" value="{{ old('tags', $product->tags ?? '') }}">
            </div>

            <!-- Trạng thái -->
            <div class="form-group">
                <label for="status">Trạng thái:</label>
                <select name="status" class="form-control" required>
                    <option value="active"
                        {{ old('status', $product->status ?? 'active') == 'active' ? 'selected' : '' }}>Kích hoạt
                    </option>
                    <option value="inactive"
                        {{ old('status', $product->status ?? '') == 'inactive' ? 'selected' : '' }}>Vô hiệu</option>
                </select>
            </div>

        </div>
    </div>

    <!-- Nút hành động -->
    <div class="form-group text-right">
        <button type="submit"
            class="btn btn-primary">{{ isset($product) ? 'Cập Nhật Sản Phẩm' : 'Lưu Sản Phẩm' }}</button>
        <a href="{{ route('products.index') }}" class="btn btn-secondary">Hủy</a>
    </div>
</form>

<!-- Link và Script cho Tagify và Summernote -->
@include('admin.Products.component.script')
