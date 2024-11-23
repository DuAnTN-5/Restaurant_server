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

        // Tìm hoặc tạo đánh giá
        $review = Review::updateOrCreate(
            [
                'user_id' => $validated['user_id'],
                'product_id' => $validated['product_id'],
            ],
            [
                'rating' => $validated['rating'],
            ]
        );

        return response()->json([
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
