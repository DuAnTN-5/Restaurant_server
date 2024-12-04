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
                    <form method="POST" action="{{ route('orders.update-status') }}" style="display: inline;">
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
                </td>
                {{-- <td>
                    <!-- Nút "Xem" luôn hiển thị -->
                    <a href="{{ route('order_items.index', ['orderId' => $order->id]) }}" class="btn btn-info">
                        <i class="fa fa-eye"></i> Xem
                    </a>

                    <!-- Nút "Thanh Toán" chỉ hiển thị khi trạng thái là "Hoàn Thành" -->
                    @if($order->status == 'completed')
                        <a href="{{ route('orders.pay', ['orderId' => $order->id]) }}" class="btn btn-warning">
                            <i class="fa fa-money"></i> Thanh Toán
                        </a>
                    @else
                        <!-- Nút "Đặt Món", "Sửa", và "Xóa" chỉ hiển thị khi trạng thái không phải là "Hoàn Thành" -->
                        <a href="javascript:void(0);" class="btn btn-info" onclick="openOrderItemModal({{ $order->id }})">
                            <i class="fa fa-plus"></i> Đặt Món
                        </a>
                        <a href="{{ route('orders.edit', $order->id) }}" class="btn btn-success">
                            <i class="fa fa-edit"></i>
                        </a>
                        <form method="POST" action="{{ route('orders.destroy', $order->id) }}" style="display: inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger" onclick="return confirm('Xác nhận xóa?')">
                                <i class="fa fa-trash"></i>
                            </button>
                        </form>
                    @endif
                </td> --}}
                <td>
                    <!-- Nút "Xem" luôn hiển thị -->
                    <a href="{{ route('order_items.index', ['orderId' => $order->id]) }}" class="btn btn-info">
                        <i class="fa fa-eye"></i> Xem
                    </a>
                
                    <!-- Nút "Thanh Toán" chỉ hiển thị khi trạng thái là "Hoàn Thành" -->
                    @if($order->status == 'completed' && !$order->is_paid)
                        {{-- <a href="javascript:void(0);" class="btn btn-warning" onclick="openPaymentModal({{ $order->id }})">
                            <i class="fa fa-money"></i> Thanh Toán
                        </a> --}}
                    @else
                        <!-- Các nút thao tác khác nếu trạng thái không phải là "Hoàn Thành" hoặc đã thanh toán -->
                        <a href="javascript:void(0);" class="btn btn-info" onclick="openOrderItemModal({{ $order->id }})">
                            <i class="fa fa-plus"></i> Đặt Món
                        </a>
                        <a href="{{ route('orders.edit', $order->id) }}" class="btn btn-success">
                            <i class="fa fa-edit"></i>
                        </a>
                        <form method="POST" action="{{ route('orders.destroy', $order->id) }}" style="display: inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger" onclick="return confirm('Xác nhận xóa?')">
                                <i class="fa fa-trash"></i>
                            </button>
                        </form>
                    @endif
                </td>
                
            </tr>
        @endforeach
    </tbody>
</table>
