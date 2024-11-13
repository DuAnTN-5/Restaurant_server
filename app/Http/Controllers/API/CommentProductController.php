<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\CommentProduct;
use App\Models\Product;
use Illuminate\Http\Request;

class CommentProductController extends Controller
{
     // Lấy danh sách bình luận của sản phẩm
     public function index($slug)
     {
        $product = Product::where('slug', $slug)->firstOrFail();
        $comments = CommentProduct::where('product_id', $product->id)
             ->whereNull('parent_id') // Chỉ lấy bình luận cha
             ->with('children') // Lấy bình luận con
             ->orderBy('created_at', 'desc')
             ->get();
 
         return response()->json([
             'status' => true,
             'data' => $comments
         ]);
     }
 
     // Thêm bình luận mới
     public function store(Request $request)
     {
         $request->validate([
             'product_id' => 'required|exists:products,id',
             'content' => 'required',
         ]);
 
         $product = Product::where('slug', $request->slug)->firstOrFail();
         $comment = CommentProduct::create([
             'product_id' => $product->id,
             'user_id' => auth()->id(),
             'content' => $request->content,
             'parent_id' => $request->parent_id ?? NULL,
         ]);
 
         return response()->json([
             'status' => true,
             'message' => 'Bình luận đã được thêm thành công',
             'data' => $comment
         ]);
     }
}
