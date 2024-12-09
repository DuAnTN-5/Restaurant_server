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
                    <input type="checkbox" class="js-switch"
                           data-id="{{ $user->id }}"
                           data-type="user"
                           {{ $user->status === 'active' ? 'checked' : '' }}>
                </td>


                <td style="text-align: center; vertical-align: middle;">
                    @php 
                        $roleName = implode(',', $user->getRoleNames()->toArray()); 
                    @endphp
                
                    @if ($roleName == 'Admin')
                        <span class="badge badge-primary">
                    @elseif ($roleName == 'Manager')
                        <span class="badge badge-success">
                    @elseif ($roleName == 'Staff')
                        <span class="badge badge-warning">
                    @elseif ($roleName == 'User')
                        <span class="badge badge-info">
                    @else
                        <span class="badge badge-secondary">
                    @endif
                
                    {{ $roleName }}</span>
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
