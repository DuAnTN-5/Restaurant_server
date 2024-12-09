<table class="table table-striped table-bordered table-hover dataTables-payments">
    <thead>
        <tr>
            <th>ID</th>
            <th>Tên Người Dùng</th>
            <th>Số Bàn</th>
            <th>Số Tiền</th>
            <th>Mã Giảm Giá</th>
            {{-- <th>Phương Thức Thanh Toán</th> --}}
            <th>Trạng Thái</th>
            <th style="background-color:rgb(209, 194, 56)">Tổng Tiền</th>
            <th>Ngày Thanh Toán</th>
            {{-- <th>Mã Giảm Giá</th> --}}
        </tr>
    </thead>
    <tbody>
        @foreach ($payments as $payment)
        <tr>
            <td>{{ $payment->id }}</td>
            <td>{{ $payment->user_id ? $payment->user->name : 'N/A' }}</td>
            <td>{{ $payment->table_id ? $payment->table->number : 'N/A' }}</td>
            <td>{{ number_format($payment->amount, 2) }} VNĐ</td>
            <td>{{ $payment->coupon_id ? $payment->coupon->code : 'N/A' }}</td>
            {{-- <td>{{ $payment->payment_method_id ? $payment->paymentMethod->name : 'N/A' }}</td> --}}
            <td>
                <span class="badge {{ $payment->payment_status === 'completed' ? 'badge-success' : 'badge-warning' }}">
                    {{ ucfirst($payment->payment_status) }}
                </span>
            </td>
            <td>{{ number_format($payment->total_amount, 2) }} VNĐ</td>
            
            <td>{{ \Carbon\Carbon::parse($payment->payment_date)->format('d/m/Y') }}</td>
            {{-- <td>{{ $payment->coupon_id ? $payment->coupon->code : 'N/A' }}</td> --}}
        </tr>
        @endforeach
    </tbody>
</table>