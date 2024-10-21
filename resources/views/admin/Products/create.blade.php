@extends('admin.dashboard.layoutadmin')

@section('content')
    <div class="row wrapper border-bottom white-bg page-heading">
        @flasher_render
        <div class="col-lg-10">
            <h2>Thêm Sản Phẩm Mới</h2>
            <ol class="breadcrumb">
                <li><a href="{{ route('admin.index') }}">Trang Chủ</a></li>
                <li><a>Quản Lý Sản Phẩm</a></li>
                <li class="active"><strong>Thêm Sản Phẩm</strong></li>
            </ol>
        </div>
    </div>

    <div class="wrapper wrapper-content animated fadeInRight">
        <div class="row">
            <div class="col-lg-12">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5>Thêm Sản Phẩm</h5>
                    </div>
                    <div class="ibox-content">
                        <!-- Hiển thị lỗi nếu có -->
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <!-- Form thêm sản phẩm -->
                        <form method="POST" action="{{ route('products.store') }}" enctype="multipart/form-data">
                            @csrf
                            <div class="form-group">
                                <label for="name">Tên sản phẩm:</label>
                                <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
                            </div>

                            <div class="form-group">
                                <label for="product_code">Mã sản phẩm:</label>
                                <input type="text" name="product_code" class="form-control" value="{{ old('product_code') }}" required>
                            </div>

                            <div class="form-group">
                                <label for="description">Mô tả:</label>
                                <textarea name="description" class="form-control" rows="3">{{ old('description') }}</textarea>
                            </div>

                            <div class="form-group">
                                <label for="price">Giá:</label>
                                <input type="text" name="price" class="form-control" value="{{ old('price') }}" required>
                            </div>

                            <div class="form-group">
                                <label for="image_url">Ảnh sản phẩm:</label>
                                <input type="file" name="image_url" class="form-control">
                            </div>

                            <div class="form-group">
                                <label for="type">Loại sản phẩm:</label>
                                <select name="type" class="form-control" required>
                                    <option value="food">Thực phẩm</option>
                                    <option value="beverage">Đồ uống</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="position">Thứ tự:</label>
                                <input type="number" name="position" class="form-control" value="{{ old('position') }}" required>
                            </div>

                            <!-- Nút lưu -->
                            <button type="submit" class="btn btn-primary">Lưu Sản Phẩm</button>
                            <a href="{{ route('products.index') }}" class="btn btn-secondary">Hủy</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
