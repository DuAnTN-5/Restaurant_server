<table class="table table-striped table-bordered table-hover dataTables-coupons">
    <thead>
        <tr>
            <th>ID</th>
            <th>Mã Coupon</th>
            <th>Loại Giảm Giá</th>
            <th>Giá Trị</th>
            <th>Ngày Bắt Đầu</th>
            <th>Ngày Kết Thúc</th>
            <th>Giới Hạn Sử Dụng</th>
            <th>Giá Trị Đơn Hàng Tối Thiểu</th>
            <th>Trạng Thái</th>
            <th>Thao Tác</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($coupons as $coupon)
            <tr>
                <td>{{ $coupon->id }}</td>
                <td>{{ $coupon->code ?? 'Chưa có mã' }}</td>
                <td>{{ $coupon->discount_type == 'percentage' ? 'Phần Trăm' : 'Cố Định' }}</td>
                <td>
                    @if($coupon->discount_type == 'percentage')
                        {{ $coupon->value }}%
                    @else
                        {{ number_format($coupon->value, 0, ',', '.') }} VND
                    @endif
                </td>
                <td>{{ \Carbon\Carbon::parse($coupon->start_date)->format('d/m/Y') }}</td>
                <td>{{ \Carbon\Carbon::parse($coupon->end_date)->format('d/m/Y') }}</td>
                <td>{{ $coupon->usage_limit ?? 'Không giới hạn' }}</td>
                <td>{{ number_format($coupon->minimum_order_value, 0, ',', '.') }} VND</td>
                <td style="text-align: center; vertical-align: middle;">
                    <input type="checkbox" class="js-switch" 
                           data-id="{{ $coupon->id }}" 
                           data-type="coupons"
                           {{ $coupon->status == 'active' ? 'checked' : '' }}>
                </td>
                

                <td style="text-align: center; vertical-align: middle;">
                    <a href="{{ route('coupons.edit', $coupon->id) }}" class="btn btn-success">
                        <i class="fa fa-edit"></i>
                    </a>
                    <form action="{{ route('coupons.destroy', $coupon->id) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger" onclick="return confirm('Bạn có chắc chắn muốn xóa mã coupon này?')">
                            <i class="fa fa-trash"></i>
                        </button>
                    </form>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
