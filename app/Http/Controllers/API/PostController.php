<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePostRequest;
use App\Http\Resources\PostResource;
use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    // Lấy danh sách tất cả bài viết
    public function index()
    {
        $posts = Post::all();
        return response()->json([
            'status' => true,
            'data' => PostResource::collection($posts),
        ]);
    }

    // Lấy chi tiết bài viết
    public function show($id)
    {
        $post = Post::find($id);
        if (!$post) {
            return response()->json([
                'status' => false,
                'message' => 'Bài viết không tìm thấy.',
            ], 404);
        }

        return response()->json([
            'status' => true,
            'data' => new PostResource($post),
        ]);
    }

    // Thêm bài viết mới
    public function store(StorePostRequest $request)
    {
        $post = Post::create($request->validated());

        return response()->json([
            'status' => true,
            'message' => 'Bài viết đã được tạo thành công.',
            'data' => new PostResource($post),
        ], 201);
    }

    // Cập nhật bài viết
    public function update(StorePostRequest $request, $id)
    {
        $post = Post::find($id);
        if (!$post) {
            return response()->json([
                'status' => false,
                'message' => 'Bài viết không tìm thấy.',
            ], 404);
        }

        $post->update($request->validated());

        return response()->json([
            'status' => true,
            'message' => 'Bài viết đã được cập nhật thành công.',
            'data' => new PostResource($post),
        ]);
    }

    // Xóa bài viết
    public function destroy($id)
    {
        $post = Post::find($id);
        if (!$post) {
            return response()->json([
                'status' => false,
                'message' => 'Bài viết không tìm thấy.',
            ], 404);
        }

        $post->delete();

        return response()->json([
            'status' => true,
            'message' => 'Bài viết đã được xóa thành công.',
        ], 204);
    }
}
