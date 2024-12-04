<table class="table table-striped table-bordered table-hover dataTables-payments">
    <thead>
        <tr>
            <th>ID</th>
            <th>Tên Người Dùng</th>
            <th>Số Bàn</th>
            <th>Phương Thức Thanh Toán</th>
            <th>Trạng Thái</th>
            <th>Số Tiền</th>
            <th>Ngày Thanh Toán</th>
            <th>Mã Giảm Giá</th> <!-- Cột Mã Giảm Giá -->
            <th>Thao Tác</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($payments as $payment)
        <tr>
            <td>{{ $payment->id }}</td>
            <td>{{ $payment->user ? $payment->user->name : 'N/A' }}</td>
            <td>{{ $payment->table ? $payment->table->number : 'N/A' }}</td>
            <td>{{ $payment->paymentMethod ? $payment->paymentMethod->name : 'N/A' }}</td>
            <td>
                <span class="badge {{ $payment->payment_status === 'completed' ? 'badge-success' : 'badge-warning' }}">
                    {{ ucfirst($payment->payment_status) }}
                </span>
            </td>
            <td>{{ number_format($payment->total_amount, 2) }} VNĐ</td>
            <td>{{ \Carbon\Carbon::parse($payment->payment_date)->format('d/m/Y') }}</td>
            <td>{{ $payment->coupon ? $payment->coupon->code : 'N/A' }}</td> <!-- Hiển thị mã giảm giá -->
            <td>
                <a href="{{ route('order_items.index', ['orderId' => $payment->order->id]) }}" class="btn btn-info">
                    <i class="fa fa-eye"></i> Xem chi tiết
                </a>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
