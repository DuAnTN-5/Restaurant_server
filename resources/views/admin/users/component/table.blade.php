<table class="table table-striped table-bordered table-hover dataTables-users">
    <thead>
        <tr>
            <th>ID</th>
            <th>Tên</th>
            <th>Email</th>
            <th>Ảnh</th>
            <th>Tình trạng</th>
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
                        <img src="{{ asset($user->image) }}" alt="Image" style="width: 50px; height: 50px; object-fit: cover;">
                    @else
                        <img src="{{ asset('default-avatar.png') }}" alt="Default Image" style="width: 50px; height: 50px; object-fit: cover;">
                    @endif
                </td>
                <td>
                    <button class="btn btn-status-toggle" data-id="{{ $user->id }}">
                        @if ($user->status == 'active')
                            <span class="badge badge-success">Active</span>
                        @else
                            <span class="badge badge-danger">Inactive</span>
                        @endif
                    </button>
                </td>
                <td>
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
