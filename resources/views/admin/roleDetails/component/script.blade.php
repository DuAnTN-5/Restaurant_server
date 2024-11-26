
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<script>
    $(document).ready(function() {
            // Xóa vai trò
        document.querySelectorAll('.delete-role').forEach((button) => {
            button.addEventListener('click', function () {
                const roleId = this.dataset.roleId;

                if (confirm('Bạn có chắc chắn muốn xóa vai trò này?')) {
                    fetch(`{{ url('/') }}/roles/${roleId}/delete`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        },
                    })
                        .then((response) => response.json())
                        .then((data) => {
                            if (data.success == true) {
                                // Xóa hàng khỏi bảng
                                document.getElementById(`role-row-${roleId}`).remove();
                                toastr.success('Vai trò đã được xóa thành công');
                            } else {
                                toastr.error('Xóa vai trò thất bại');
                            }
                        })
                        .catch((error) => {
                            console.error('Error:', error);
                            toastr.error('Có lỗi xảy ra khi xóa vai trò');
                        });
                }
            });
        });

        // Tạo vai trò mới
        $('#create-role-form').on('submit', function (e) {
            e.preventDefault();
            const roleName = $('#role-name').val();
            const newRow = document.createElement('tr');
            $.ajax({
                url: '{{ url('/') }}/roles', // Route xử lý tạo vai trò
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                },
                data: { role_name: roleName },
                success: function (response) {
                    if (response.success) {
                        const roleId = response.data.role_id; // Role ID trả về từ server
                        newRow.id = `role-row-${roleId}`;
                        newRow.innerHTML = `
                            <td>${roleName}</td>
                            @foreach ($permissions as $permission)
                                <td>
                                    <label class="switch">
                                        <input type="checkbox" class="permission-switch" data-id="${roleId}" data-permission-name="{{ $permission->name }}">
                                        <span class="slider"></span>
                                    </label>
                                </td>
                            @endforeach
                            <td>
                                <button class="btn btn-danger btn-sm delete-role" data-role-id="${roleId}">Xóa</button>
                            </td>
                        `;
                        document.getElementById('roles-table-body').appendChild(newRow);

                        // Gắn sự kiện toggle và xóa cho hàng mới
                        toastr.success('Tạo vai trò thành công');
                        $('#role-name').val('')
                    } else {
                        toastr.error('Tạo vai trò thất bại');
                    }
                },
                error: function (error) {
                    console.error('Error:', error);
                    toastr.error('Có lỗi xảy ra khi tạo vai trò ' + error?.responseJSON?.message);
                },
            });
        });

        // Bật/tắt quyền cho vai trò
        document.querySelectorAll('.permission-switch').forEach((switchElement) => {
            switchElement.addEventListener('change', function () {
                const roleId = this.dataset.id;
                const permissionName = this.dataset.name;
                const isChecked = this.checked;

                fetch(`{{ url('/') }}/roles/${roleId}/permissions/toggle`, {
                    method: 'PUT',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        permission_name: permissionName,
                        checked: isChecked,
                    }),
                })
                    .then((response) => response.json())
                    .then((data) => {
                        if (data.success) {
                            toastr.success('Cập nhật thành công');
                        } else {
                            toastr.error('Cập nhật thất bại');
                        }
                    })
                    .catch((error) => {
                        console.error('Error:', error);
                        toastr.error('Có lỗi xảy ra khi cập nhật');
                    });
            });
        });
        const rolesTableBody = document.getElementById('roles-table-body');

        // Event Delegation for Rename Role
        rolesTableBody.addEventListener('click', function (event) {
            if (event.target.classList.contains('rename-role')) {
                const roleId = event.target.dataset.roleId;
                const roleRow = document.getElementById(`role-row-${roleId}`);
                const roleNameCell = roleRow.querySelector('.role-name');
                const currentName = roleNameCell.textContent.trim();

                // Prompt for new role name
                const newName = prompt('Nhập tên vai trò mới:', currentName);
                if (!newName || newName === currentName) {
                    alert('Tên vai trò không thay đổi.');
                    return;
                }

                // Update Role Name via AJAX
                fetch(`{{ url('/') }}/roles/${roleId}/rename`, {
                    method: 'PUT',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({ name: newName }),
                })
                    .then((response) => response.json())
                    .then((data) => {
                        if (data.success) {
                            roleNameCell.textContent = newName; // Update name in the table
                            alert('Tên vai trò đã được cập nhật thành công.');
                        } else {
                            alert('Cập nhật tên vai trò thất bại.');
                        }
                    })
                    .catch((error) => {
                        console.error('Error:', error);
                        alert('Có lỗi xảy ra khi cập nhật tên vai trò.');
                    });
            }
        });
    });
</script>
