<?php

namespace App\Policies;

use App\Models\Staff;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class StaffPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        // Chỉ cho phép người dùng có vai trò 'admin' xem danh sách nhân viên
        return $user->role === 'admin';
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Staff $staff): bool
    {
        // Chỉ cho phép người dùng có vai trò 'admin' hoặc 'manager' xem thông tin nhân viên
        return $user->role === 'admin' || $user->role === 'manager';
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        // Chỉ cho phép người dùng có vai trò 'admin' tạo nhân viên
        return $user->role === 'admin';
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Staff $staff): bool
    {
        // Chỉ cho phép người dùng có vai trò 'admin' hoặc 'manager' cập nhật thông tin nhân viên
        return $user->role === 'admin' || $user->role === 'manager';
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Staff $staff): bool
    {
        // Chỉ cho phép người dùng có vai trò 'admin' xóa nhân viên
        return $user->role === 'admin';
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Staff $staff): bool
    {
        // Chỉ cho phép người dùng có vai trò 'admin' khôi phục nhân viên đã bị xóa mềm
        return $user->role === 'admin';
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Staff $staff): bool
    {
        // Chỉ cho phép người dùng có vai trò 'admin' xóa vĩnh viễn nhân viên
        return $user->role === 'admin';
    }
}
