@extends('admin.dashboard.layoutadmin')

@section('content')
    <div class="row wrapper border-bottom white-bg page-heading">
        <div class="col-lg-10">
            <h2>Danh Sách Phương Thức Thanh Toán</h2>
            <ol class="breadcrumb">
                <li><a href="{{ route('admin.index') }}">Trang Chủ</a></li>
                <li><a>Quản Lý Thanh Toán</a></li>
                <li class="active"><strong>Danh Sách Phương Thức Thanh Toán</strong></li>
            </ol>
        </div>
        <div class="col-lg-2 text-right">
            <a href="{{ route('payment_methods.create') }}" class="btn btn-primary" style="margin-top: 20px;">Thêm Phương Thức</a>
        </div>
    </div>

    @if ($errors->has('error'))
        <div class="alert alert-danger">{{ $errors->first('error') }}</div>
    @endif

    <div class="wrapper wrapper-content animated fadeInRight">
        @flasher_render
        <div class="row">
            <!-- Danh sách phương thức thanh toán dưới dạng bảng (table) -->
            <div class="col-lg-8">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5>Danh Sách Phương Thức Thanh Toán</h5>
                        <div class="ibox-tools">
                            <a class="collapse-link">
                                <i class="fa fa-chevron-up"></i>
                            </a>
                            <a class="close-link">
                                <i class="fa fa-times"></i>
                            </a>
                        </div>
                    </div>
                    <div class="ibox-content">
                        <div class="table-responsive">
                            <!-- Bao gồm bảng dữ liệu -->
                            @include('admin.payment_methods.component.table')
                        </div>
                    </div>
                </div>
            </div>

            <!-- Form thêm mới phương thức thanh toán -->
            <div class="col-lg-4">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5>Thêm Phương Thức Thanh Toán Mới</h5>
                    </div>
                    <div class="ibox-content">
                        <form method="POST" action="{{ route('payment_methods.store') }}" enctype="multipart/form-data">
                            @csrf

                            <!-- Tên phương thức thanh toán -->
                            <div class="form-group">
                                <label for="name">Tên phương thức:</label>
                                <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
                            </div>

                            <!-- Icon -->
                            <div class="form-group">
                                <label for="icon">Chọn icon:</label>
                                <input type="file" name="icon" id="icon" class="form-control">
                            </div>

                            <!-- Trạng thái -->
                            <div class="form-group">
                                <label for="is_active">Trạng thái:</label>
                                <select name="is_active" class="form-control" required>
                                    <option value="1" selected>Kích hoạt</option>
                                    <option value="0">Vô hiệu</option>
                                </select>
                            </div>

                            <!-- Nút hành động -->
                            <div class="form-group text-right">
                                <button type="submit" class="btn btn-primary">Lưu Phương Thức</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

