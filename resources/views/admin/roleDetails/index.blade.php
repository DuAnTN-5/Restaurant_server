@extends('admin.dashboard.layoutadmin')

@section('content')
    <div class="row wrapper border-bottom white-bg page-heading">
        @flasher_render
        <div class="col-lg-10">
            <h2>Danh Sách Quyền</h2>
            <ol class="breadcrumb">
                <li>
                    <a href="{{ route('admin.index') }}">Trang Chủ</a>
                </li>
                <li>
                    <a>Quản Lý Quyền</a>
                </li>
                <li class="active">
                    <strong>Danh Sách Quyền</strong>
                </li>
            </ol>
        </div>
        {{-- <div class="col-lg-2 text-right">
            <!-- Nút Xem Danh Sách Đã Xóa -->
            <a href="{{ route('users.trashed') }}" class="btn btn-warning" style="margin-top: 20px; margin-left: 10px;">Xem
                Danh Sách Đã Xóa</a>
        </div> --}}
    </div>


    @if ($errors->has('error'))
        <div class="alert alert-danger">{{ $errors->first('error') }}</div>
    @endif

    <div class="wrapper wrapper-content animated fadeInRight">
        <div class="row">
            <div class="col-lg-12">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5>Danh Sách Quyền</h5>
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
                            @include('admin.roleDetails.component.filter')

                            <!-- Include table content -->
                            @include('admin.roleDetails.component.table')

                            <!-- Include pagination -->
                            {{-- @include('admin.users.component.paginate')  --}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    @include('admin.roleDetails.component.script')
@endpush
<style>
    .switch {
        position: relative;
        display: inline-block;
        width: 34px;
        height: 20px;
    }

    .switch input {
        opacity: 0;
        width: 0;
        height: 0;
    }

    .slider {
        position: absolute;
        cursor: pointer;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: #ccc;
        transition: 0.4s;
        border-radius: 10px;
    }

    .slider:before {
        position: absolute;
        content: "";
        height: 14px;
        width: 14px;
        left: 3px;
        bottom: 3px;
        background-color: white;
        transition: 0.4s;
        border-radius: 50%;
    }

    input:checked + .slider {
        background-color: #2196F3;
    }

    input:checked + .slider:before {
        transform: translateX(14px);
    }
</style>
