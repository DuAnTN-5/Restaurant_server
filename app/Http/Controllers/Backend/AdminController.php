<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\User;

class AdminController extends Controller
{
    public function index()
    {
        // Đếm tổng số người dùng
        $totalUsers = User::count();

        // Trả về view và truyền biến $totalUsers
        return view('admin.dashboard.index', compact('totalUsers'));
    }
}
