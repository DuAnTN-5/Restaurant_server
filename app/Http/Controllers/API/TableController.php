<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Api\Cart;
use App\Models\Table;
use Illuminate\Http\Request;

class TableController extends Controller
{
    // public function index()
    // {
    //     $tables = Table::select('id', 'number', 'status')->get();

    //     return response()->json([
    //         'success' => true,
    //         'data' => $tables
    //     ]);
    // }

    public function index()
    {
        // Xác định ngày bắt đầu và ngày kết thúc (14 ngày tới)
        $startDate = now(); // Ngày hiện tại
        $endDate = now()->addDays(14); // Ngày sau 14 ngày

        // Lấy tất cả các ngày từ hôm nay đến 14 ngày tới
        $dates = [];
        for ($date = $startDate; $date <= $endDate; $date->addDay()) {
            $dates[] = $date->toDateString();
        }

        // Lấy danh sách tất cả các bàn
        $tables = Table::all(['id', 'number', 'status']);

        // Khởi tạo mảng để lưu kết quả cho từng ngày
        $availableTablesByDate = [];

        // Duyệt qua từng ngày trong 14 ngày tới
        foreach ($dates as $date) {
            // Ban đầu, tất cả các bàn đều là "trống"
            $availableTablesByDate[$date] = $tables->map(function ($table) use ($date) {
                // Mặc định tất cả các bàn đều trống
                return [
                    'table_id' => $table->id,
                    'table_number' => $table->number,
                    'status' => 'available', // Ban đầu là trống
                    'date' => $date,
                ];
            });

            // Kiểm tra nếu có bàn nào đã được đặt cho ngày này
            $reservedTables = Cart::where('date', '=', $date)->pluck('table_id'); // Lấy danh sách bàn đã đặt

            // Thay đổi trạng thái bàn thành "đã đặt" nếu có bàn đã được đặt
            $availableTablesByDate[$date] = $availableTablesByDate[$date]->map(function ($table) use ($reservedTables) {
                if ($reservedTables->contains($table['table_id'])) {
                    $table['status'] = 'reserved'; // Nếu bàn đã được đặt, thay đổi trạng thái
                }
                return $table;
            });
        }

        return response()->json([
            'success' => true,
            'data' => $availableTablesByDate,
        ]);
    }
}
