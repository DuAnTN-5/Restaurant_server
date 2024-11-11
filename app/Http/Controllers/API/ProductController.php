<?php

namespace App\Http\Controllers\API;

use Intervention\Image\Facades\Image;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProductRequest;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    // Lấy danh sách tất cả sản phẩm
    public function index()
    {
        $products = Product::all();
        return response()->json([
            'status' => true,
            'data' => ProductResource::collection($products),
        ]);
    }

    // Lấy chi tiết sản phẩm
    public function show($id)
    {
        $product = Product::find($id);
        if (!$product) {
            return response()->json([
                'status' => false,
                'message' => 'Sản phẩm không tìm thấy.',
            ], 404);
        }

        return response()->json([
            'status' => true,
            'data' => new ProductResource($product),
        ]);
    }


    // public function store(StoreProductRequest $request)
    // {
    //     $data = $request->validated();
    
    //     if ($request->hasFile('image_url')) {
    //         // Lấy hình ảnh duy nhất
    //         $image = $request->file('image_url');
    //         // Tạo tên hình ảnh duy nhất
    //         $imageName = time() . '_' . $image->getClientOriginalName();
    //         // Di chuyển hình ảnh vào thư mục upload
    //         $image->move(public_path('upload/product'), $imageName);
    //         // Lưu đường dẫn hình ảnh vào dữ liệu
    //         $data['image_url'] = 'upload/product/' . $imageName;
    //     }
    
    //     $product = Product::create($data);
    
    //     return response()->json([
    //         'status' => true,
    //         'message' => 'Sản phẩm đã được tạo thành công.',
    //         'data' => new ProductResource($product),
    //     ], 201);
    // }
    


    // Cập nhật sản phẩm
//     public function update(StoreProductRequest $request, $id)
//     {
//     $product = Product::find($id);
//     if (!$product) {
//         return response()->json([
//             'status' => false,
//             'message' => 'Sản phẩm không tìm thấy.',
//         ], 404);
//     }

//     // Lưu trữ dữ liệu đã xác thực
//     $data = $request->validated();

//     // Kiểm tra nếu có tệp hình ảnh mới
//     if ($request->hasFile('image_url')) {
//         // Xóa tệp hình ảnh cũ nếu tồn tại
//         if ($product->image_url) {
//             $oldImagePath = public_path($product->image_url);
//             if (file_exists($oldImagePath)) {
//                 unlink($oldImagePath); // Xóa tệp cũ
//             }
//         }

//         // Tạo tên hình ảnh duy nhất
//         $image = $request->file('image_url');
//         $imageName = time() . '_' . $image->getClientOriginalName();
//         // Di chuyển hình ảnh vào thư mục upload
//         $image->move(public_path('upload/product'), $imageName);
//         // Lưu đường dẫn hình ảnh vào dữ liệu
//         $data['image_url'] = 'upload/product/' . $imageName;
//     }

//     // Cập nhật sản phẩm với dữ liệu mới
//     $product->update($data);

//     return response()->json([
//         'status' => true,
//         'message' => 'Sản phẩm đã được cập nhật thành công.',
//         'data' => new ProductResource($product),
//     ]);
// }


    // Xóa sản phẩm
    // public function destroy($id)
    // {
    //     $product = Product::find($id);
    //     if (!$product) {
    //         return response()->json([
    //             'status' => false,
    //             'message' => 'Sản phẩm không tìm thấy.',
    //         ], 404);
    //     }

    //     $product->delete();

    //     return response()->json([
    //         'status' => true,
    //         'message' => 'Sản phẩm đã được xóa thành công.',
    //     ], 200);
    // }
}
