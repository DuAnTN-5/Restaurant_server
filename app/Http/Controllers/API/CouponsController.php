<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
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

    /**
     * Lấy thông tin mã giảm giá theo mã code.
     *
     * @param string $code
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCouponByCode($code)
    {
        $coupon = Coupon::where('code', $code)->first();

        if ($coupon) {
            return response()->json($coupon);
        }

        return response()->json(['message' => 'Coupon not found'], 404);
    }
}
