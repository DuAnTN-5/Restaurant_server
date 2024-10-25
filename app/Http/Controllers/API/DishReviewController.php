<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\DishReviewResource;
use App\Models\DishReview;
use Illuminate\Http\Request;

class DishReviewController extends Controller
{


    public function getDishRating($dish_id)
    {
        $summary = DishReview::where('dish_id', $dish_id)
            ->selectRaw('count(*) as total_reviews, avg(rating) as average_rating')
            ->first();

        // Trả về dữ liệu dưới dạng JSON
        return response()->json([
            'dish_id' => $dish_id,
            'total_reviews' => $summary->total_reviews ?? 0,
            'average_rating' => round($summary->average_rating, 1) ?? 0, // Làm tròn số sao trung bình tới 1 chữ số thập phân
        ]);
    }


    public function index($dish_id)
    {
        $reviews = DishReview::where('dish_id', $dish_id)->with('user')->get();

        // Sử dụng Resource Collection để trả về danh sách đánh giá
        return DishReviewResource::collection($reviews);
    }


    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'user_id' => 'required|exists:users,id',
            'dish_id' => 'required|exists:products,id',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string',
        ]);

        $review = DishReview::create($validatedData);

        return new DishReviewResource($review);
    }


    public function update(Request $request, $id)
    {
        $review = DishReview::findOrFail($id);

        $validatedData = $request->validate([
            'rating' => 'sometimes|integer|min:1|max:5',
            'comment' => 'nullable|string',
        ]);

        $review->update($validatedData);

        return new DishReviewResource($review);
    }
 

    public function destroy($id)
    {
        $review = DishReview::findOrFail($id);
        $review->delete();

        return response()->json(['message' => 'Xóa đánh giá thành công']);
    }




}
