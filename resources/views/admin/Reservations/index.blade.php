@extends('admin.dashboard.layoutadmin')

@section('content')
    <div class="row wrapper border-bottom white-bg page-heading">
        <div class="col-lg-10">
            <h2>Quản Lý Đặt Bàn</h2>
            <ol class="breadcrumb">
                <li><a href="{{ route('admin.index') }}">Trang Chủ</a></li>
                <li class="active"><strong>Danh Sách Đặt Bàn</strong></li>
            </ol>
        </div>
    </div>

    <div class="wrapper wrapper-content animated fadeInRight">
        @flasher_render
        <div class="row">
            <div class="col-lg-12">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5>Danh Sách Đặt Bàn</h5>
                    </div>
                    <div class="ibox-content">
                        <!-- Form tìm kiếm và lọc -->
                        <div class="row">
                            <div class="col-lg-12 text-right">
                                <form method="GET" action="{{ route('reservations.index') }}" class="form-inline">
                                    <!-- Lọc theo ngày đặt bàn -->
                                    <div class="form-group mb-2">
                                        <input type="datetime-local" name="start_date" class="form-control"
                                            value="{{ request()->input('start_date') }}">
                                    </div>

                                    <!-- Dropdown chọn trạng thái -->
                                    <div class="form-group mx-sm-3 mb-2">
                                        <select name="status" class="form-control">
                                            <option value="">-- Chọn Tình Trạng --</option>
                                            <option value="confirmed"
                                                {{ request()->input('status') == 'confirmed' ? 'selected' : '' }}>Đã xác nhận</option>
                                            <option value="pending"
                                                {{ request()->input('status') == 'pending' ? 'selected' : '' }}>Chờ xác nhận</option>
                                            <option value="canceled"
                                                {{ request()->input('status') == 'canceled' ? 'selected' : '' }}>Đã hủy</option>
                                        </select>
                                    </div>

                                    <!-- Tìm kiếm từ khóa -->
                                    <div class="form-group mx-sm-3 mb-2">
                                        <input type="text" name="search" class="form-control"
                                            placeholder="Tìm kiếm đặt bàn..." value="{{ request()->input('search') }}">
                                    </div>

                                    <!-- Nút Tìm kiếm -->
                                    <button type="submit" class="btn btn-primary mb-2">Tìm kiếm</button>
                                </form>

                            </div>
                        </div>

                        <!-- Danh sách đặt bàn -->
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Người Đặt</th>
                                        <th>Email</th>
                                        <th>Bàn</th>
                                        <th>Ngày Đặt</th>
                                        <th>Yêu Cầu Đặc Biệt</th>
                                        <th>Trạng Thái</th>
                                        <th>Thao Tác</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($reservations as $reservation)
                                        <tr>
                                            <td>{{ $reservation->id }}</td>
                                            <td>{{ $reservation->user->name }}</td>
                                            <td>{{ $reservation->user->email }}</td>
                                            <td>Bàn số {{ $reservation->table->number }}</td>
                                            <td>{{ \Carbon\Carbon::parse($reservation->reservation_date)->format('d/m/Y H:i') }}</td>
                                            <td>{{ $reservation->special_requests ?: 'Không có' }}</td>
                                            <td>
                                                @if ($reservation->status == 'confirmed')
                                                    <span class="badge badge-success">Đã xác nhận</span>
                                                @elseif ($reservation->status == 'pending')
                                                    <span class="badge badge-warning">Chờ xác nhận</span>
                                                @elseif ($reservation->status == 'canceled')
                                                    <span class="badge badge-danger">Đã hủy</span>
                                                @endif
                                            </td>
                                            <td>
                                                <a href="{{ route('reservations.edit', $reservation->id) }}" class="btn btn-success btn-sm">
                                                    <i class="fa fa-edit"></i>
                                                </a>
                                                <form action="{{ route('reservations.destroy', $reservation->id) }}" method="POST" style="display:inline;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Bạn có chắc chắn muốn xóa không?')">
                                                        <i class="fa fa-trash"></i>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                
                            </table>
                        </div>

                        <!-- Phân trang -->
                        <div class="d-flex justify-content-center">
                            {{ $reservations->appends(request()->input())->links('pagination::bootstrap-4') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
