<table class="table table-striped table-bordered table-hover dataTables-roles text-center">
    <thead>
        <tr>
            <th>Vai Trò</th>
            @foreach ($permissions as $permission)
                <th>{{ $permission->name }}</th>
            @endforeach
        </tr>
    </thead>
    <tbody id="roles-table-body">
        @foreach ($roles as $role)
                <tr id="role-row-{{ $role->id }}">
                    <td class="role-name">{{ $role->name }}</td>
                    @foreach ($permissions as $permission)
                        <td>
                            <label class="switch">
                                <input
                                    type="checkbox"
                                    class="permission-switch"
                                    data-id="{{ $role->id }}"
                                    data-name="{{ $permission->name }}"
                                    data-type="{{ $role->permissions->contains($permission) }}"
                                    {{ $role->permissions->contains($permission) ? 'checked' : '' }}>
                                <span class="slider"></span>
                            </label>
                        </td>
                    @endforeach
                    <td>
                        <button class="btn btn-primary btn-sm rename-role" data-role-id="{{ $role->id }}">Đổi tên</button>
                        <button class="btn btn-danger btn-sm delete-role" data-role-id="{{ $role->id }}">Xóa</button>
                    </td>
                </tr>
            @endforeach
    </tbody>
</table>
<style>
    .table .js-switch {
        display: inline-block;
    }

    .text-center th,
    .text-center td {
        text-align: center;
        vertical-align: middle;
    }
</style>
