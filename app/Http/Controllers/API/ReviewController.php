<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Review;


class ReviewController extends Controller
{
    public function rate(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'product_id' => 'required|exists:products,id',
            'rating' => 'required|integer|min:1|max:5',
        ]);
    
        $review = Review::where('user_id', $validated['user_id'])
                        ->where('product_id', $validated['product_id'])
                        ->first();
    
        if ($review && $review->rating_count >= 2) {
            return response()->json([
                'status' => false,
                'message' => 'Bạn chỉ được đánh giá tối đa 2 lần cho một sản phẩm.',
                'rating_count' => $review->rating_count,
            ], 400);
        }
    
        $review = $review ? $review : new Review();
        $review->user_id = $validated['user_id'];
        $review->product_id = $validated['product_id'];
        $review->rating = $validated['rating'];
        $review->rating_count = ($review->rating_count ?? 0) + 1; 
        $review->save();
    
        return response()->json([
            'status' => true,
            'message' => 'Đánh giá gửi thành công.',
            'data' => $review,
        ], 201);
    }
    
    public function comment(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'product_id' => 'required|exists:products,id',
            'comment' => 'required|string|max:1000',
        ]);

        // Tạo một bình luận mới
        $review = Review::create([
            'user_id' => $validated['user_id'],
            'product_id' => $validated['product_id'],
            'comment' => $validated['comment'],
        ]);

        return response()->json([
            'message' => 'Bình luận được gửi thành công.',
            'data' => $review,
        ], 201);
    }

    public function getComments($product_id)
    {
        $comments = Review::where('product_id', $product_id)
            ->whereNotNull('comment')
            ->with('user:id,name,image') // Chỉ lấy các trường cần thiết từ user
            ->orderByDesc('created_at')
            ->get()
            ->map(function ($comment) {
                return [
                    'id' => $comment->id,
                    'user_id' => $comment->user_id,
                    'name' => $comment->user->name ?? 'Ẩn danh',
                    'image' => $comment->user->image ?? null,
                    'comment' => $comment->comment,
                    // 'created_at' => $comment->created_at->format('Y-m-d H:i:s'),
                    'date' => $comment->formatted_date, 
                    'time' => $comment->formatted_time,
                ];
            });
    
        return response()->json([
            'status' => true,
            'data' => $comments,
        ]);
    }
    



}
