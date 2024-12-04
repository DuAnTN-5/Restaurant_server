<table class="table table-striped table-bordered table-hover dataTables-payments">
    <thead>
        <tr>
            <th>ID</th>
            <th>Table ID</th>
            <th>Phương Thức Thanh Toán</th>
            <th>Trạng Thái</th>
            <th>Số Tiền</th>
            <th>Ngày Thanh Toán</th>
            <th>Thao Tác</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($payments as $payment)
            <tr>
                <td>{{ $payment->id }}</td>
                <td>{{ $payment->table_id->number }}</td>
                <td>{{ $payment->payment_method }}</td>
                <td>
                    <span class="badge {{ $payment->payment_status === 'completed' ? 'badge-success' : 'badge-warning' }}">
                        {{ ucfirst($payment->payment_status) }}
                    </span>
                </td>
                <td>{{ number_format($payment->total_amount, 2) }} VNĐ</td>
                <td>{{ \Carbon\Carbon::parse($payment->payment_date)->format('d/m/Y') }}</td>
                <td>
                    <a href="{{ route('payments.edit', $payment->id) }}" class="btn btn-success">
                        <i class="fa fa-edit"></i>
                    </a>
                    <form method="POST" action="{{ route('payments.destroy', $payment->id) }}" style="display: inline;">
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

{{-- Kết nối với các thư viện JavaScript nếu cần --}}
<script src="{{ asset('backend/js/status/status.js') }}"></script>
