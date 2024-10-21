@extends('admin.dashboard.layoutadmin')

@section('content')
    <div class="row wrapper border-bottom white-bg page-heading">
        <div class="col-lg-10">
            <h2>Danh Sách Bàn và Sơ Đồ Nhà Hàng</h2>
            <ol class="breadcrumb">
                <li><a href="{{ route('admin.index') }}">Trang Chủ</a></li>
                <li class="active"><strong>Sơ Đồ Nhà Hàng và Danh Sách Bàn</strong></li>
            </ol>
        </div>
    </div>

    <div class="wrapper wrapper-content animated fadeInRight">
        @flasher_render
        <div class="row">
            <div class="col-lg-12">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5>Quản Lý Bàn</h5>
                    </div>
                    <div class="ibox-content">
                        <!-- Form tìm kiếm và lọc -->
                        <div class="row">
                            <div class="col-lg-12 text-right">
                                <form method="GET" action="{{ route('tables.index') }}" class="form-inline">
                                    <!-- Dropdown chọn tình trạng bàn (lọc) -->
                                    <div class="form-group mb-2">
                                        <select name="status" class="form-control">
                                            <option value="">-- Chọn Tình Trạng Bàn --</option>
                                            <option value="available" {{ request()->input('status') == 'available' ? 'selected' : '' }}>Bàn trống</option>
                                            <option value="reserved" {{ request()->input('status') == 'reserved' ? 'selected' : '' }}>Đã đặt</option>
                                            <option value="occupied" {{ request()->input('status') == 'occupied' ? 'selected' : '' }}>Có khách</option>
                                        </select>
                                    </div>

                                    <!-- Tìm kiếm theo số bàn -->
                                    <div class="form-group mx-sm-3 mb-2">
                                        <input type="text" name="search" class="form-control" placeholder="Tìm kiếm số bàn..." value="{{ request()->input('search') }}">
                                    </div>

                                    <!-- Nút Tìm kiếm -->
                                    <button type="submit" class="btn btn-primary mb-2">Tìm kiếm</button>
                                </form>
                            </div>
                        </div>

                        <div class="row">
                            <!-- Phần sơ đồ nhà hàng chiếm 30% -->
                            <div class="col-lg-4 col-md-4">
                                <h4 style="color: red;">Sơ đồ nhà hàng</h4>
                                <div style="position: relative;">
                                    <img style="height: 400px; max-width: 100%;" src="{{ asset('logo/ảnh mô hình nhà hàng.jpg') }}" alt="Sơ đồ nhà hàng" class="img-responsive">
                                </div>
                            </div>

                            <!-- Phần danh sách bàn chiếm 70% -->
                            <div class="col-lg-8 col-md-8">
                                <h4 style="color:red;">Danh sách bàn</h4>
                                <div class="row">
                                    @foreach ($tables as $table)
                                        <div class="col-lg-6 col-md-6 col-sm-12">
                                            <div class="table-box" style="border: 1px solid #ddd; padding: 20px; text-align: center; margin-bottom: 20px; cursor: pointer;" data-id="{{ $table->id }}" data-toggle="modal" data-target="#reservationModal">
                                                <h3>Bàn số {{ $table->number }}</h3>
                                                <p>{{ $table->seats }} ghế</p>
                                                <p>Tình trạng: <strong style="color:red;">{{ $table->status }}</strong></p>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                <!-- Phân trang -->
                                <div class="d-flex justify-content-center">
                                    {{ $tables->appends(request()->input())->links('pagination::bootstrap-4') }}
                                </div>
                            </div>
                        </div>
                        
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Đặt Bàn -->
    <div class="modal fade" id="reservationModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <form method="POST" action="{{ route('reservations.store') }}">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Đặt Bàn</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="table_id" id="table_id"> <!-- Ẩn để lưu table_id -->
                        
                        <!-- Cột table_id -->
                        <div class="form-group">
                            <label for="table_number">Bàn </label>
                            <input type="text" id="table_number" class="form-control" disabled> <!-- Hiển thị số bàn -->
                        </div>

                        <!-- Thêm dropdown chọn người dùng -->
                        <div class="form-group">
                            <label for="user_id">Người Đặt</label>
                            <select name="user_id" id="user_id" class="form-control select2" required>
                                <option value="">-- Chọn Người Đặt --</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
                                @endforeach
                            </select>
                        </div>
                        
                        <!-- Cột guest_count -->
                        <div class="form-group">
                            <label for="guest_count">Số Lượng Khách</label>
                            <input type="number" name="guest_count" id="guest_count" class="form-control" required>
                        </div>

                        <!-- Cột reservation_date -->
                        <div class="form-group">
                            <label for="reservation_date">Ngày Đặt Bàn</label>
                            <input type="datetime-local" name="reservation_date" id="reservation_date" class="form-control" required>
                        </div>

                        <!-- Cột special_requests -->
                        <div class="form-group">
                            <label for="special_requests">Yêu Cầu Đặc Biệt</label>
                            <textarea name="special_requests" id="special_requests" class="form-control"></textarea>
                        </div>

                        <!-- Cột status -->
                        <div class="form-group">
                            <label for="status">Trạng Thái</label>
                            <select name="status" id="status" class="form-control">
                                <option value="pending">Chờ xác nhận</option>
                                <option value="confirmed">Đã xác nhận</option>
                                <option value="canceled">Đã hủy</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
                        <button type="submit" class="btn btn-primary">Đặt Bàn</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection

<!-- Select2 CSS -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

<!-- Select2 JS -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<style>
    .select2-container .select2-selection--single {
        height: 38px; /* Tùy chỉnh chiều cao */
        border: 1px solid #ccc; /* Đường viền */
        padding: 5px; /* Khoảng cách bên trong */
    }

    .select2-container .select2-selection__arrow {
        height: 34px; /* Chiều cao của mũi tên */
    }
</style>

@push('scripts')
    <script>
        $(document).ready(function() {
            // Khởi tạo Select2 cho trường chọn người dùng
            $('#user_id').select2({
                placeholder: '-- Chọn Người Đặt --',
                allowClear: true,
                width: '100%' // Tự động căn chỉnh chiều rộng
            });
        });

        // Khi click vào bàn, mở modal và gán giá trị id của bàn vào form
        $('.table-box').click(function() {
            var tableId = $(this).data('id');
            $('#table_id').val(tableId); // Gán giá trị id của bàn vào input ẩn trong form
        });

        // Khi click vào vị trí bàn trên sơ đồ, mở modal đặt bàn
        $('.table-marker').click(function() {
            var tableId = $(this).data('id');
            $('#table_id').val(tableId); // Gán giá trị id của bàn vào input ẩn trong form
            $('#reservationModal').modal('show');
        });
    </script>
@endpush
