<div class="row">
    @foreach ($tables as $table)
        <div class="col-md-4 col-lg-3 mb-4">
            <div class="card shadow-sm border-0">
                <div class="card-header text-white">
                    <h5 class="mb-0">Bàn Số {{ $table->number }}</h5>
                    <a href="{{ route('reservations.create') }}" class="btn btn-success btn-sm">
                        <i class="fa fa-calendar-plus-o"></i> Đặt Bàn
                    </a>                    
                </div>
                <div class="card-body">
                    {{-- <p><strong>ID:</strong> {{ $table->id }}</p> --}}
                    <p><strong>Số Chỗ Ngồi:</strong> {{ $table->seats }}</p>
                    <p><strong>Trạng Thái:</strong> {{ $table->status }}</p>
                    <p><strong>Vị Trí:</strong> {{ $table->location }}</p>
                    <p><strong>Tính Năng Đặc Biệt:</strong> {{ $table->special_features ?? 'Không có' }}</p>
                    {{-- <p><strong>Phù Hợp Cho Sự Kiện:</strong> {{ $table->suitable_for_events ? 'Có' : 'Không' }}</p> --}}
                    <p><strong>Tính Khả Dụng Tùy Chỉnh:</strong> {{ $table->custom_availability ?? 'N/A' }}</p>
                    <p><strong>Ngày Tạo:</strong> {{ $table->created_at ? $table->created_at->format('d/m/Y') : 'N/A' }}</p>
                    <p><strong>Ngày Cập Nhật:</strong> {{ $table->updated_at ? $table->updated_at->format('d/m/Y') : 'N/A' }}</p>
                </div>
                <div class="card-footer d-flex justify-content-between align-items-center" style="padding: 10px; background-color: #f9f9f9; border-top: none; border-radius: 0 0 10px 10px;">
                    <a href="{{ route('tables.edit', $table->id) }}" class="btn btn-outline-primary btn-sm" style="width: 48%; text-align: center;">
                        <i class="fa fa-edit"></i> Sửa
                    </a>
                    <form method="POST" action="{{ route('tables.destroy', $table->id) }}" style="display: inline; width: 48%;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-outline-danger btn-sm" style="width: 100%; text-align: center;" onclick="return confirm('Xác nhận xóa?')">
                            <i class="fa fa-trash"></i> Xóa
                        </button>
                    </form>
                </div>
            </div>
        </div>
    @endforeach
</div>


<style>
    .card {
        border-radius: 10px;
        overflow: hidden;
        height: 350px;
        display: flex;
        flex-direction: column;
    }

    .card-header {
        font-size: 16px;
        font-weight: bold;
        padding: 10px 15px;
        color: #fff;
        background-color: #17a2b8;
        border-radius: 10px 10px 0 0;
    }

    .card-body {
        padding: 15px;
        color: #333;
        flex: 1;
        overflow: hidden;
    }

    .card-footer {
        padding: 10px;
        background-color: #f9f9f9;
        border-top: none;
        display: flex;
        justify-content: space-between;
        align-items: center;
        border-radius: 0 0 10px 10px;
    }

    .action-button {
        width: 48%;
        text-align: center;
    }

    .btn-outline-primary {
        color: #17a2b8;
        border-color: #17a2b8;
    }

    .btn-outline-primary:hover {
        background-color: #17a2b8;
        color: #fff;
    }

    .btn-outline-danger {
        color: #dc3545;
        border-color: #dc3545;
    }

    .btn-outline-danger:hover {
        background-color: #dc3545;
        color: #fff;
    }

    .shadow-sm {
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }
</style>
