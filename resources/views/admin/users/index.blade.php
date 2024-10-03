@extends('admin.dashboard.layoutadmin')

@section('content')
    <div class="row wrapper border-bottom white-bg page-heading">
        @flasher_render
        <div class="col-lg-10">
            <h2>Danh Sách Người Dùng</h2>
            <ol class="breadcrumb">
                <li>
                    <a href="{{ route('admin.index') }}">Trang Chủ</a>
                </li>
                <li>
                    <a>Quản Lý Người Dùng</a>
                </li>
                <li class="active">
                    <strong>Danh Sách Người Dùng</strong>
                </li>
            </ol>
        </div>
        <div class="col-lg-2 text-right">
            <a href="{{ route('users.create') }}" class="btn btn-primary" style="margin-top: 20px;">Thêm Người Dùng</a>
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
                        <h5>Danh Sách Người Dùng</h5>
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
                            <!-- Include filter form -->
                            @include('admin.users.component.filter')

                            <!-- Include table content -->
                            @include('admin.users.component.table')

                            <!-- Include pagination -->
                            @include('admin.users.component.paginate')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    @include('admin.users.component.script')
@endpush
