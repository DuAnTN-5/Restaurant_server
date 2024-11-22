<div class="cart-items">
    @foreach ($products as $product)
        <div class="cart-item mb-3">
            <div class="row">
                <div class="col-lg-4">
                    <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="img-thumbnail" style="width: 100px; height: 100px;">
                </div>
                <div class="col-lg-5 item-details">
                    <h5 class="item-name">{{ $product->name }}</h5>
                    <p><strong>Mã sản phẩm:</strong> {{ $product->product_code }}</p>
                    <p class="item-price" data-price="{{ $product->price }}">Giá: ${{ number_format($product->price, 2) }}</p>
                </div>
                <div class="col-lg-3">
                    <label for="quantity_{{ $product->id }}">Số lượng:</label>
                    <input type="number" name="quantity[{{ $product->id }}]" id="quantity_{{ $product->id }}" value="0" min="0" class="form-control quantity-input">
                </div>
            </div>
        </div>
    @endforeach
</div>
