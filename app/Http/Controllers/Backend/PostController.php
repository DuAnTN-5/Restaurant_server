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
        $status = $request->query('status');

        $query = Post::query();

        if ($search) {
            $query->where('title', 'LIKE', "%{$search}%")
                  ->orWhere('body', 'LIKE', "%{$search}%");
        }

        if ($categoryId) {
            $query->where('category_id', $categoryId);
        }

        if ($createdAt) {
            $query->whereDate('created_at', $createdAt);
        }

        if ($status) {
            $query->where('status', $status);
        }

        $posts = $query->paginate(10)->appends([
            'search' => $search,
            'category_id' => $categoryId,
            'created_at' => $createdAt,
            'status' => $status,
        ]);

        $categories = PostCategory::all();

        return view('admin.posts.index', compact('posts', 'categories', 'search', 'categoryId', 'createdAt', 'status'));
    }

    // Hiển thị form tạo bài viết mới
    public function create()
    {
        $categories = PostCategory::all();
        return view('admin.posts.create', compact('categories'));
    }

    // Lưu bài viết vào cơ sở dữ liệu
    public function store(Request $request, FlasherInterface $flasher)
    {
        $request->validate([
            'title' => 'required|max:255',
            'body' => 'required',
            'category_id' => 'required|exists:post_categories,id',
            'image_url' => 'nullable|file|image|mimes:jpg,jpeg,png,gif|max:2048',
            'position' => 'nullable|integer',
        ]);

        $slug = $request->input('slug') ?: Str::slug($request->title);

        $postCount = Post::count() + 1;
        $postCode = 'MQ' . str_pad($postCount, 2, '0', STR_PAD_LEFT);

        $post = new Post([
            'title' => $request->title,
            'slug' => $slug,
            'code' => $postCode,
            'body' => $request->body,
            'category_id' => $request->category_id,
            'user_id' => auth()->id(),
            'position' => $request->position ?? 0,
            'status' => 'published', // Đặt mặc định là xuất bản
        ]);

        if ($request->hasFile('image_url')) {
            $file = $request->file('image_url');
            $fileName = time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('postfiles'), $fileName);
            $post->image_url = 'postfiles/' . $fileName;
        }

        $post->save();

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

        $slug = $request->input('slug') ?: Str::slug($request->title);

        $post->update([
            'title' => $request->title,
            'slug' => $slug,
            'body' => $request->body,
            'category_id' => $request->category_id,
            'position' => $request->position ?? 0,
        ]);

        if ($request->hasFile('image_url')) {
            if ($post->image_url && file_exists(public_path($post->image_url))) {
                unlink(public_path($post->image_url));
            }

            $file = $request->file('image_url');
            $fileName = time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('postfiles'), $fileName);
            $post->image_url = 'postfiles/' . $fileName;
        }

        $post->save();

        $flasher->addSuccess('Bài viết đã được cập nhật!');
        return redirect()->route('posts.index');
    }

    // Hiển thị form chỉnh sửa bài viết
    public function edit($id)
    {
        $post = Post::findOrFail($id);
        $categories = PostCategory::all();
        return view('admin.posts.edit', compact('post', 'categories'));
    }

    // Xóa bài viết
    public function destroy($id, FlasherInterface $flasher)
{
    $post = Post::findOrFail($id);

    if ($post->image_url && file_exists(public_path($post->image_url))) {
        unlink(public_path($post->image_url));
    }

    $post->delete(); // Thực hiện xóa mềm

    $flasher->addSuccess('Bài viết đã được xóa!');
    return redirect()->route('posts.index');
}

    // Toggle trạng thái bài viết
    public function updateStatus(Request $request)
    {
    $request->validate([
        'id' => 'required|exists:posts,id',
        'status' => 'required|in:published,draft',
    ]);

    try {
        $post = Post::findOrFail($request->id);
        $post->status = $request->status;
        $post->save();

        $message = $post->status === 'published'
            ? 'Bài viết đã được xuất bản.'
            : 'Bài viết hiện đang ở trạng thái nháp.';

        return response()->json(['success' => true, 'message' => $message]);

    } catch (\Exception $e) {
        return response()->json(['success' => false, 'message' => 'Có lỗi xảy ra khi cập nhật trạng thái bài viết.']);
    }
}


}
