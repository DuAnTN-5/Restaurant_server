<?php

namespace App\Http\Controllers\Backend;

use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Flasher\Prime\FlasherInterface;
use Illuminate\Support\Facades\Mail;
use App\Mail\UserCreated; // Gửi email thông báo

class UserController extends Controller
{
    // Hiển thị danh sách người dùng với tìm kiếm và phân trang
    public function index(Request $request)
    {
        $config = $this->config();

        $search = $request->query('search');
        $status = $request->query('status');

        $query = User::query();

        // Thực hiện tìm kiếm
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                    ->orWhere('email', 'LIKE', "%{$search}%")
                    ->orWhere('phone_number', 'LIKE', "%{$search}%");
            });
        }

        // Lọc theo trạng thái
        if ($status) {
            $query->where('status', $status);
        }

        // Phân trang với 10 người dùng mỗi trang
        $users = $query->paginate(10)->appends($request->except('page'));

        return view('admin.users.index', compact('users', 'config'));
    }

    // Hiển thị form tạo người dùng mới
    public function create()
    {
        return view('admin.users.create');
    }

    // Lưu người dùng mới vào cơ sở dữ liệu
    public function store(Request $request, FlasherInterface $flasher)
{
    // Xác thực dữ liệu đầu vào
    $validator = Validator::make($request->all(), [
        'email' => 'required|email|unique:users,email',
        'name' => 'required',
        'password' => 'required|confirmed',
        'role' => 'required|in:0,1,2,3', // Kiểm tra vai trò người dùng
        'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048' // Xác thực file ảnh
    ]);

    if ($validator->fails()) {
        foreach ($validator->errors()->all() as $error) {
            $flasher->addError($error);
        }
        return redirect()->back()->withInput();
    }

    // Tạo user ID
    $lastUser = User::orderBy('created_at', 'desc')->first();
    $lastIdNumber = $lastUser ? (int)substr($lastUser->user_id, 2) : 0;
    $newIdNumber = $lastIdNumber + 1;
    $userId = 'MQ' . $newIdNumber;

    // Xử lý việc tải lên ảnh đại diện và lưu vào thư mục 'userfiles/image'
    if ($request->hasFile('image')) {
        $image = $request->file('image');
        $imageName = time() . '.' . $image->getClientOriginalExtension();
        $image->move(public_path('userfiles/image'), $imageName); // Lưu file vào 'userfiles/image'
        $imagePath = 'userfiles/image/' . $imageName; // Đường dẫn để lưu vào DB
    } else {
        $imagePath = null;
    }

    // Lưu thông tin người dùng
    $user = User::create([
        'name' => $request->name,
        'email' => $request->email,
        'password' => Hash::make($request->password),
        'status' => 'active',
        'user_id' => $userId,
        'role' => $request->role,
        'image' => $imagePath, // Lưu đường dẫn ảnh vào cột 'image'
    ]);

    // Gửi email thông báo
    Mail::to($user->email)->send(new UserCreated($user));

    $flasher->addSuccess('Người dùng đã được thêm thành công và email đã được gửi!');
    return redirect()->route('users.index');
}


    // Hiển thị form chỉnh sửa người dùng
    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view('admin.users.edit', compact('user'));
    }

    // Cập nhật thông tin người dùng
    public function update(Request $request, $id, FlasherInterface $flasher)
{
    // Xác thực dữ liệu
    $validator = Validator::make($request->all(), [
        'email' => 'required|email|unique:users,email,' . $id,
        'name' => 'required|string|max:255',
        'password' => 'nullable|confirmed|min:8',
        'role' => 'required|in:0,1,2,3',
        'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048' // Xác thực file ảnh nếu có
    ]);

    if ($validator->fails()) {
        foreach ($validator->errors()->all() as $error) {
            $flasher->addError($error);
        }
        return redirect()->back()->withInput();
    }

    // Tìm người dùng cần cập nhật
    $user = User::findOrFail($id);

    // Cập nhật thông tin
    $user->name = $request->name;
    $user->email = $request->email;

    // Cập nhật mật khẩu nếu có
    if ($request->filled('password')) {
        $user->password = Hash::make($request->password);
    }

    // Xử lý việc tải lên ảnh mới nếu có
    if ($request->hasFile('image')) {
        // Xóa ảnh cũ nếu có
        if ($user->image && file_exists(public_path($user->image))) {
            unlink(public_path($user->image));
        }

        // Tải lên ảnh mới
        $image = $request->file('image');
        $imageName = time() . '.' . $image->getClientOriginalExtension();
        $image->move(public_path('userfiles/image'), $imageName); // Lưu file vào 'userfiles/image'
        $user->image = 'userfiles/image/' . $imageName; // Cập nhật đường dẫn ảnh mới
    }

    // Cập nhật vai trò
    $user->role = $request->role;

    // Lưu thông tin người dùng
    $user->save();

    // Thông báo thành công
    $flasher->addSuccess('Người dùng đã được cập nhật thành công!');
    return redirect()->route('users.index');
}


    // Khôi phục người dùng đã bị xóa mềm
    public function restore($id, FlasherInterface $flasher)
    {
        $user = User::withTrashed()->findOrFail($id);
        $user->restore();

        $flasher->addSuccess('Người dùng đã được khôi phục thành công');
        return redirect()->route('users.index');
    }

    // Cập nhật trạng thái người dùng (active/inactive)
    public function updateStatus(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:users,id',
            'status' => 'required|in:active,inactive',
        ]);

        $user = User::findOrFail($request->id);
        $user->status = $request->status;
        $user->save();

        $message = $user->status === 'active'
            ? 'Tài khoản này đã có thể sử dụng.'
            : 'Bạn đã vô hiệu hóa tài khoản này.';

        return response()->json(['success' => true, 'message' => $message]);
    }

    // Trả về cấu hình JavaScript và CSS
    private function config()
    {
        return [
            'js' => ['backend/js/plugins/switchery/switchery.js'],
            'css' => ['backend/css/plugins/switchery/switchery.css'],
        ];
    }
}
