<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleController extends Controller
{
    public function index()
    {
        $roles = Role::all();
        $permissions = Permission::all();

        $rolesWithPermissions = $roles->map(function ($role) use ($permissions) {
            $assignedPermissions = $role->permissions; // Quyền đã gán cho vai trò
            $unassignedPermissions = $permissions->diff($assignedPermissions); // Quyền chưa gán

            return [
                'role' => $role,
                'assigned_permissions' => $assignedPermissions,
                'unassigned_permissions' => $unassignedPermissions,
            ];
        });

        return response()->json(['roles' => $rolesWithPermissions]);
    }

    public function togglePermission(Request $request, Role $role)
    {
        $permissionName = $request->input('permission_name');
        $checked = $request->input('checked');

        $permission = Permission::where('name', $permissionName)->firstOrFail();
        $users = $role->users; // Lấy danh sách người dùng thuộc vai trò
        // dd(auth()->user()->roles);
        // Role::findByName('quan_ly');
        // $quanLy->givePermissionTo(['quan_ly_bai_viet', 'quan_ly_san_pham', 'quan_ly_don_hang', 'quan_ly_ban_dat']);

        if ($checked) {
            // Gán quyền cho vai trò
            $role->givePermissionTo($permission);


            foreach ($users as $user) {
                $user->syncPermissions($user->getPermissionsViaRoles()); // Đồng bộ quyền kế thừa
            }
        } else {
            // Thu hồi quyền khỏi vai trò
            $role->revokePermissionTo($permission);

            // Cập nhật quyền kế thừa cho tất cả người dùng thuộc vai trò

            foreach ($users as $user) {
                $user->syncPermissions($user->getPermissionsViaRoles()); // Đồng bộ quyền kế thừa
            }
        }

        // Làm mới cache quyền
        app(\Spatie\Permission\PermissionRegistrar::class)->forgetCachedPermissions();

        return response()->json(['success' => true]);
    }


    public function assignPermission(Request $request, Role $role)
    {
        $role->syncPermissions($request->permissions);
        return response()->json([
            'data' => $request->all(),
            'status' => true,
            'success' => true,
            'message' => 'Quyền đã được cập nhật'
        ], 200);
    }
    public function store(Request $request)
    {
        // Validate input
        $request->validate([
            'role_name' => 'required|string|unique:roles,name|max:255',
        ]);

        // Tạo role
        $role = Role::create(['name' => $request->role_name]);

        // Trả về phản hồi hoặc redirect
        return response()->json([
            'data' => [ 'role_id' => $role->id, 'role_name' => $role->name],
            'status' => true,
            'success' => true,
            'message' => 'Tạo phân quyền thành công'
        ], 200);
    }
    public function createRoles()
    {
        // Tạo vai trò
        Role::create(['name' => 'nhan_vien']);
        Role::create(['name' => 'quan_ly']);

        // Tạo quyền
        Permission::create(['name' => 'quan_ly_bai_viet']);
        Permission::create(['name' => 'quan_ly_san_pham']);
        Permission::create(['name' => 'quan_ly_don_hang']);
        Permission::create(['name' => 'quan_ly_ban_dat']);

        // Gán quyền cho vai trò
        $quanLy = Role::findByName('quan_ly');
        $quanLy->givePermissionTo(['quan_ly_bai_viet', 'quan_ly_san_pham', 'quan_ly_don_hang', 'quan_ly_ban_dat']);

        $nhanVien = Role::findByName('nhan_vien');
        $nhanVien->givePermissionTo(['quan_ly_don_hang', 'quan_ly_ban_dat']);

        return response()->json([
            // 'data' => $request->all(),
            
            'status' => true,
            'success' => true,
            'message' => 'Cập nhật trạng thái phân quyền thành công'
        ], 200);
    }

    public function rename(Request $request, Role $role)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:roles,name,' . $role->id,
        ]);

        try {
            $role->name = $request->name;
            $role->save();

            return response()->json([
                'success' => true,
                'message' => 'Tên vai trò đã được cập nhật.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi cập nhật tên vai trò.',
            ], 500);
        }
    }

    public function destroy(Role $role)
    {
        try {
            $role->delete();
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }
}
