<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use App\Models\CouponUser;
use Carbon\Carbon;
use Illuminate\Http\Request;

class CouponsController extends Controller
{
    public function getCoupons(Request $request)
    {
        // Lấy tất cả các mã giảm giá còn hạn, có trạng thái active, và không vượt quá giới hạn sử dụng
        $coupons = Coupon::where('status', 'active')
            ->where(function ($query) {
                $query->whereNull('start_date')
                    ->orWhere('start_date', '<=', Carbon::now());
            })
            ->where(function ($query) {
                $query->whereNull('end_date')
                    ->orWhere('end_date', '>=', Carbon::now());
            })
            ->where('usage_limit', '>', 0)  // Mã còn hạn sử dụng
            ->get();

        return response()->json($coupons);
    }

    // Kiểm tra mã giảm giá hợp lệ
    public function checkCoupon(Request $request)
    {
        $request->validate([
            'coupon_code' => 'required|string|max:50',
            "user_id" => 'required',
        ]);

        $couponCode = $request->input('coupon_code');
        $user_id = $request->user_id;  // Lấy thông tin người dùng hiện tại

        // Tìm mã giảm giá trong cơ sở dữ liệu
        $coupon = Coupon::where('code', $couponCode)
            ->where('status', 'active')
            ->where(function ($query) {
                $query->whereNull('start_date')
                    ->orWhere('start_date', '<=', Carbon::now());
            })
            ->where(function ($query) {
                $query->whereNull('end_date')
                    ->orWhere('end_date', '>=', Carbon::now());
            })
            ->where('usage_limit', '>', 0) // Mã chưa hết giới hạn sử dụng
            ->first();

        if (!$coupon) {
            return response()->json(['message' => 'Mã giảm giá không hợp lệ hoặc đã hết hạn.'], 400);
        }

        // Kiểm tra xem người dùng đã sử dụng mã giảm giá này chưa
        $usedCoupon = CouponUser::where('user_id', $user_id)
            ->where('coupon_id', $coupon->id)
            ->first();

        if ($usedCoupon) {
            return response()->json(['message' => 'Bạn đã sử dụng mã giảm giá này rồi.'], 400);
        }

        // Nếu mã hợp lệ và người dùng chưa sử dụng, trả về thông tin mã giảm giá
        return response()->json([
            'message' => 'Mã giảm giá hợp lệ.',
            'coupon' => $coupon,
        ]);
    }

    // Áp dụng mã giảm giá
    public function useCoupon(Request $request)
    {
        $request->validate([
            'coupon_code' => 'required|string|max:50',
        ]);

        $couponCode = $request->input('coupon_code');
        $user = $request->user();  // Lấy thông tin người dùng hiện tại

        // Tìm mã giảm giá trong cơ sở dữ liệu
        $coupon = Coupon::where('code', $couponCode)
            ->where('status', 'active')
            ->where(function ($query) {
                $query->whereNull('start_date')
                    ->orWhere('start_date', '<=', Carbon::now());
            })
            ->where(function ($query) {
                $query->whereNull('end_date')
                    ->orWhere('end_date', '>=', Carbon::now());
            })
            ->where('usage_limit', '>', 0) // Mã chưa hết giới hạn sử dụng
            ->first();

        if (!$coupon) {
            return response()->json(['message' => 'Mã giảm giá không hợp lệ hoặc đã hết hạn.'], 400);
        }

        // Kiểm tra xem người dùng đã sử dụng mã giảm giá này chưa
        $usedCoupon = CouponUser::where('user_id', $user->id)
            ->where('coupon_id', $coupon->id)
            ->first();

        if ($usedCoupon) {
            return response()->json(['message' => 'Bạn đã sử dụng mã giảm giá này rồi.'], 400);
        }

        // Ghi nhận người dùng đã sử dụng mã
        CouponUser::create([
            'user_id' => $user->id,
            'coupon_id' => $coupon->id,
        ]);

        // Giảm số lần sử dụng của mã giảm giá
        $coupon->usage_limit -= 1;
        $coupon->save();

        return response()->json([
            'message' => 'Mã giảm giá đã được áp dụng.',
            'coupon' => $coupon,
        ]);
    }


    // /**
    //  * Lấy thông tin mã giảm giá theo mã code.
    //  *
    //  * @param string $code
    //  * @return \Illuminate\Http\JsonResponse
    //  */
    // public function getCouponByCode($code)
    // {
    //     $coupon = Coupon::where('code', $code)->first();

    //     if ($coupon) {
    //         return response()->json($coupon);
    //     }

    //     return response()->json(['message' => 'Coupon not found'], 404);
    // }
}
