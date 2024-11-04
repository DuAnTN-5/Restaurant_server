<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProductCategoryRequest;
use App\Http\Resources\ProductCategoryResource;
use App\Http\Resources\ProductResource;
use App\Models\ProductCategory;
use Illuminate\Http\Request;

class ProductCategoryController extends Controller
{
    // Lấy danh sách tất cả danh mục
    public function index()
    {
        $categories = ProductCategory::getAllParent();
        return response()->json([
            'status' => true,
            'data' => ProductCategoryResource::collection($categories),
        ]);
    }

    // Lấy chi tiết danh mục
    public function show($id)
    {
        $category = ProductCategory::find($id);
        if (!$category) {
            return response()->json([
                'status' => false,
                'message' => 'Danh mục không tìm thấy.',
            ], 404);
        }

        return response()->json([
            'status' => true,
            'data' => new ProductCategoryResource($category),
        ]);
    }

    public function products($id)
    {
        $category = ProductCategory::findOrFail($id);
        $products = $category->products; 

        return ProductResource::collection($products);
    }

    // Thêm mới danh mục
    // public function store(StoreProductCategoryRequest $request)
    // {
    //     $validatedData = $request->validated();

    //     $category = ProductCategory::create($validatedData);

    //     return response()->json([
    //         'status' => true,
    //         'message' => 'Danh mục đã được tạo thành công.',
    //         'data' => new ProductCategoryResource($category),
    //     ], 201);
    // }

    // Cập nhật danh mục
    // public function update(StoreProductCategoryRequest $request, $id)
    // {
    //     $category = ProductCategory::find($id);
    //     if (!$category) {
    //         return response()->json([
    //             'status' => false,
    //             'message' => 'Danh mục không tìm thấy.',
    //         ], 404);
    //     }

    //     $validatedData = $request->validated();
    //     $category->update($validatedData);

    //     return response()->json([
    //         'status' => true,
    //         'message' => 'Danh mục đã được cập nhật thành công.',
    //         'data' => new ProductCategoryResource($category),
    //     ]);
    // }

    // Xóa danh mục
    // public function destroy($id)
    // {
    //     $category = ProductCategory::find($id);
    //     if (!$category) {
    //         return response()->json([
    //             'status' => false,
    //             'message' => 'Danh mục không tìm thấy.',
    //         ], 404);
    //     }

    //     $category->delete();

    //     return response()->json([
    //         'status' => true,
    //         'message' => 'Danh mục đã được xóa thành công.',
    //     ], 204);
    // }
}
