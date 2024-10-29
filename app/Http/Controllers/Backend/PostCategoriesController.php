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
        'status' => $status ?? 'active', // Thiết lập mặc định
    ]);

    // Truyền biến $categories vào view
    return view('admin.PostCategories.index', compact('categories', 'search', 'created_at', 'status'));
}


    // Hiển thị form tạo danh mục bài viết mới
    public function create()
    {
        return view('admin.PostCategories.create');
    }

    // Lưu danh mục vào cơ sở dữ liệu
    public function store(Request $request, FlasherInterface $flasher)
{
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
    return redirect()->route('post-categories.index');
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
            'status' => $request->status ?? 'active', // Thiết lập mặc định
        ]);

        $flasher->addSuccess('Danh mục bài viết đã được cập nhật!');
        return redirect()->route('PostCategories.index'); // Sửa đường dẫn về route chính xác
    }

    // Xóa danh mục bài viết
    public function destroy($id, FlasherInterface $flasher)
    {
        $category = PostCategory::findOrFail($id);

        // Kiểm tra xem có bài viết nào thuộc danh mục này không
        if ($category->posts()->count() > 0) {
            $flasher->addError('Không thể xóa danh mục vì có bài viết thuộc danh mục này.');
            return redirect()->route('post-categories.index'); // Sửa đường dẫn về route chính xác
        }

        $category->delete();
        $flasher->addSuccess('Danh mục bài viết đã được xóa!');
        return redirect()->route('post-categories.index'); // Sửa đường dẫn về route chính xác
    }

    // Đổi trạng thái danh mục bài viết
    public function updateStatus(Request $request)
{
    $request->validate([
        'id' => 'required|exists:post_categories,id',
        'status' => 'required|in:active,inactive',
    ]);

    try {
        $category = PostCategory::findOrFail($request->id);
        $category->status = $request->status;
        $category->save();

        $message = $category->status === 'active'
            ? 'Danh mục đã được kích hoạt.'
            : 'Danh mục đã bị vô hiệu hóa.';

        return response()->json(['success' => true, 'message' => $message]);

    } catch (\Exception $e) {
        return response()->json(['success' => false, 'message' => 'Có lỗi xảy ra khi cập nhật trạng thái.']);
    }
}



}
