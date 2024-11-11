@extends('admin.dashboard.layoutadmin')

@section('content')
<div class="row wrapper border-bottom white-bg page-heading">
    @flasher_render
    <div class="col-lg-10">
        <h2>Danh Sách Người Dùng Đã Xóa</h2>
        <ol class="breadcrumb">
            <li>
                <a href="{{ route('admin.index') }}">Trang Chủ</a>
            </li>
            <li>
                <a>Quản Lý Người Dùng</a>
            </li>
            <li class="active">
                <strong>Danh Sách Người Dùng Đã Xóa</strong>
            </li>
        </ol>
    </div>
    <div class="col-lg-2 text-right">
        <!-- Nút Xem Danh Sách Người Dùng -->
        <a href="{{ route('users.index') }}" class="btn btn-primary" style="margin-top: 20px; margin-left: 10px;">Danh Sách Người Dùng</a>
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
                    <h5>Danh Sách Người Dùng Đã Xóa</h5>
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
                        <!-- Include filter form for trashed users -->
                        @include('admin.users.component.filter')

                        <!-- Table for trashed users -->
                        <table class="table table-striped table-bordered table-hover dataTables-users text-center">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Tên</th>
                                    <th>Email</th>
                                    <th>Ảnh</th>
                                    <th>Vai trò</th>
                                    <th>Thao Tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($users as $user)
                                    <tr>
                                        <td>{{ $user->id }}</td>
                                        <td>{{ $user->name }}</td>
                                        <td>{{ $user->email }}</td>
                                        <td>
                                            @if ($user->image)
                                                <img src="{{ asset($user->image) }}" alt="Image" class="img-thumbnail">
                                            @else
                                                <img src="{{ asset('default-avatar.png') }}" alt="Default Image" class="img-thumbnail">
                                            @endif
                                        </td>
                                        <td style="text-align: center; vertical-align: middle;">
                                            @if ($user->role == 1)
                                                <span class="badge badge-primary">Admin</span>
                                            @elseif ($user->role == 2)
                                                <span class="badge badge-warning">Manager</span>
                                            @elseif ($user->role == 3)
                                                <span class="badge badge-info">Staff</span>
                                            @else
                                                <span class="badge badge-secondary">User</span>
                                            @endif
                                        </td>
                                        <td style="text-align: center; vertical-align: middle;">
                                            <!-- Nút khôi phục người dùng đã xóa -->
                                            <form method="POST" action="{{ route('users.restore', $user->id) }}" style="display: inline;">
                                                @csrf
                                                <button type="submit" class="btn btn-warning">
                                                    <i class="fa fa-undo"></i>
                                                </button>
                                            </form>
                                        
                                            <!-- Nút xóa vĩnh viễn người dùng đã xóa -->
                                            <form method="POST" action="{{ route('users.forceDelete', $user->id) }}" style="display: inline;" onsubmit="return confirm('Bạn có chắc chắn muốn xóa vĩnh viễn người dùng này?');">
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

                        <!-- Pagination for trashed users -->
                        @include('admin.users.component.paginate')
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
        /* Success color */
    }

    .toast-error {
        background-color: red !important;
        /* Error color */
    }

    .text-center th,
    .text-center td {
        text-align: center;
        vertical-align: middle;
    }

    .img-thumbnail {
        width: 50px;
        height: 50px;
        object-fit: cover;
    }
</style>