<table class="table table-striped table-bordered table-hover dataTables-reservations">
    <thead>
        <tr>
            <th>ID</th>
            <th>Tên Người Dùng</th>
            <th>Tên Bàn</th>
            <th>Ngày Đặt</th>
            <th>Số Khách</th>
            <th>Yêu Cầu Đặc Biệt</th>
            <th>Trạng Thái</th>
            <th>Thao Tác</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($reservations as $reservation)
            <tr>
                <td>{{ $reservation->id }}</td>
                <td>{{ $reservation->user->name ?? 'Không có' }}</td> <!-- Hiển thị tên người dùng -->
                <td>{{ $reservation->table->number ?? 'Không có' }}</td> <!-- Hiển thị tên bàn -->
                <td>{{ $reservation->reservation_date->format('d/m/Y H:i:s') }}</td>
                <td>{{ $reservation->guest_count }}</td>
                <td>{{ $reservation->special_requests ?: 'Không có' }}</td>
                <td>
                    <form method="POST" action="{{ route('reservations.update-status') }}" style="display: inline;">
                        @csrf
                        <input type="hidden" name="id" value="{{ $reservation->id }}">
                        <select name="status" class="form-control status-select" data-id="{{ $reservation->id }}" onchange="this.form.submit()">
                            <option value="reserved" {{ $reservation->status == 'reserved' ? 'selected' : '' }}>Đặt Chỗ</option>
                            <option value="confirmed" {{ $reservation->status == 'confirmed' ? 'selected' : '' }}>Xác Nhận</option>
                            <option value="canceled" {{ $reservation->status == 'canceled' ? 'selected' : '' }}>Hủy</option>
                            <option value="pending" {{ $reservation->status == 'pending' ? 'selected' : '' }}>Đang Chờ</option>
                            <option value="in_use" {{ $reservation->status == 'in_use' ? 'selected' : '' }}>Đang Sử Dụng</option>
                        </select>
                    </form>
                </td>
                
                
                <td>
                    <a href="{{ route('reservations.edit', $reservation->id) }}" class="btn btn-success">
                        <i class="fa fa-edit"></i>
                    </a>
                    <form method="POST" action="{{ route('reservations.destroy', $reservation->id) }}" style="display: inline;">
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
