@extends('admin.dashboard.layoutadmin')

@section('content')
<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
        <h2>Danh Sách Danh Mục Sản Phẩm Đã Xóa</h2>
        <ol class="breadcrumb">
            <li><a href="{{ route('admin.index') }}">Trang Chủ</a></li>
            <li><a>Quản Lý Danh Mục</a></li>
            <li class="active"><strong>Danh Sách Danh Mục Sản Phẩm Đã Xóa</strong></li>
        </ol>
    </div>
    <div class="col-lg-2 text-right">
        <!-- Nút Quay Lại Danh Sách -->
        <a href="{{ route('ProductCategories.index') }}" class="btn btn-primary" style="margin-top: 20px;">Danh Sách Danh Mục</a>
    </div>
</div>

@if (session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

@if (session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
@endif

<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>Danh Sách Danh Mục Sản Phẩm Đã Xóa</h5>
                </div>
                <div class="ibox-content">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover dataTables-categories">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Tên</th>
                                    <th>Mô Tả</th>
                                    <th>Thứ Tự</th>
                                    
                                    <th>Thao Tác</th>
                                </tr>
                            </thead>

                            <tbody>
                                @forelse ($productCategories as $productCategory)
                                    <tr>
                                        <td>{{ $productCategory->id }}</td>
                                        <td>
                                            {{ $productCategory->name }}
                                            <small style="color: red; display: block; margin-top: 5px;">
                                                Danh mục cha: {{ $productCategory->parent ? $productCategory->parent->name : 'Không có' }}
                                            </small>
                                        </td>
                                        <td>{{ $productCategory->description }}</td>
                                        <td>{{ $productCategory->position }}</td>
                                        
                                        <td style="text-align: center;">
                                            <!-- Nút khôi phục -->
                                            <form method="POST" action="{{ route('ProductCategories.restore', $productCategory->id) }}" style="display: inline;">
                                                @csrf
                                                <button type="submit" class="btn btn-warning">
                                                    <i class="fa fa-undo"></i>
                                                </button>
                                            </form>

                                            <!-- Nút xóa vĩnh viễn -->
                                            <form method="POST" action="{{ route('ProductCategories.forceDelete', $productCategory->id) }}" style="display: inline;" onsubmit="return confirm('Bạn có chắc chắn muốn xóa vĩnh viễn danh mục này?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger">
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center">Không có danh mục nào</td>
                                    </tr>
                                @endforelse
                            </tbody>

                            <tfoot>
                                <tr>
                                    <th>ID</th>
                                    <th>Tên</th>
                                    <th>Mô Tả</th>
                                    <th>Thứ Tự</th>
                                   
                                    <th>Thao Tác</th>
                                </tr>
                            </tfoot>
                        </table>
                        <!-- Pagination -->
                        {{-- <div class="d-flex justify-content-center">
                            {{ $productCategories->links() }}
                        </div> --}}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
