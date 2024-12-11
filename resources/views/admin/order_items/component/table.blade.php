@php
    // Các trạng thái nếu có (nếu cần thiết cho các mục đơn hàng)
    $statusLabels = [
        'available' => 'Còn hàng',
        'out_of_stock' => 'Hết hàng'
    ];
@endphp

<table class="table table-striped table-bordered table-hover dataTables-items">
    <thead>
        <tr>
            <th>ID</th>
            <th>Tên Sản Phẩm</th>
            <th>Số Lượng</th>
            <th>Giảm Giá</th>
            <th>Giá</th>
            <th>Tổng Giá</th>
            <th>Ngày Tạo</th>
            <th>Thao Tác</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($items as $item)
            <tr>
                <td>{{ $item->id }}</td>
                <td>{{ $item->product->name ?? 'Không xác định' }}</td> <!-- Sử dụng relationship với sản phẩm -->
                <td>{{ $item->quantity }}</td>
                <td>{{ $item->discount_applied ? number_format($item->discount_applied, 0, ',', '.') : 0 }} đ</td>
                <td>{{ number_format($item->price, 0, ',', '.') }} đ</td>
                <td>{{ number_format($item->total_price, 0, ',', '.') }} đ</td>
                <td>{{ $item->created_at->format('d/m/Y') }}</td>
                <td>
                    <a href="{{ route('order_items.edit', ['orderId' => $orderId, 'id' => $item->id]) }}" class="btn btn-success">
                        <i class="fa fa-edit"></i>
                    </a>
                    <form method="POST" action="{{ route('order_items.destroy', ['orderId' => $orderId, 'id' => $item->id]) }}" style="display: inline;">
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
