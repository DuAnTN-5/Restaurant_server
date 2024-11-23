<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\CommentPost;
use Illuminate\Http\Request;

class CommentPostController extends Controller
{
    public function index($postId)
    {
        // Lấy bình luận gốc (parent_id = 0)
        $comments = CommentPost::where('post_id', $postId)
            ->where('parent_id', NULL) // Lọc bình luận cha
            ->with(['children', 'user:id,name,image']) // Lấy kèm bình luận con và thông tin người dùng
            ->orderBy('created_at', 'asc') // Sắp xếp theo thời gian
            ->get();

        return response()->json([
            'status' => true,
            'data' => $comments->map(function ($comment) {
                return $this->formatComment($comment);
            }),
        ]);
    }


    // Hàm định dạng một bình luận
    private function formatComment($comment)
    {
        return [
            'id' => $comment->id,
            'user' => [
                'id' => $comment->user_id,
                'name' => $comment->user->name ?? 'Ẩn danh',
                'image' => $comment->user->image ?? null,
            ],
            'content' => $comment->content,
            'created_at' => $comment->created_at->format('d/m/Y H:i:s'),
            'children' => $comment->children->map(function ($child) {
                return $this->formatComment($child); // Đệ quy định dạng
            }),
        ];
    }


    public function store(Request $request)
    {
        $validated = $request->validate([
            'post_id' => 'required|exists:posts,id',
            'user_id' => 'required|exists:users,id',
            'content' => 'required|string',
            'parent_id' => 'nullable|integer|exists:comments_post,id',
        ]);
    
        // Nếu không có parent_id (bình luận gốc), đặt nó là null
        $validated['parent_id'] = $validated['parent_id'] ?? null;
    
        $comment = CommentPost::create($validated);
    
        return response()->json([
            'status' => true,
            'message' => 'Bình luận đã được thêm thành công.',
            'data' => $comment,
        ], 201);
    }
    
    

}
