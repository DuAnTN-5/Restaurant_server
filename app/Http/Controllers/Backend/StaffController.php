<?php

namespace App\Http\Controllers\Backend;

use App\Models\Staff;
use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Flasher\Prime\FlasherInterface;
use Illuminate\Support\Facades\Mail;
use App\Mail\StaffCreated; // Để gửi thông báo qua email

class StaffController extends Controller
{
    // Hiển thị danh sách nhân viên
    public function index(Request $request)
{
    $search = $request->query('search');
    $status = $request->query('status');
    $role = $request->query('role'); // Bộ lọc role: Admin, Manager, Staff

    // Khởi tạo query với eager loading của 'user'
    $query = Staff::with('user');

    // Nếu role được chọn, lọc theo role (1 = Admin, 2 = Manager, 3 = Staff)
    if ($role) {
        $query->whereHas('user', function ($q) use ($role) {
            $q->where('role', $role);
        });
    }

    // Nếu có từ khóa tìm kiếm, tìm kiếm trong tên nhân viên hoặc email/số điện thoại của user
    if ($search) {
        $query->where(function ($q) use ($search) {
            $q->where('name', 'LIKE', "%{$search}%")
                ->orWhereHas('user', function ($q) use ($search) {
                    $q->where('email', 'LIKE', "%{$search}%")
                      ->orWhere('phone_number', 'LIKE', "%{$search}%");
                });
        });
    }

    // Nếu có lọc trạng thái, lọc theo trạng thái của nhân viên
    if ($status) {
        $query->where('status', $status);
    }

    // Phân trang kết quả
    $staffs = $query->paginate(10)->appends($request->except('page'));

    // Trả về view với danh sách staff đã được eager load user
    return view('admin.staff.index', compact('staffs'));
}


    // Hiển thị thông tin chi tiết của một nhân viên cụ thể
    public function show($id)
    {
        $staffDetails = Staff::with('user')->findOrFail($id); // Lấy thông tin chi tiết nhân viên kèm theo thông tin user
        return view('admin.staff.show', compact('staffDetails')); // Truyền thông tin chi tiết tới view
    }

    // Hiển thị form để thêm nhân viên mới
    public function create()
    {
        $users = User::all(); // Lấy danh sách user để gán vào nhân viên
        return view('admin.staff.create', compact('users')); // Trả về form tạo nhân viên mới
    }

    // Lưu thông tin nhân viên mới vào cơ sở dữ liệu
    public function store(Request $request, FlasherInterface $flasher)
    {
        // Xác thực dữ liệu đầu vào
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id', // Kiểm tra user có tồn tại không
            'position' => 'required|string|max:50',
            'hire_date' => 'required|date',
            'department' => 'required|string|max:50',
            'salary' => 'required|numeric|min:0',
            'status' => 'required|in:active,inactive', // Trạng thái active/inactive
            'shift_start' => 'nullable|date_format:H:i',
            'shift_end' => 'nullable|date_format:H:i',
            'task_description' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $error) {
                $flasher->addError($error);
            }
            return redirect()->back()->withInput();
        }

        // Tạo mới nhân viên
        $staff = Staff::create($request->all());

        // Gửi email thông báo đến user liên quan
        $user = User::find($request->user_id);
        Mail::to($user->email)->send(new StaffCreated($staff));

        $flasher->addSuccess('Nhân viên đã được thêm thành công và email đã được gửi!');
        return redirect()->route('staff.index');
    }

    // Hiển thị form để chỉnh sửa thông tin nhân viên
    public function edit($id)
    {
        $staff = Staff::with('user')->findOrFail($id); // Lấy thông tin nhân viên cùng user
        $users = User::all(); // Lấy danh sách user để hiển thị trong form
        return view('admin.staff.edit', compact('staff', 'users'));
    }

    // Cập nhật thông tin của nhân viên
    public function update(Request $request, $id, FlasherInterface $flasher)
    {
        // Xác thực dữ liệu đầu vào
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id', // Kiểm tra user ID có tồn tại không
            'position' => 'required|string|max:50',
            'hire_date' => 'required|date',
            'department' => 'required|string|max:50',
            'salary' => 'required|numeric|min:0',
            'status' => 'required|in:active,inactive',
            'shift_start' => 'nullable|date_format:H:i',
            'shift_end' => 'nullable|date_format:H:i',
            'task_description' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $error) {
                $flasher->addError($error);
            }
            return redirect()->back()->withInput();
        }

        // Cập nhật thông tin nhân viên
        $staff = Staff::findOrFail($id);
        $staff->update($request->all());

        $flasher->addSuccess('Nhân viên đã được cập nhật thành công!');
        return redirect()->route('staff.index');
    }

    // Khôi phục một nhân viên đã bị xóa mềm
    public function restore($id, FlasherInterface $flasher)
    {
        $staff = Staff::withTrashed()->findOrFail($id);
        $staff->restore();

        $flasher->addSuccess('Nhân viên đã được khôi phục thành công');
        return redirect()->route('staff.index');
    }

    // Tạo báo cáo danh sách nhân viên
    public function report()
    {
        $staffs = Staff::all(); // Lấy tất cả nhân viên
        return view('admin.staff.report', compact('staffs')); // Trả về view báo cáo
    }
}
