<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\PostCategory;
use Flasher\Prime\FlasherInterface;
use Illuminate\Support\Str;

class PostController extends Controller
{
    // Hiển thị danh sách bài viết với tìm kiếm và lọc theo danh mục
    public function index(Request $request)
{
    $search = $request->query('search');
    $categoryId = $request->query('category_id');
    $createdAt = $request->query('created_at');
    $status = $request->query('status'); // Lấy giá trị lọc theo tình trạng

    // Query cơ bản
    $query = Post::query();

    // Nếu có từ khóa tìm kiếm
    if ($search) {
        $query->where('title', 'LIKE', "%{$search}%")
            ->orWhere('body', 'LIKE', "%{$search}%");
    }

    // Nếu có lọc theo danh mục
    if ($categoryId) {
        $query->where('category_id', $categoryId);
    }

    // Nếu có lọc theo ngày tạo
    if ($createdAt) {
        $query->whereDate('created_at', $createdAt);
    }

    // Nếu có lọc theo tình trạng
    if ($status) {
        $query->where('status', $status);
    }

    // Phân trang và giữ tham số tìm kiếm/lọc
    $posts = $query->paginate(10)->appends([
        'search' => $search,
        'category_id' => $categoryId,
        'created_at' => $createdAt,
        'status' => $status,  // Giữ lại giá trị status trong phân trang
    ]);

    // Lấy danh mục để hiển thị trong form lọc
    $categories = PostCategory::all();

    return view('admin.posts.index', compact('posts', 'categories', 'search', 'categoryId', 'createdAt', 'status'));
}


    // Hiển thị form tạo bài viết mới
    public function create()
    {
        $categories = PostCategory::all();
        return view('admin.Posts.create', compact('categories'));
    }

    // Lưu bài viết vào cơ sở dữ liệu
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

    // Đếm số lượng bài viết và tạo mã bài viết với tiền tố "MQ"
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

    // Kiểm tra xem đã lưu slug và code hay chưa
    \Log::info("Post Slug: " . $post->slug); // Ghi log để kiểm tra giá trị
    \Log::info("Post Code: " . $post->code); // Ghi log để kiểm tra giá trị

    $post->save(); // Lưu bài viết vào cơ sở dữ liệu

    $flasher->addSuccess('Bài viết đã được thêm thành công với mã bài viết: ' . $postCode);
    return redirect()->route('posts.index');
}


    // Cập nhật bài viết
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

        // Tạo slug tự động từ tiêu đề nếu không có slug được nhập
        $slug = $request->input('slug') ?? Str::slug($request->title);

        $post->update([
            'title' => $request->title,
            'slug' => $slug, // Cập nhật slug
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

    // Hiển thị form chỉnh sửa bài viết
    public function edit($id)
    {
        $post = Post::findOrFail($id);
        $categories = PostCategory::all();
        return view('admin.Posts.edit', compact('post', 'categories'));
    }

    // Xóa bài viết
    public function destroy($id, FlasherInterface $flasher)
    {
        $post = Post::findOrFail($id);

        // Check if the user is the post owner or has the admin role (role == 1)
        if (auth()->id() !== $post->user_id && auth()->user()->role !== 1) {
            abort(404, 'Bạn không có quyền xóa bài viết này.');
        }

        // Xóa ảnh nếu có
        if ($post->image_url && file_exists(public_path($post->image_url))) {
            unlink(public_path($post->image_url));
        }

        $post->delete();

        $flasher->addSuccess('Bài viết đã được xóa!');
        return redirect()->route('posts.index');
    }

    // Toggle trạng thái bài viết
    public function toggleStatus($id)
{
    $post = Post::findOrFail($id);

    // Chuyển đổi qua ba trạng thái: 'published', 'draft', và 'archived'
    if ($post->status === 'published') {
        $post->status = 'archived'; // Chuyển từ 'published' sang 'archived'
    } elseif ($post->status === 'archived') {
        $post->status = 'draft'; // Chuyển từ 'archived' sang 'draft'
    } elseif ($post->status === 'draft') {
        $post->status = 'published'; // Chuyển từ 'draft' sang 'published'
    }

    $post->save();

    return response()->json(['status' => $post->status]);
}

}
