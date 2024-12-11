<?php

namespace App\Http\Controllers\Backend;

use App\Models\Staff;
use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Flasher\Prime\FlasherInterface;
use Illuminate\Support\Facades\Mail;
use App\Mail\StaffCreated;

class StaffController extends Controller
{
    // Hiển thị danh sách nhân viên
    public function index(Request $request)
    {
        $search = $request->query('search');
        $status = $request->query('status');
        $role = $request->query('role');

        
        $query = Staff::with(['user'])
        ->whereHas('user', function ($q)  {
            $q->whereNull('deleted_at');
            $q->orWhereHas('roles', function ($role) {
                $role->where('id', '>', 1);

                });
            });
        

        if ($role) {
            $query->whereHas('user', function ($q) use ($role) {
                $q->where('role', $role);
            });
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('position', 'LIKE', "%{$search}%")
                    ->orWhereHas('user', function ($q) use ($search) {
                        $q->where('name', 'LIKE', "%{$search}%")
                            ->orWhere('email', 'LIKE', "%{$search}%")
                            ->orWhere('phone_number', 'LIKE', "%{$search}%");
                    });
            });
        }

        if ($status) {
            $query->where('status', $status);
        }

        $staffs = $query->paginate(10)->appends($request->except('page'));

        return view('admin.staff.index', compact('staffs'));
    }

    // Hiển thị thông tin chi tiết của một nhân viên cụ thể
    public function show($id)
    {
        $staffDetails = Staff::with('user')->findOrFail($id);
        return view('admin.staff.show', compact('staffDetails'));
    }

    // Hiển thị form để thêm nhân viên mới
    public function create()
    {
        $users = User::all();
        return view('admin.staff.create', compact('users'));
    }

    // Lưu thông tin nhân viên mới vào cơ sở dữ liệu
    public function store(Request $request, FlasherInterface $flasher)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id|unique:staffs,user_id',
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

        $staff = Staff::create($request->all());

        // Kiểm tra xem user đã xóa mềm chưa và gửi email thông báo
        $user = User::withTrashed()->find($request->user_id);
        if ($user) {
            Mail::to($user->email)->send(new StaffCreated($staff));
        }

        $flasher->addSuccess('Nhân viên đã được thêm thành công và email đã được gửi!');
        return redirect()->route('staff.index');
    }

    // Hiển thị form để chỉnh sửa thông tin nhân viên
    public function edit($id)
    {
        $staff = Staff::with('user')->findOrFail($id);
        $users = User::all();
        return view('admin.staff.edit', compact('staff', 'users'));
    }

    // Cập nhật thông tin của nhân viên
    public function update(Request $request, $id, FlasherInterface $flasher)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
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
        $staffs = Staff::all();
        return view('admin.staff.report', compact('staffs'));
    }
}
