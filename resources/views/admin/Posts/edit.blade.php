@extends('admin.dashboard.layoutadmin')

@section('content')
    <div class="row wrapper border-bottom white-bg page-heading">
        <div class="col-lg-10">
            <h2>Chỉnh Sửa Bài Viết</h2>
            <ol class="breadcrumb">
                <li><a href="{{ route('admin.index') }}">Trang Chủ</a></li>
                <li><a href="{{ route('posts.index') }}">Danh Sách Bài Viết</a></li>
                <li class="active"><strong>Chỉnh Sửa Bài Viết</strong></li>
            </ol>
        </div>
    </div>

    <div class="wrapper wrapper-content animated fadeInRight">
        <div class="row">
            <div class="col-lg-12">
                <div class="ibox float-e-margins">
                    <div class="ibox-title"><h5>Chỉnh Sửa Bài Viết</h5></div>
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

                        <!-- Include form chỉnh sửa bài viết -->
                        @include('admin.posts.component.form', ['post' => $post, 'postCategories' => $postCategories])
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    @include('admin.posts.component.script') <!-- Script xử lý trạng thái -->
@endpush