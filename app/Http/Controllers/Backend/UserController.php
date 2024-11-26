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
    // public function __construct()
    // {

    //     // Hạn chế quyền truy cập vào toàn bộ controller
    //     $this->middleware(function ($request, $next) {
    //         if (!auth()->check() || !auth()->user()->hasRole('Admin')) {
    //             abort(403, 'Bạn không có quyền truy cập vào khu vực này.');
    //         }
    //         return $next($request);
    //     });
    // }
    // public function setupRolesAndPermissions()
    // {
    //     // Tạo quyền (Permissions)
    //     Permission::firstOrCreate(['name' => 'view user']);
    //     Permission::firstOrCreate(['name' => 'create user']);
    //     Permission::firstOrCreate(['name' => 'edit user']);
    //     Permission::firstOrCreate(['name' => 'delete user']);
    //     Permission::firstOrCreate(['name' => 'force delete user']);

    //     // Tạo vai trò (Roles)
    //     $adminRole = Role::firstOrCreate(['name' => 'Admin']);
    //     $managerRole = Role::firstOrCreate(['name' => 'Manager']);
    //     $staffRole = Role::firstOrCreate(['name' => 'Staff']);

    //     // Gán quyền cho vai trò
    //     $adminRole->givePermissionTo(Permission::all()); // Admin có tất cả quyền
    //     $managerRole->givePermissionTo(['view user', 'create user', 'edit user', 'delete user']);
    //     $staffRole->givePermissionTo(['view user']);

    //     return redirect()->route('users.index')->with('success', 'Roles và Permissions đã được thiết lập thành công!');
    // }

    /**
     * Gán vai trò cho người dùng.
     */
    // public function assignRole(Request $request, $id)
    // {
    //     $request->validate([
    //         'role' => 'required|exists:roles,name',
    //     ]);

    //     $user = User::findOrFail($id);

    //     // Gán vai trò
    //     $user->assignRole($request->role);

    //     return redirect()->route('users.index')->with('success', 'Vai trò đã được gán thành công!');
    // }

    /**
     * Xóa vai trò của người dùng.
     */
    // public function removeRole(Request $request, $id)
    // {
    //     $request->validate([
    //         'role' => 'required|exists:roles,name',
    //     ]);

    //     $user = User::findOrFail($id);

    //     // Gỡ bỏ vai trò
    //     $user->removeRole($request->role);

    //     return redirect()->route('users.index')->with('success', 'Vai trò đã được gỡ bỏ thành công!');
    // }

    /**
     * Tạo người dùng mới.
     */




    // Hiển thị danh sách người dùng với tìm kiếm và phân trang
    public function index(Request $request)
    {
        // them quyen

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
    // hàm tạo 
    public function create(Request $request, FlasherInterface $flasher)
    {
        $roles = Role::all();
        if ($request->isMethod('get')) {
            // Hiển thị form tạo người dùng
            return view('admin.users.create',  compact('roles'));
        }

        // Xử lý lưu trữ dữ liệu khi form được gửi
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|confirmed|min:8',
            'role' => 'required|exists:roles,name',
        ]);

        try {
            // Tạo người dùng mới
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);

            // Kiểm tra nếu $user được tạo thành công
            if ($user) {
                // Gán vai trò cho người dùng
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
            'role' => 'required',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'province_code' => 'nullable|string',
            'district_code' => 'nullable|string',
            'ward_code' => 'nullable|string',
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
            'province_code' => $request->province_code,
            'district_code' => $request->district_code,
            'ward_code' => $request->ward_code,
            'address' => $request->address,
            'phone_number' => $request->phone_number,
            'date_of_birth' => $request->date_of_birth,
            'sex' => $request->sex,
        ]);

        // Gán quyền cho user
        $user->assignRole($request->role);

        // Tạo đối tượng Staff nếu user có vai trò phù hợp
        if ($user->hasAnyRole(['Admin', 'Manager', 'Staff'])) {
            Staff::create([
                'user_id' => $user->id,
                'position' => $request->position,
                'hire_date' => $request->hire_date,
                'department' => $request->department,
                'salary' => $request->salary,
                'status' => $request->status ?? 'active',
                'shift_start' => $request->shift_start,
                'shift_end' => $request->shift_end,
                'task_description' => $request->task_description,
            ]);
        }

        // Thêm thông báo thành công
        $flasher->addSuccess('Người dùng đã được thêm thành công và email đã được gửi!');
        return redirect()->route('users.index');
    }


    public function update(Request $request, $id, FlasherInterface $flasher)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|unique:users,email,' . $id,
            'name' => 'required|string|max:255',
            'password' => 'nullable|confirmed|min:8',
            'role' => 'required',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'province_code' => 'nullable|string',
            'district_code' => 'nullable|string',
            'ward_code' => 'nullable|string',
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

        //$user->role = $request->role;
        $user->syncRoles($request->role);
        $user->province_code = $request->province_code;
        $user->district_code = $request->district_code;
        $user->ward_code = $request->ward_code;
        $user->address = $request->address;
        $user->phone_number = $request->phone_number;
        $user->date_of_birth = $request->date_of_birth;
        $user->sex = $request->sex;
        $user->save();

        if ($user->hasAnyRole(['Admin', 'Manager', 'Staff'])) {
            Staff::updateOrCreate(
                ['user_id' => $user->id],
                [
                    'position' => $request->position,
                    'hire_date' => $request->hire_date,
                    'department' => $request->department,
                    'salary' => $request->salary,
                    'status' => $request->status ?? 'active',
                    'shift_start' => $request->shift_start,
                    'shift_end' => $request->shift_end,
                    'task_description' => $request->task_description,
                ]
            );
        } elseif ($staff = Staff::where('user_id', $user->id)->first()) {
            $staff->delete();
        }

        $flasher->addSuccess('Người dùng đã được cập nhật thành công!');
        return redirect()->route('users.index');
    }

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
            // Kiểm tra nếu có staff liên quan
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
        return view('admin.users.show', compact('user'));
    }

    // Hiển thị danh sách người dùng đã bị xóa mềm
    public function trashed(Request $request)
    {
        $users = User::onlyTrashed()->paginate(10)->appends($request->except('page'));
        return view('admin.users.restore', compact('users'));
    }

    // Khôi phục người dùng đã bị xóa mềm
    public function restore($id, FlasherInterface $flasher)
    {
        $user = User::withTrashed()->findOrFail($id);
        $user->restore();

        $flasher->addSuccess('Người dùng đã được khôi phục thành công');
        return redirect()->route('users.index');
    }

    // Xóa người dùng (soft delete)
    public function destroy($id)
{
    $user = User::findOrFail($id);

    // Kiểm tra xem người dùng có phải là admin không
    if ($user->hasRole('Admin')) {
        // Nếu là admin, không cho phép xóa
        return redirect()->route('users.index')->with('error', 'Không thể xóa tài khoản Admin');
    }

    // Nếu không phải admin, cho phép xóa
    $user->delete();

    return redirect()->route('users.index')->with('success', 'Người dùng đã bị xóa thành công');
}

    // Xóa vĩnh viễn người dùng
    public function forceDelete($id, FlasherInterface $flasher)
    {
        $user = User::withTrashed()->findOrFail($id);
        $user->forceDelete();

        $flasher->addSuccess('Người dùng đã được xóa vĩnh viễn');
        return redirect()->route('users.index');
    }
}
