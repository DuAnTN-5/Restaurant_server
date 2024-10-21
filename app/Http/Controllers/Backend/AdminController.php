<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Coupon;
use App\Models\User;
use App\Models\OrderItem; // Giả sử sản phẩm nằm trong bảng OrderItem
use Carbon\Carbon;
use DB;

class AdminController extends Controller
{
    public function index()
    {
        // Đếm tổng số người dùng
        $totalUsers = User::count();

        // Lấy tất cả mã giảm giá
        $coupons = Coupon::all();

        // Đếm tổng số đơn hàng trong tháng hiện tại
        $totalOrders = Order::whereMonth('created_at', Carbon::now()->month)
                            ->whereYear('created_at', Carbon::now()->year)
                            ->count();

        // Tính tổng tiền từ tất cả các đơn hàng
        $totalMoneyFromOrders = Order::sum('total_price'); // Giả sử 'total_price' là trường chứa tổng tiền của đơn hàng

        // Tính doanh thu tháng này
        $monthlyRevenue = Order::whereMonth('created_at', Carbon::now()->month)
                                ->whereYear('created_at', Carbon::now()->year)
                                ->sum('total_price');

        // Tính số lượng khách hàng mới trong tháng hiện tại
        $newUsersThisMonth = User::whereMonth('created_at', Carbon::now()->month)
                                 ->whereYear('created_at', Carbon::now()->year)
                                 ->count();

        // Lấy doanh thu theo từng ngày trong tháng hiện tại
        $startOfMonth = Carbon::now()->startOfMonth();
        $endOfMonth = Carbon::now()->endOfMonth();

        $dates = [];
        $revenues = [];

        for ($date = $startOfMonth; $date->lte($endOfMonth); $date->addDay()) {
            $dates[] = $date->format('Y-m-d');
            $revenues[] = Order::whereDate('created_at', $date)->sum('total_price');
        }

        // Lấy top 10 sản phẩm bán chạy theo ngày
        $topProductsDay = OrderItem::with('product')
                                   ->select('product_id', DB::raw('SUM(quantity) as quantity'))
                                   ->whereDate('created_at', Carbon::today())
                                   ->groupBy('product_id')
                                   ->orderBy('quantity', 'desc')
                                   ->take(10)
                                   ->get();

        // Lấy top 10 sản phẩm bán chạy theo tuần
        $topProductsWeek = OrderItem::with('product')
                                    ->select('product_id', DB::raw('SUM(quantity) as quantity'))
                                    ->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])
                                    ->groupBy('product_id')
                                    ->orderBy('quantity', 'desc')
                                    ->take(10)
                                    ->get();

        // Lấy top 10 sản phẩm bán chạy theo tháng
        $topProductsMonth = OrderItem::with('product')
                                     ->select('product_id', DB::raw('SUM(quantity) as quantity'))
                                     ->whereMonth('created_at', Carbon::now()->month)
                                     ->groupBy('product_id')
                                     ->orderBy('quantity', 'desc')
                                     ->take(10)
                                     ->get();

        // Lấy top 10 sản phẩm bán chạy theo năm
        $topProductsYear = OrderItem::with('product')
                                    ->select('product_id', DB::raw('SUM(quantity) as quantity'))
                                    ->whereYear('created_at', Carbon::now()->year)
                                    ->groupBy('product_id')
                                    ->orderBy('quantity', 'desc')
                                    ->take(10)
                                    ->get();

        // Trả về view với tất cả các dữ liệu đã lấy
        return view('admin.dashboard.index', compact(
            'dates', 'revenues',
            'totalUsers', 'totalOrders', 'totalMoneyFromOrders', 'monthlyRevenue', 'newUsersThisMonth',
            'topProductsDay', 'topProductsWeek', 'topProductsMonth', 'topProductsYear',
            'coupons' // Thêm danh sách mã giảm giá vào compact để truyền vào view
        ));
    }
}
