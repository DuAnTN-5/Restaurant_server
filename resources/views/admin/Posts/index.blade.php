@extends('admin.dashboard.layoutadmin')

@section('content')
    <div class="row wrapper border-bottom white-bg page-heading">
        <div class="col-lg-10">
            <h2>Danh Sách Bài Viết</h2>
            <ol class="breadcrumb">
                <li>
                    <a href="{{ route('admin.index') }}">Trang Chủ</a>
                </li>
                <li class="active">
                    <strong>Danh Sách Bài Viết</strong>
                </li>
            </ol>
        </div>
        <div class="col-lg-2 text-right">
            <a href="{{ route('posts.create') }}" class="btn btn-primary" style="margin-top: 20px;">Tạo Bài Viết</a>
        </div>
    </div>

    <div class="wrapper wrapper-content animated fadeInRight">
        <div class="row">
            <div class="col-lg-12">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5>Danh Sách Bài Viết</h5>
                    </div>
                    <div class="ibox-content">
                        <!-- Form tìm kiếm và lọc danh mục -->
                        @include('admin.posts.component.filter') <!-- Form tìm kiếm -->

                        @include('admin.posts.component.table')

                        <!-- Phân trang -->
                        @include('admin.posts.component.paginate')
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    @include('admin.posts.component.script')
@endpush
