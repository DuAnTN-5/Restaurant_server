@extends('admin.dashboard.layoutadmin')

@section('content')
    <div class="row wrapper border-bottom white-bg page-heading">
        @flasher_render
        <div class="col-lg-10">
            <h2>Chỉnh Sửa Sản Phẩm</h2>
            <ol class="breadcrumb">
                <li><a href="{{ route('admin.index') }}">Trang Chủ</a></li>
                <li><a>Quản Lý Sản Phẩm</a></li>
                <li class="active"><strong>Chỉnh Sửa Sản Phẩm</strong></li>
            </ol>
        </div>
    </div>

    <div class="wrapper wrapper-content animated fadeInRight">
        <div class="row">
            <div class="col-lg-12">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5>Chỉnh Sửa Sản Phẩm</h5>
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

                        <!-- Form chỉnh sửa sản phẩm -->
                        <form method="POST" action="{{ route('products.update', $product->id) }}" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <div class="form-group">
                                <label for="name">Tên sản phẩm:</label>
                                <input type="text" name="name" class="form-control" value="{{ old('name', $product->name) }}" required>
                            </div>

                            <div class="form-group">
                                <label for="product_code">Mã sản phẩm:</label>
                                <input type="text" name="product_code" class="form-control" value="{{ old('product_code', $product->product_code) }}" required>
                            </div>

                            <div class="form-group">
                                <label for="description">Mô tả:</label>
                                <textarea name="description" class="form-control" rows="3">{{ old('description', $product->description) }}</textarea>
                            </div>

                            <div class="form-group">
                                <label for="price">Giá:</label>
                                <input type="text" name="price" class="form-control" value="{{ old('price', $product->price) }}" required>
                            </div>

                            <div class="form-group">
                                <label for="image_url">Ảnh sản phẩm:</label>
                                <input type="file" name="image_url" class="form-control">
                                @if($product->image_url)
                                    <img src="{{ asset($product->image_url) }}" alt="{{ $product->name }}" style="width: 100px;">
                                @endif
                            </div>

                            <div class="form-group">
                                <label for="type">Loại sản phẩm:</label>
                                <select name="type" class="form-control" required>
                                    <option value="food" {{ $product->type == 'food' ? 'selected' : '' }}>Thực phẩm</option>
                                    <option value="beverage" {{ $product->type == 'beverage' ? 'selected' : '' }}>Đồ uống</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="position">Thứ tự:</label>
                                <input type="number" name="position" class="form-control" value="{{ old('position', $product->position) }}" required>
                            </div>

                            <!-- Nút lưu -->
                            <button type="submit" class="btn btn-primary">Cập Nhật Sản Phẩm</button>
                            <a href="{{ route('products.index') }}" class="btn btn-secondary">Hủy</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
