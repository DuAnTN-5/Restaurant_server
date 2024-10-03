@extends('admin.dashboard.layoutadmin')

@section('content')
    <div class="row wrapper border-bottom white-bg page-heading">
        <div class="col-lg-10">
            <h2>Danh Sách Sản Phẩm</h2>
            <ol class="breadcrumb">
                <li>
                    <a href="{{ route('admin.index') }}">Trang Chủ</a>
                </li>
                <li>
                    <a>Quản Lý Sản Phẩm</a>
                </li>
                <li class="active">
                    <strong>Danh Sách Sản Phẩm</strong>
                </li>
            </ol>
        </div>
        <div class="col-lg-2 text-right">
            <a href="{{ route('products.create') }}" class="btn btn-primary" style="margin-top: 20px;">Thêm Sản Phẩm</a>
        </div>
    </div>

    <div class="wrapper wrapper-content animated fadeInRight">
        @flasher_render
        <div class="row">
            <div class="col-lg-12">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5>Danh Sách Sản Phẩm</h5>
                    </div>
                    <div class="ibox-content">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered table-hover dataTables-products">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Mã SP</th>
                                        <th>Tên</th>
                                        <th>Hình ảnh</th>
                                        <th>Giá cả</th>
                                        <th>Loại</th>
                                        <th>Thứ tự</th>
                                        <th>Thao Tác</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($products as $product)
                                        <tr>
                                            <td>{{ $product->id }}</td>
                                            <td>{{ $product->product_code }}</td>
                                            <td>{{ $product->name }}</td>
                                            <td>
                                                @if($product->image_url)
                                                    <img src="{{ $product->image_url }}" alt="{{ $product->name }}" style="width: 100px; height: auto;">
                                                @else
                                                    <span>Không có hình ảnh</span>
                                                @endif
                                            </td>
                                            <td>{{ $product->price }}</td>
                                            <td>{{ $product->type }}</td>
                                            <td>{{ $product->position }}</td>
                                            <td>
                                                <a href="{{ route('products.edit', $product->id) }}" class="btn btn-success">
                                                    <i class="fa fa-edit"></i>
                                                </a>
                                                <form method="POST" action="{{ route('products.destroy', $product->id) }}" style="display: inline;">
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

                            <!-- Phân trang -->
                            <div class="d-flex justify-content-center">
                                {{ $products->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection


