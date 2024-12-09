<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Coupon;
use App\Models\User;
use App\Models\Payment;
use App\Models\OrderItem;
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
        $totalMoneyFromOrders = Payment::sum('total_amount'); // Giả sử 'total_amount' là trường chứa tổng tiền của đơn hàng

        // Tính doanh thu tháng này
        $monthlyRevenue = Payment::whereMonth('created_at', Carbon::now()->month)
            ->whereYear('created_at', Carbon::now()->year)
            ->sum('total_amount');

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
            $revenues[] = Payment::whereDate('created_at', $date)->sum('total_amount');

            // Lấy top 10 sản phẩm bán chạy theo ngày
            $topProductsDay = OrderItem::with('product') // Eager load quan hệ product
                ->select('product_id', DB::raw('SUM(quantity) as quantity'))
                ->whereDate('created_at', Carbon::today())
                ->groupBy('product_id')
                ->orderBy('quantity', 'desc')
                ->take(10)
                ->get();

            $topProductsWeek = OrderItem::with('product')
                ->select('product_id', DB::raw('SUM(quantity) as quantity'))
                ->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])
                ->groupBy('product_id')
                ->orderBy('quantity', 'desc')
                ->take(10)
                ->get();

            $topProductsMonth = OrderItem::with('product')
                ->select('product_id', DB::raw('SUM(quantity) as quantity'))
                ->whereMonth('created_at', Carbon::now()->month)
                ->groupBy('product_id')
                ->orderBy('quantity', 'desc')
                ->take(10)
                ->get();

            $topProductsYear = OrderItem::with('product')
                ->select('product_id', DB::raw('SUM(quantity) as quantity'))
                ->whereYear('created_at', Carbon::now()->year)
                ->groupBy('product_id')
                ->orderBy('quantity', 'desc')
                ->take(10)
                ->get();


            // Trả về view với tất cả các dữ liệu đã lấy
            return view('admin.dashboard.index', compact(
                'dates',
                'revenues',
                'totalUsers',
                'totalOrders',
                'totalMoneyFromOrders',
                'monthlyRevenue',
                'newUsersThisMonth',
                'topProductsDay',
                'topProductsWeek',
                'topProductsMonth',
                'topProductsYear',
                'coupons' // Thêm danh sách mã giảm giá vào compact để truyền vào view
            ));
        }
    }

    // theo ngày tháng năm 
    // thong ke theo ngay 
    function thongKeTheoNgay()
    {
        $startOfMonth = Carbon::now()->startOfMonth();
        $endOfMonth = Carbon::now()->endOfMonth();

        $thongKeTheoNgay = Payment::whereBetween('payment_date', [$startOfMonth, $endOfMonth])
            ->selectRaw('DATE(payment_date) as day, SUM(total_amount) as total_amount')
            ->groupBy('day')
            ->orderBy('day', 'asc')
            ->get();
        // dd($thongKeTheoNgay);
        $labels = $thongKeTheoNgay->pluck('day');
        $data = $thongKeTheoNgay->pluck('total_amount');
        // Trả về kết quả thống kê dưới dạng JSON
        return response()->json([
            'labels' => $labels,
            'data' => $data,

        ]);

    }

    public function thongKeTheothang()
    {
        // Lấy ngày bắt đầu và kết thúc của năm hiện tại
        $startOfYear = Carbon::now()->startOfYear();
        $endOfYear = Carbon::now()->endOfYear();

        // Truy vấn dữ liệu thanh toán trong năm hiện tại và nhóm theo tháng
        $thongKeTheoThang = Payment::whereBetween('payment_date', [$startOfYear, $endOfYear])
            ->selectRaw('MONTH(payment_date) as month, YEAR(payment_date) as year, SUM(total_amount) as total_amount')
            ->groupBy('month', 'year')
            ->orderBy('month', 'asc')
            ->get();

        // Sử dụng pluck để lấy dữ liệu labels và data
        $labels = $thongKeTheoThang->map(function ($item) {
            return Carbon::createFromFormat('Y-m', "{$item->year}-{$item->month}")->format('F Y');
        });
        $data = $thongKeTheoThang->pluck('total_amount');

        // Trả về kết quả thống kê dưới dạng JSON
        return response()->json([
            'labels' => $labels,
            'data' => $data,
        ]);
    }


}
