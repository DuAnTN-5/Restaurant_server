@extends('admin.dashboard.layoutadmin')

@section('content')
    <div class="row wrapper border-bottom white-bg page-heading">
        @flasher_render
        <div class="col-lg-10">
            <h2>Danh Sách Bài Viết Đã Xóa</h2>
            <ol class="breadcrumb">
                <li>
                    <a href="{{ route('admin.index') }}">Trang Chủ</a>
                </li>
                <li>
                    <a>Quản Lý Bài Viết</a>
                </li>
                <li class="active">
                    <strong>Danh Sách Bài Viết Đã Xóa</strong>
                </li>
            </ol>
        </div>
        <div class="col-lg-2 text-right">
            <!-- Nút Xem Danh Sách Bài Viết -->
            <a href="{{ route('posts.index') }}" class="btn btn-primary" style="margin-top: 20px; margin-left: 10px;">Danh Sách
                Bài Viết</a>
        </div>
    </div>

    @if ($errors->has('error'))
        <div class="alert alert-danger">{{ $errors->first('error') }}</div>
    @endif

    <div class="wrapper wrapper-content animated fadeInRight">
        <div class="row">
            <div class="col-lg-12">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5>Danh Sách Bài Viết Đã Xóa</h5>
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
                            <!-- Include filter form for trashed posts -->
                            @include('admin.posts.component.filter')

                            <!-- Table for trashed posts -->
                            <table class="table table-striped table-bordered table-hover dataTables-posts text-center">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Tiêu Đề</th>
                                        <th>Ảnh</th>
                                        <th>Danh Mục</th>
                                        <th>Thao Tác</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($posts as $post)
                                        <tr>
                                            <td>{{ $post->id }}</td>
                                            <td>{{ $post->title }}</td>
                                            <td>
                                                @if ($post->image_url && file_exists(public_path($post->image_url)))
                                                    <img src="{{ asset($post->image_url) }}" alt="Image" width="80">
                                                @else
                                                    <img src="{{ asset('default-post.png') }}" alt="Default Image"
                                                        width="50">
                                                @endif
                                            </td>
                                            <td>{{ $post->category->name ?? 'Không có danh mục' }}</td>

                                            <td style="text-align: center; vertical-align: middle;">
                                                <!-- Nút khôi phục bài viết đã xóa -->
                                                <form method="POST" action="{{ route('posts.restore', $post->id) }}"
                                                    style="display: inline;">
                                                    @csrf
                                                    <button type="submit" class="btn btn-warning">
                                                        <i class="fa fa-undo"></i>
                                                    </button>
                                                </form>

                                                <!-- Nút xóa vĩnh viễn bài viết đã xóa -->
                                                <form method="POST" action="{{ route('posts.forceDelete', $post->id) }}"
                                                    style="display: inline;"
                                                    onsubmit="return confirm('Bạn có chắc chắn muốn xóa vĩnh viễn bài viết này?');">
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

                            <!-- Pagination for trashed posts -->
                            @include('admin.Posts.component.paginate')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

<style>
    .toast-success {
        background-color: #1AB394 !important;
    }

    .toast-error {
        background-color: red !important;
    }

    .text-center th,
    .text-center td {
        text-align: center;
        vertical-align: middle;
    }
</style>
