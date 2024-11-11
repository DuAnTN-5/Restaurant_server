<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PostCategory;
use Flasher\Prime\FlasherInterface;
use Illuminate\Support\Str;

class PostCategoriesController extends Controller
{
    // Hiển thị danh sách các danh mục bài viết
    public function index(Request $request)
    {
        // Lấy các giá trị lọc
        $search = $request->query('search');
        $created_at = $request->query('created_at');
        $status = $request->query('status');

        // Query cơ bản
        $query = PostCategory::query();

        // Nếu có từ khóa tìm kiếm
        if ($search) {
            $query->where('name', 'LIKE', "%{$search}%");
        }

        // Nếu có lọc theo ngày tạo
        if ($created_at) {
            $query->whereDate('created_at', $created_at);
        }

        // Nếu có lọc theo tình trạng
        if ($status) {
            $query->where('status', $status);
        }

        // Phân trang và giữ tham số tìm kiếm/lọc
        $postCategories = $query->paginate(5)->appends([
            'search' => $search,
            'created_at' => $created_at,
            'status' => $status ?? 'active', // Thiết lập mặc định
        ]);

        // Truyền biến $postCategories vào view
        return view('admin.postCategories.index', compact('postCategories', 'search', 'created_at', 'status'));
    }

    // Hiển thị form tạo danh mục bài viết mới
    public function create()
    {
        return view('admin.postCategories.create');
    }

    // Lưu danh mục vào cơ sở dữ liệu
    public function store(Request $request, FlasherInterface $flasher)
    {
        // Validate dữ liệu đầu vào
        $request->validate([
            'name' => 'required|max:255',
            'description' => 'nullable|max:500',
            'status' => 'nullable|in:active,inactive',
        ]);

        // Tạo slug từ tên danh mục
        $slug = Str::slug($request->name);
        $originalSlug = $slug;
        $counter = 1;

        // Kiểm tra và thêm hậu tố nếu slug đã tồn tại
        while (PostCategory::where('slug', $slug)->exists()) {
            $slug = "{$originalSlug}-{$counter}";
            $counter++;
        }

        // Tạo danh mục mới
        PostCategory::create([
            'name' => $request->name,
            'slug' => $slug,
            'description' => $request->description,
            'status' => $request->status ?? 'active',
        ]);

        $flasher->addSuccess('Danh mục bài viết đã được thêm thành công!');
        return redirect()->route('postCategories.index');
    }

    // Hiển thị form chỉnh sửa danh mục bài viết
    public function edit($id)
    {
        $postCategory = PostCategory::findOrFail($id);
        return view('admin.postCategories.edit', compact('postCategory'));
    }

    // Cập nhật danh mục
    public function update(Request $request, $id, FlasherInterface $flasher)
    {
        // Lấy danh mục bài viết
        $postCategory = PostCategory::findOrFail($id);

        // Validate dữ liệu đầu vào
        $request->validate([
            'name' => 'required|max:255',
            'description' => 'nullable|max:500',
            'status' => 'required|in:active,inactive',
        ]);

        // Cập nhật thông tin danh mục
        $postCategory->update([
            'name' => $request->name,
            'description' => $request->description,
            'status' => $request->status ?? 'active',
        ]);

        $flasher->addSuccess('Danh mục bài viết đã được cập nhật!');
        return redirect()->route('postCategories.index');
    }

    // Xóa danh mục bài viết
    public function destroy($id, FlasherInterface $flasher)
    {
        $postCategory = PostCategory::findOrFail($id);

        // Kiểm tra xem có bài viết nào thuộc danh mục này không
        if ($postCategory->posts()->count() > 0) {
            $flasher->addError('Không thể xóa danh mục vì có bài viết thuộc danh mục này.');
            return redirect()->route('postCategories.index');
        }

        $postCategory->delete();
        $flasher->addSuccess('Danh mục bài viết đã được xóa!');
        return redirect()->route('postCategories.index');
    }

    // Đổi trạng thái danh mục bài viết
    public function updateStatus(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:post_categories,id', // Sửa tên bảng cho chính xác
            'status' => 'required|in:active,inactive', // Trạng thái phải là active hoặc inactive
        ]);

        try {
            $postCategory = PostCategory::findOrFail($request->id);
            $postCategory->status = $request->status;
            $postCategory->save();

            return response()->json([
                'success' => true,
                'message' => 'Danh mục bài viết đã được cập nhật.'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi cập nhật trạng thái.'.$e
            ]);
        }
    }
}
