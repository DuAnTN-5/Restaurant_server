<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\RoleDetail;
use App\Models\Staff;
use Flasher\Prime\FlasherInterface;

class RolePermissionsController extends Controller
{
    protected $flasher;

    // Constructor để khởi tạo Flasher
    public function __construct(FlasherInterface $flasher)
    {
        $this->flasher = $flasher;
    }

    // Hiển thị danh sách phân quyền
    public function index()
    {
        // Lấy danh sách phân quyền
        $roleDetails = RoleDetail::all();
        return view('admin.roleDetails.index', compact('roleDetails'));
    }

    // Cập nhật trạng thái phân quyền
    public function updateStatus(Request $request, $id)
    {
        try {
            // Tìm RoleDetail theo ID
            $role = RoleDetail::findOrFail($id);

            // Cập nhật trạng thái của cột cụ thể
            $role->{$request->column_name} = $request->checked ? 1 : 0;
            $role->save();

            // Thêm thông báo thành công với Flasher
            // $this->flasher->addSuccess('Cập nhật trạng thái phân quyền thành công.');

            // Trả về phản hồi JSON thành công
            return response()->json([
                'data' => $request->all(),
                'status' => true,
                'success' => true,
                'message' => 'Cập nhật trạng thái phân quyền thành công'
            ], 200);
        } catch (\Exception $e) {
            // Thêm thông báo lỗi với Flasher
            // $this->flasher->addError('Có lỗi xảy ra khi cập nhật trạng thái phân quyền.');

            // Trả về phản hồi JSON lỗi
            return response()->json([
                'status' => false,
                'error' => $e->getMessage(),
                'message' => 'Có lỗi xảy ra khi cập nhật trạng thái phân quyền'
            ], 500);
        }
    }
}
