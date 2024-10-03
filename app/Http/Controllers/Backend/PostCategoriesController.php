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
    // Retrieve the filter inputs
    $search = $request->query('search');
    $created_at = $request->query('created_at');
    $status = $request->query('status');

    // Start the query builder for PostCategory
    $query = PostCategory::query();

    // Apply search filter if provided
    if ($search) {
        $query->where(function($q) use ($search) {
            $q->where('name', 'LIKE', "%{$search}%")
              ->orWhere('slug', 'LIKE', "%{$search}%");
        });
    }

    // Apply date filter if provided
    if ($created_at) {
        $query->whereDate('created_at', $created_at);
    }

    // Apply status filter if provided
    if ($status) {
        $query->where('status', $status);
    }

    // Paginate the result
    $categories = $query->paginate(10);

    return view('admin.postcategories.index', compact('categories'));
}


    // Hiển thị form tạo danh mục mới
    public function create()
    {
        return view('admin.PostCategories.create');
    }

    // Lưu danh mục bài viết vào cơ sở dữ liệu
    public function store(Request $request, FlasherInterface $flasher)
{
    $request->validate([
        'title' => 'required|max:255',
        'body' => 'required',
        'category_id' => 'required|exists:post_categories,id',
        'image_url' => 'nullable|file|image|mimes:jpg,jpeg,png,gif|max:2048',
        'position' => 'nullable|integer',  // Validate vị trí nếu có
    ]);

    // Tạo slug tự động từ tiêu đề nếu không có slug nhập vào
    $slug = $request->input('slug') ? $request->input('slug') : Str::slug($request->title);

    // Đếm số lượng bài viết và tạo mã bài viết với tiền tố "MQ" nếu không có code nhập vào
    $postCount = Post::count() + 1;
    $postCode = 'MQ' . str_pad($postCount, 2, '0', STR_PAD_LEFT);

    $post = new Post([
        'title' => $request->title,
        'slug' => $slug, // Gán slug tự động
        'code' => $postCode, // Gán mã bài viết tự động
        'body' => $request->body,
        'category_id' => $request->category_id,
        'user_id' => auth()->id(),
        'position' => $request->position ?? 0, // Mặc định là 0 nếu không có
        'status' => 'draft', // Mặc định bài viết ở trạng thái nháp
    ]);

    // Xử lý hình ảnh nếu có
    if ($request->hasFile('image_url')) {
        $file = $request->file('image_url');
        $fileName = time() . '.' . $file->getClientOriginalExtension();
        $file->move(public_path('postfiles'), $fileName); // Lưu hình vào thư mục postfiles
        $post->image_url = 'postfiles/' . $fileName; // Lưu đường dẫn vào database
    }

    $post->save();

    $flasher->addSuccess('Bài viết đã được thêm thành công với mã bài viết: ' . $postCode);
    return redirect()->route('posts.index');
}


    // Hiển thị form chỉnh sửa danh mục
    public function edit($id)
    {
        $category = PostCategory::findOrFail($id);
        return view('admin.PostCategories.edit', compact('category'));
    }

    // Cập nhật danh mục bài viết
    public function update(Request $request, $id, FlasherInterface $flasher)
{
    $post = Post::findOrFail($id);

    $request->validate([
        'title' => 'required|max:255',
        'body' => 'required',
        'category_id' => 'required|exists:post_categories,id',
        'image_url' => 'nullable|file|image|mimes:jpg,jpeg,png,gif|max:2048',
        'position' => 'nullable|integer',
    ]);

    // Tạo slug tự động từ tiêu đề nếu không có slug nhập vào
    $slug = $request->input('slug') ? $request->input('slug') : Str::slug($request->title);

    // Đảm bảo mã bài viết không bị mất khi cập nhật
    $post->update([
        'title' => $request->title,
        'slug' => $slug,
        'body' => $request->body,
        'category_id' => $request->category_id,
        'position' => $request->position ?? 0, // Mặc định là 0 nếu không có
    ]);

    // Xử lý hình ảnh nếu có
    if ($request->hasFile('image_url')) {
        if ($post->image_url && file_exists(public_path($post->image_url))) {
            unlink(public_path($post->image_url)); // Xóa hình ảnh cũ nếu có
        }

        $file = $request->file('image_url');
        $fileName = time() . '.' . $file->getClientOriginalExtension();
        $file->move(public_path('postfiles'), $fileName); // Lưu hình mới vào thư mục postfiles
        $post->image_url = 'postfiles/' . $fileName; // Lưu đường dẫn mới vào database
    }

    $flasher->addSuccess('Bài viết đã được cập nhật!');
    return redirect()->route('posts.index');
}


    // Xóa danh mục bài viết
    public function destroy($id, FlasherInterface $flasher)
    {
        $category = PostCategory::findOrFail($id);
        $category->delete();

        $flasher->addSuccess('Danh mục bài viết đã được xóa!');
        return redirect()->route('PostCategories.index');
    }

    // Toggle trạng thái danh mục bài viết
    public function toggleStatus($id)
    {
        $category = PostCategory::find($id);

        if ($category) {
            \Log::info('Trạng thái hiện tại: ' . $category->status);

            // Toggle status
            $category->status = $category->status === 'active' ? 'inactive' : 'active';
            $category->save();

            \Log::info('Trạng thái sau khi thay đổi: ' . $category->status);

            return response()->json(['status' => $category->status]);
        }

        return response()->json(['error' => 'Category not found'], 404);
    }
}