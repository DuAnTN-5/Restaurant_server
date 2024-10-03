<?php
namespace App\Http\Controllers\Backend;

use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Flasher\Prime\FlasherInterface;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
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

        $users = $query->paginate(10)->appends($request->except('page'));

        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        return view('admin.users.create');
    }

    public function store(Request $request, FlasherInterface $flasher)
    {
        if (Auth::user()->role !== 1) {
            $flasher->addError('Bạn không có quyền thêm người dùng');
            return redirect()->route('users.index');
        }

        $validator = Validator::make($request->all(), [
            'email' => 'required|email|unique:users,email',
            'name' => 'required',
            'password' => 'required|confirmed',
            'date_of_birth' => 'nullable|date',
            'sex' => 'nullable|in:Nam,Nữ',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048' // Kiểm tra định dạng và kích thước ảnh
        ]);

        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $error) {
                $flasher->addError($error);
            }
            return redirect()->back()->withInput();
        }

        // Lưu ảnh đại diện (avatar)
        $imagePath = null;
        if ($request->hasFile('image')) {
            $fileName = time() . '.' . $request->image->extension();
            $imagePath = 'userfiles/image/' . $fileName;
            $request->image->move(public_path('userfiles/image'), $fileName); // Lưu vào thư mục 'public/userfiles/image'
        }

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'image' => $imagePath ? $imagePath : null, // Lưu đường dẫn ảnh
            'password' => Hash::make($request->password),
            'date_of_birth' => $request->date_of_birth,
            'sex' => $request->sex,
            'phone_number' => $request->phone_number,
            'address' => $request->address,
            'status' => 'active',
        ]);

        $flasher->addSuccess('Người dùng đã được thêm thành công');
        return redirect()->route('users.index');
    }


    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, $id, FlasherInterface $flasher)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $id,
            'phone_number' => 'nullable|string|max:15',
            'date_of_birth' => 'nullable|date',
            'sex' => 'required|in:Nam,Nữ',
            'password' => 'nullable|string|min:8|confirmed',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048' // Kiểm tra định dạng và kích thước ảnh
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
        $user->phone_number = $request->phone_number;
        $user->date_of_birth = $request->date_of_birth;
        $user->sex = $request->sex;
        $user->address = $request->address;

        // Xử lý cập nhật ảnh đại diện (avatar)
        if ($request->hasFile('image')) {
            // Xóa ảnh cũ nếu tồn tại
            if ($user->image && file_exists(public_path($user->image))) {
                unlink(public_path($user->image));
            }
            // Lưu ảnh mới
            $fileName = time() . '.' . $request->image->extension();
            $imagePath = 'userfiles/image/' . $fileName;
            $request->image->move(public_path('userfiles/image'), $fileName);
            $user->image = $imagePath;
        }

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        $flasher->addSuccess('Người dùng đã được cập nhật thành công');
        return redirect()->route('users.index');
    }


    public function destroy($id, FlasherInterface $flasher)
    {
        $user = User::findOrFail($id);

        if (Auth::user()->role !== 1 && Auth::user()->id !== $user->id) {
            $flasher->addError('Bạn không có quyền xóa người dùng này');
            return redirect()->route('users.index');
        }

        // Xóa mềm (không xóa hình ảnh ngay lập tức)
        $user->delete();

        $flasher->addSuccess('Người dùng đã được xóa mềm thành công');
        return redirect()->route('users.index');
    }

    public function restore($id, FlasherInterface $flasher)
    {
        // Khôi phục người dùng đã bị xóa mềm
        $user = User::withTrashed()->findOrFail($id);
        $user->restore();

        $flasher->addSuccess('Người dùng đã được khôi phục thành công');
        return redirect()->route('users.index');
    }

    public function updateStatus(Request $request, FlasherInterface $flasher)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|exists:users,id',
            'status' => 'required|in:active,inactive',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => $validator->errors()->first()]);
        }

        $user = User::find($request->id);
        $user->status = $request->status;
        $user->save();

        $flasher->addSuccess('Trạng thái người dùng đã được cập nhật thành công!');
        return response()->json(['success' => true, 'status' => $user->status]);
    }
}
