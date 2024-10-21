<table class="table table-striped table-bordered table-hover dataTables-users text-center">
    <thead>
        <tr>
            <th>ID</th>
            <th>Tên</th>
            <th>Email</th>
            <th>Ảnh</th>
            <th>Tình trạng</th>
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
                    <input type="checkbox" class="js-switch" data-id="{{ $user->id }}" {{ $user->status == 'active' ? 'checked' : '' }}>
                </td>
                <td style="text-align: center; vertical-align: middle;">
                    @if (isset($user->role))
                        @if ($user->role == 1)
                            <span class="badge badge-primary">Admin</span>
                        @elseif ($user->role == 2)
                            <span class="badge badge-warning">Manager</span>
                        @elseif ($user->role == 3)
                            <span class="badge badge-info">Staff</span>
                        @else
                            <span class="badge badge-secondary">User</span>
                        @endif
                    @else
                        <span class="badge badge-secondary">User</span>
                    @endif
                </td>
                <td style="text-align: center; vertical-align: middle;">
                    <a href="{{ route('users.edit', $user->id) }}" class="btn btn-success">
                        <i class="fa fa-edit"></i>
                    </a>
                    <form method="POST" action="{{ route('users.destroy', $user->id) }}" style="display: inline;">
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