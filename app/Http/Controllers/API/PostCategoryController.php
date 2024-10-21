<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePostCategoryRequest;
use App\Http\Resources\PostCategoryResource;
use App\Models\PostCategory;
use Illuminate\Http\Request;

class PostCategoryController extends Controller
{
    // Lấy danh sách tất cả danh mục
    public function index()
    {
        $categories = PostCategory::all();
        return response()->json([
            'status' => true,
            'data' => PostCategoryResource::collection($categories),
        ]);
    }

    // Lấy chi tiết danh mục
    public function show($id)
    {
        $category = PostCategory::find($id);
        if (!$category) {
            return response()->json([
                'status' => false,
                'message' => 'Danh mục không tìm thấy.',
            ], 404);
        }

        return response()->json([
            'status' => true,
            'data' => new PostCategoryResource($category),
        ]);
    }

    // Thêm mới danh mục
    public function store(StorePostCategoryRequest $request)
    {
        $validatedData = $request->validated();

        $category = PostCategory::create($validatedData);

        return response()->json([
            'status' => true,
            'message' => 'Danh mục đã được tạo thành công.',
            'data' => new PostCategoryResource($category),
        ], 201);
    }

    // Cập nhật danh mục
    public function update(StorePostCategoryRequest $request, $id)
    {
        $category = PostCategory::find($id);
        if (!$category) {
            return response()->json([
                'status' => false,
                'message' => 'Danh mục không tìm thấy.',
            ], 404);
        }

        $validatedData = $request->validated();
        $category->update($validatedData);

        return response()->json([
            'status' => true,
            'message' => 'Danh mục đã được cập nhật thành công.',
            'data' => new PostCategoryResource($category),
        ]);
    }

    // Xóa danh mục
    public function destroy($id)
    {
        $category = PostCategory::find($id);
        if (!$category) {
            return response()->json([
                'status' => false,
                'message' => 'Danh mục không tìm thấy.',
            ], 404);
        }

        $category->delete();

        return response()->json([
            'status' => true,
            'message' => 'Danh mục đã được xóa thành công.',
        ], 204);
    }
}
