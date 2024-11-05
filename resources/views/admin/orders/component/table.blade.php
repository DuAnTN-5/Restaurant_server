@php
    $statusLabels = [
        'pending' => 'Đang chờ',
        'confirmed' => 'Đã xác nhận',
        'preparing' => 'Đang chuẩn bị',
        'ready' => 'Sẵn sàng',
        'completed' => 'Hoàn thành',
        'canceled' => 'Đã hủy'
    ];
@endphp

<table class="table table-striped table-bordered table-hover dataTables-products">
    <thead>
        <tr>
            <th>ID</th>
            <th>Tên Người Dùng</th>
            <th>Bàn Số</th>
            <th>Trạng Thái Thanh Toán</th>
            <th>Ghi Chú</th>
            <th>Ngày Tạo</th>
            <th>Trạng Thái</th>
            <th>Thao Tác</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($orders as $order)
            <tr>
                <td>{{ $order->id }}</td>
                <td>{{ $order->user->name ?? 'Không xác định' }}</td>
                <td>{{ $order->table->number ?? 'Không xác định' }}</td>
                <td>{{ $order->payment_status }}</td>
                <td>{{ $order->note }}</td>
                <td>{{ $order->created_at->format('d/m/Y') }}</td>
                <td>
                    <form method="POST" action="{{ route('orders.updateStatus') }}" style="display: inline;">
                        @csrf
                        <input type="hidden" name="id" value="{{ $order->id }}">
                        <select name="status" class="form-control status-select" data-id="{{ $order->id }}" onchange="changeStatusColor(this)">
                            <option value="pending" class="status-pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Đang Chờ</option>
                            <option value="confirmed" class="status-confirmed" {{ $order->status == 'confirmed' ? 'selected' : '' }}>Đã Xác Nhận</option>
                            <option value="preparing" class="status-preparing" {{ $order->status == 'preparing' ? 'selected' : '' }}>Đang Chuẩn Bị</option>
                            <option value="ready" class="status-ready" {{ $order->status == 'ready' ? 'selected' : '' }}>Sẵn Sàng</option>
                            <option value="completed" class="status-completed" {{ $order->status == 'completed' ? 'selected' : '' }}>Hoàn Thành</option>
                            <option value="canceled" class="status-canceled" {{ $order->status == 'canceled' ? 'selected' : '' }}>Hủy</option>
                        </select>
                    </form>
                    <td>
                        <!-- Nút "Đặt Món" để thêm sản phẩm vào đơn hàng -->
                        <a href="javascript:void(0);" class="btn btn-info" onclick="openOrderItemModal({{ $order->id }})">
                            <i class="fa fa-plus"></i> Đặt Món
                        </a>
                        
                        <!-- Nút Sửa và Xóa như bạn đã có -->
                        <a href="{{ route('orders.edit', $order->id) }}" class="btn btn-success">
                            <i class="fa fa-edit"></i>
                        </a>
                        <a href="{{ route('order_items.index', ['orderId' => $order->id]) }}" class="btn btn-info">
                            <i class="fa fa-eye"></i>
                        </a>
                        <form method="POST" action="{{ route('orders.destroy', $order->id) }}" style="display: inline;">
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
