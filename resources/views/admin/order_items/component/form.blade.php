<form method="POST" action="{{ isset($item) ? route('order_items.update', ['orderId' => $orderId, 'id' => $item->id]) : route('order_items.store', ['orderId' => $orderId]) }}">
    @csrf
    @if (isset($item))
        @method('PUT')
    @endif

    <div class="row">
        <!-- Phần bên trái - 8 cột -->
        <div class="col-lg-8">
            <!-- ID Sản Phẩm -->
            <div class="form-group">
                <label for="product_id">Sản phẩm:</label>
                <select name="product_id" class="form-control" required>
                    <option value="">-- Chọn sản phẩm --</option>
                    @foreach ($products as $product)
                        <option value="{{ $product->id }}"
                            {{ old('product_id', $item->product_id ?? '') == $product->id ? 'selected' : '' }}>
                            {{ $product->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- ID Khuyến mãi -->
            <div class="form-group">
                <label for="discount_promotion_id">ID Khuyến mãi:</label>
                <input type="text" name="discount_promotion_id" class="form-control"
                    value="{{ old('discount_promotion_id', $item->discount_promotion_id ?? '') }}">
            </div>

            <!-- Áp Dụng Khuyến Mãi -->
            <div class="form-group">
                <label for="discount_applied">Khuyến mãi đã áp dụng (%):</label>
                <input type="number" name="discount_applied" class="form-control" min="0" max="100"
                    value="{{ old('discount_applied', $item->discount_applied ?? 0) }}" placeholder="Nhập % khuyến mãi (0 - 100)">
            </div>
        </div>

        <!-- Phần bên phải - 4 cột -->
        <div class="col-lg-4">
            <!-- Số lượng -->
            <div class="form-group">
                <label for="quantity">Số lượng:</label>
                <input type="number" name="quantity" class="form-control" min="1" required
                    value="{{ old('quantity', $item->quantity ?? 1) }}" placeholder="Nhập số lượng">
            </div>

            <!-- Giá -->
            <div class="form-group">
                <label for="price">Giá (VND):</label>
                <input type="number" id="price" name="price" class="form-control" placeholder="VND"
                    value="{{ old('price', $item->price ?? '') }}" required>
            </div>

            <!-- Tổng giá -->
            <div class="form-group">
                <label for="total_price">Tổng giá (VND):</label>
                <input type="number" id="total_price" name="total_price" class="form-control" placeholder="VND"
                    value="{{ old('total_price', $item->total_price ?? '') }}" readonly>
            </div>
        </div>
    </div>

    <!-- Nút hành động -->
    <div class="form-group text-right">
        <button type="submit" class="btn btn-primary">{{ isset($item) ? 'Cập Nhật Mục Đơn Hàng' : 'Lưu Mục Đơn Hàng' }}</button>
        <a href="{{ route('order_items.index', ['orderId' => $orderId]) }}" class="btn btn-secondary">Hủy</a>
    </div>
</form>

<!-- Script tính toán tổng giá dựa trên số lượng và giá -->
@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const quantityInput = document.querySelector('input[name="quantity"]');
        const priceInput = document.querySelector('input[name="price"]');
        const totalPriceInput = document.querySelector('input[name="total_price"]');

        function updateTotalPrice() {
            const quantity = parseFloat(quantityInput.value) || 0;
            const price = parseFloat(priceInput.value) || 0;
            totalPriceInput.value = (quantity * price).toFixed(2);
        }

        quantityInput.addEventListener('input', updateTotalPrice);
        priceInput.addEventListener('input', updateTotalPrice);
        
        // Gọi hàm tính tổng giá khi trang được tải
        updateTotalPrice();
    });
</script>
@endpush
