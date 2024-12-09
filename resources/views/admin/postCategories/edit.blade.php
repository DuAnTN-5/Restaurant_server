@extends('admin.dashboard.layoutadmin')

@section('content')
    <div class="row wrapper border-bottom white-bg page-heading">
        <div class="col-lg-10">
            <h2>Chỉnh Sửa Loại Bài Viết</h2>
            <ol class="breadcrumb">
                <li><a href="{{ route('admin.index') }}">Trang Chủ</a></li>
                <li><a>Quản Lý Loại Bài Viết</a></li>
                <li class="active"><strong>Chỉnh Sửa Loại Bài Viết</strong></li>
            </ol>
        </div>
        <div class="col-lg-2 text-right">
            <a href="{{ route('postCategories.index') }}" class="btn btn-primary" style="margin-top: 20px;">Danh Sách</a>
        </div>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="wrapper wrapper-content animated fadeInRight">
        <div class="row">
            <div class="col-lg-12">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5>Chỉnh Sửa Loại Bài Viết</h5>
                    </div>
                    <div class="ibox-content">
                        @include('admin.postCategories.component.form') <!-- Include form -->
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    @include('admin.postCategories.component.script') <!-- Script xử lý trạng thái -->
@endpush