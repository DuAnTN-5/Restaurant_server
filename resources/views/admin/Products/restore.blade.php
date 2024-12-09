@extends('admin.dashboard.layoutadmin')

@section('content')
    <div class="row wrapper border-bottom white-bg page-heading">
        <div class="col-lg-10">
            <h2>Danh Sách Sản Phẩm Đã Xóa</h2>
            <ol class="breadcrumb">
                <li><a href="{{ route('admin.index') }}">Trang Chủ</a></li>
                <li><a>Quản Lý Sản Phẩm</a></li>
                <li class="active"><strong>Danh Sách Sản Phẩm Đã Xóa</strong></li>
            </ol>
        </div>
        <div class="col-lg-2 text-right">
            <a href="{{ route('products.index') }}" class="btn btn-primary" style="margin-top: 20px;">Danh Sách Sản Phẩm</a>
        </div>
    </div>

    @if ($errors->has('error'))
        <div class="alert alert-danger">{{ $errors->first('error') }}</div>
    @endif

    <div class="wrapper wrapper-content animated fadeInRight">
        @flasher_render
        <div class="row">
            <div class="col-lg-12">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5>Danh Sách Sản Phẩm Đã Xóa</h5>
                    </div>
                    <div class="ibox-content">
                        <div class="table-responsive">
                            
                            <table class="table table-striped table-bordered table-hover dataTables-products">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Mã Sản Phẩm</th>
                                        <th>Tên</th>
                                        <th>Giá</th>
                                        <th>Hình Ảnh</th>
                                        <th>Số Lượng Tồn Kho</th>
                                        <th>Thứ Tự</th>
                                        
                                        <th>Ngày Tạo</th>
                                        <th>Thao Tác</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($products as $product)
                                        <tr>
                                            <td>{{ $product->id }}</td>
                                            <td>{{ $product->product_code }}</td>
                                            <td>
                                                {{ $product->name }}
                                                <br>
                                                <small style="color: red;">Danh mục: {{ $product->category ? $product->category->name : 'Không có' }}</small>
                                            </td>
                                            <td>
                                                <span style="color: blue;">Giá: {{ $product->price }}</span>
                                                @if ($product->discount_price)
                                                    <br><span style="color: red;">Giá giảm: {{ $product->discount_price }}</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if (!empty($product->image_url))
                                                    @php
                                                        // Chuyển đổi image_url từ JSON sang mảng
                                                        $imageArray = json_decode($product->image_url, true);
                                            
                                                        // Lấy ảnh đầu tiên trong mảng (nếu có)
                                                        $imagePath = is_array($imageArray) && count($imageArray) > 0 ? $imageArray[0] : null;
                                                    @endphp
                                            
                                                    @if ($imagePath && file_exists(public_path($imagePath)))
                                                        <img src="{{ asset($imagePath) }}" alt="Image" width="80">
                                                    @else
                                                        <img src="{{ asset('default-product.png') }}" alt="Default Image" width="50">
                                                    @endif
                                                @else
                                                    <img src="{{ asset('default-product.png') }}" alt="Default Image" width="50">
                                                @endif
                                            </td>
                                            <td>{{ $product->stock_quantity }}</td>
                                            <td>{{ $product->position }}</td>
                                            
                                            <td>{{ $product->created_at->format('d/m/Y') }}</td>
                                            <td>
                                                <!-- Nút khôi phục -->
                                                <form method="POST" action="{{ route('products.restore', $product->id) }}" style="display: inline;">
                                                    @csrf
                                                    <button type="submit" class="btn btn-warning">
                                                        <i class="fa fa-undo"></i> 
                                                    </button>
                                                </form>

                                                <!-- Nút xóa vĩnh viễn -->
                                                <form method="POST" action="{{ route('products.forceDelete', $product->id) }}" style="display: inline;" onsubmit="return confirm('Bạn có chắc chắn muốn xóa vĩnh viễn sản phẩm này?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger">
                                                        <i class="fa fa-trash"></i>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>

                            <!-- Phân trang -->
                            @include('admin.Products.component.paginate')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
