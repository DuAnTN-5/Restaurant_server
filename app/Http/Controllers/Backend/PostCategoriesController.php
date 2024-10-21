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
        $categories = $query->paginate(10)->appends([
            'search' => $search,
            'created_at' => $created_at,
            'status' => $status,
        ]);

        return view('admin.postCategories.index', compact('categories', 'search', 'created_at', 'status'));
    }

    // Hiển thị form tạo danh mục bài viết mới
    public function create()
    {
        return view('admin.postCategories.create');
    }

    // Lưu danh mục vào cơ sở dữ liệu
    public function store(Request $request, FlasherInterface $flasher)
    {
        $request->validate([
            'name' => 'required|max:255',
            'description' => 'nullable|max:500',
            'status' => 'required|in:active,inactive', // Chỉ cho phép giá trị này
        ]);

        // Tạo slug từ tên danh mục
        $slug = Str::slug($request->name);

        // Tạo danh mục mới
        PostCategory::create([
            'name' => $request->name,
            'slug' => $slug,
            'description' => $request->description,
            'status' => $request->status,
        ]);

        $flasher->addSuccess('Danh mục bài viết đã được thêm thành công!');
        return redirect()->route('postCategories.index'); // Sửa đường dẫn về route chính xác
    }

    // Hiển thị form chỉnh sửa danh mục bài viết
    public function edit($id)
    {
        $category = PostCategory::findOrFail($id);
        return view('admin.post_categories.edit', compact('category'));
    }

    // Cập nhật danh mục
    public function update(Request $request, $id, FlasherInterface $flasher)
    {
        $category = PostCategory::findOrFail($id);

        $request->validate([
            'name' => 'required|max:255',
            'description' => 'nullable|max:500',
            'status' => 'required|in:active,inactive',
        ]);

        // Cập nhật danh mục
        $category->update([
            'name' => $request->name,
            'description' => $request->description,
            'status' => $request->status,
        ]);

        $flasher->addSuccess('Danh mục bài viết đã được cập nhật!');
        return redirect()->route('postCategories.index'); // Sửa đường dẫn về route chính xác
    }

    // Xóa danh mục bài viết
    public function destroy($id, FlasherInterface $flasher)
    {
        $category = PostCategory::findOrFail($id);

        // Kiểm tra xem có bài viết nào thuộc danh mục này không
        if ($category->posts()->count() > 0) {
            $flasher->addError('Không thể xóa danh mục vì có bài viết thuộc danh mục này.');
            return redirect()->route('postCategories.index'); // Sửa đường dẫn về route chính xác
        }

        $category->delete();
        $flasher->addSuccess('Danh mục bài viết đã được xóa!');
        return redirect()->route('postCategories.index'); // Sửa đường dẫn về route chính xác
    }

    // Đổi trạng thái danh mục bài viết
    public function toggleStatus($id, FlasherInterface $flasher)
    {
        $category = PostCategory::findOrFail($id);
        $category->status = $category->status === 'active' ? 'inactive' : 'active';
        $category->save();

        $flasher->addSuccess('Trạng thái danh mục đã được thay đổi thành công!');
        return redirect()->route('postCategories.index');
    }
}
