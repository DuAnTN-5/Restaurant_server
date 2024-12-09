<?php

namespace App\Http\Controllers\Backend;

use App\Models\Location;
use App\Models\User;
use App\Models\Staff;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Flasher\Prime\FlasherInterface;
use Illuminate\Support\Facades\Mail;
use App\Mail\UserCreated;

use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class UserController extends Controller
{
    // Hiển thị danh sách người dùng với tìm kiếm và phân trang
    public function index(Request $request)
    {
        $search = $request->query('search');
        $status = $request->query('status');

        $query = User::query();

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                    ->orWhere('email', 'LIKE', "%{$search}%")
                    ->orWhere('phone_number', 'LIKE', "%{$search}%");
            });
        }

        if ($status) {
            $query->where('status', $status);
        }

        $users = $query->paginate(5)->appends($request->except('page'));
        $roles = Role::all();
        return view('admin.users.index', compact('users', 'roles'));
    }

    // Hàm tạo
    public function create(Request $request, FlasherInterface $flasher)
    {
        $roles = Role::all();
        if ($request->isMethod('get')) {
            return view('admin.users.create',  compact('roles'));
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|confirmed|min:8',
            
        ]);

        try {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);

            if ($user) {
                $user->assignRole($request->role);
                $flasher->addSuccess('Người dùng đã được tạo thành công!');
            } else {
                $flasher->addError('Không thể tạo người dùng. Vui lòng thử lại.');
            }
        } catch (\Exception $e) {
            $flasher->addError('Đã xảy ra lỗi: ' . $e->getMessage());
        }

        return redirect()->route('users.index');
    }

    // Hiển thị form sửa người dùng
    public function edit($id)
    {
        $user = User::findOrFail($id);
        $staff = Staff::where('user_id', $id)->first();
        $roles = Role::all();
        return view('admin.users.edit', compact('user', 'staff', 'roles'));
    }

    // Lưu người dùng mới vào cơ sở dữ liệu
    public function store(Request $request, FlasherInterface $flasher)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|unique:users,email',
            'name' => 'required',
            'password' => 'required|confirmed',
            
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'address' => 'nullable|string',
            'phone_number' => 'nullable|string',
            'date_of_birth' => 'nullable|date',
            'sex' => 'nullable|string|max:10',
            'position' => 'nullable|string|max:255',
            'hire_date' => 'nullable|date',
            'department' => 'nullable|string|max:255',
            'salary' => 'nullable|numeric',
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

        // Kiểm tra và lưu hình ảnh
        $imagePath = null;
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('userfiles/image'), $imageName);
            $imagePath = 'userfiles/image/' . $imageName;
        }

        // Tạo người dùng
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'status' => 'active',
            'image' => $imagePath,
            'address' => $request->address,
            'phone_number' => $request->phone_number,
            'date_of_birth' => $request->date_of_birth,
            'sex' => $request->sex,
        ]);

        // Gán quyền cho user
        $user->assignRole($request->role);

        // Tạo đối tượng Staff nếu user có vai trò Staff, Manager, hoặc Admin
        if ($user->hasAnyRole(['Admin', 'Manager', 'Staff'])) {
            Staff::create([
                'user_id' => $user->id,
                'hire_date' => $request->hire_date,
                'department' => $request->department,
                'salary' => $request->salary,
                'status' => $request->status ?? 'active',
                'shift_start' => $request->shift_start,
                'shift_end' => $request->shift_end,
            ]);
        }

        $flasher->addSuccess('Người dùng đã được thêm thành công và email đã được gửi!');
        return redirect()->route('users.index');
    }

    // Cập nhật người dùng
    public function update(Request $request, $id, FlasherInterface $flasher)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|unique:users,email,' . $id,
            'name' => 'required|string|max:255',
            'password' => 'nullable|confirmed|min:8',
            
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'address' => 'nullable|string',
            'phone_number' => 'nullable|string',
            'date_of_birth' => 'nullable|date',
            'sex' => 'nullable|string|max:10',
            'position' => 'nullable|string|max:255',
            'hire_date' => 'nullable|date',
            'department' => 'nullable|string|max:255',
            'salary' => 'nullable|numeric',
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

        $user = User::findOrFail($id);
        $user->name = $request->name;
        $user->email = $request->email;

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        if ($request->hasFile('image')) {
            if ($user->image && file_exists(public_path($user->image))) {
                unlink(public_path($user->image));
            }
            $image = $request->file('image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('userfiles/image'), $imageName);
            $user->image = 'userfiles/image/' . $imageName;
        }

        $user->syncRoles($request->role);
        $user->address = $request->address;
        $user->phone_number = $request->phone_number;
        $user->date_of_birth = $request->date_of_birth;
        $user->sex = $request->sex;
        $user->save();

        // Cập nhật Staff nếu user có vai trò Admin, Manager, hoặc Staff
        if ($user->hasAnyRole(['Admin', 'Manager', 'Staff'])) {
            Staff::updateOrCreate(
                ['user_id' => $user->id],
                [
                    'hire_date' => $request->hire_date,
                    'department' => $request->department,
                    'salary' => $request->salary,
                    'status' => $request->status ?? 'active',
                    'shift_start' => $request->shift_start,
                    'shift_end' => $request->shift_end,
                ]
            );
        } else {
            $staff = Staff::where('user_id', $user->id)->first();
            if ($staff) {
                $staff->delete();
            }
        }

        $flasher->addSuccess('Người dùng đã được cập nhật thành công!');
        return redirect()->route('users.index');
    }

    // Cập nhật trạng thái người dùng
    public function updateStatus(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:users,id',
            'status' => 'required|in:active,inactive',
        ]);

        $user = User::findOrFail($request->id);
        $user->status = $request->status;
        $user->save();

        // Cập nhật status của staff nếu user bị vô hiệu hóa
        if ($user->status === 'inactive') {
            $staff = Staff::where('user_id', $user->id)->first();
            if ($staff) {
                $staff->status = 'inactive';
                $staff->save();
            }
        }

        $message = $user->status === 'active'
            ? 'Tài khoản người dùng đã được kích hoạt.'
            : 'Tài khoản người dùng đã bị vô hiệu hóa.';

        return response()->json(['success' => true, 'message' => $message]);
    }

    // Hiển thị chi tiết người dùng
    public function show($id)
    {
        $user = User::findOrFail($id);
        $staff = Staff::where('user_id', $id)->first();
        return view('admin.users.show', compact('user', 'staff'));
    }

    // Xóa người dùng
    public function destroy($id)
    {
        $user = User::findOrFail($id);

        if ($user->hasRole('Admin')) {
            return redirect()->route('users.index')->with('error', 'Không thể xóa tài khoản Admin');
        }

        // Xóa thông tin Staff liên quan
        $staff = Staff::where('user_id', $user->id)->first();
        if ($staff) {
            $staff->delete();
        }

        $user->delete();
        return redirect()->route('users.index')->with('success', 'Người dùng đã bị xóa thành công');
    }
}
