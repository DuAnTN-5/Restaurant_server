<table class="table table-striped table-bordered table-hover dataTables-products">
    <thead>
        <tr>
            <th>ID</th>
            <th>Mã Sản Phẩm</th>
            <th>Tên</th>
            <th>Giá</th>
            <th>Hình Ảnh</th>
            <th>Số Lượng Tồn Kho</th>
            <th>Thứ Tự</th>
            <th>Trạng Thái</th>
            <th>Ngày Tạo</th>
            <th>Thao Tác</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($products as $product)
            <tr>
                <td>{{ $product->id }}</td>
                <td>{{ $product->product_code }}</td>
                <td>
                    {{ $product->name }}
                    <br>
                    <small style="color: red;">Danh mục: {{ $product->category ? $product->category->name : 'Không có' }}</small>
                </td>
                <td>
                    <span style="color: blue;">Giá: {{ $product->price }}</span>
                    @if ($product->discount_price)
                        <br><span style="color: red;">Giá giảm: {{ $product->discount_price }}</span>
                    @endif
                </td>
                <td>
                    @if ($product->image_url && file_exists(public_path($product->image_url)))
                        <img src="{{ asset($product->image_url) }}" alt="Image" width="80">
                    @else
                        <img src="{{ asset('default-product.png') }}" alt="Default Image" width="50">
                    @endif
                </td>
                
                <td>{{ $product->stock_quantity }}</td>
                <td>{{ $product->position }}</td>
                <td style="text-align: center; vertical-align: middle;">
                    <input type="checkbox" class="js-switch" 
                           data-id="{{ $product->id }}" 
                           data-type="product" 
                           {{ $product->status === 'active' ? 'checked' : '' }}>
                </td>
                
                
                <td>{{ $product->created_at->format('d/m/Y') }}</td>
                <td>
                    <a href="{{ route('products.edit', $product->id) }}" class="btn btn-success">
                        <i class="fa fa-edit"></i>
                    </a>
                    <form method="POST" action="{{ route('products.destroy', $product->id) }}" style="display: inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger" onclick="return confirm('Xác nhận xóa?')">
                            <i class="fa fa-trash"></i>
                        </button>
                    </form>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
