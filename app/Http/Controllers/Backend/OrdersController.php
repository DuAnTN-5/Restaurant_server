<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use Flasher\Prime\FlasherInterface;

class OrdersController extends Controller
{
    // Hiển thị danh sách đơn hàng
    public function index(Request $request)
    {
        // Lấy các giá trị lọc
        $search = $request->query('search');
        $status = $request->query('status');
        $order_type = $request->query('order_type');

        // Query cơ bản
        $query = Order::query();

        // Nếu có từ khóa tìm kiếm
        if ($search) {
            $query->where('user_id', 'LIKE', "%{$search}%");
        }

        // Nếu có lọc theo tình trạng
        if ($status) {
            $query->where('status', $status);
        }

        // Nếu có lọc theo loại đơn hàng
        if ($order_type) {
            $query->where('order_type', $order_type);
        }

        // Phân trang và giữ tham số tìm kiếm/lọc
        $orders = $query->paginate(10)->appends([
            'search' => $search,
            'status' => $status,
            'order_type' => $order_type,
        ]);

        return view('admin.orders.index', compact('orders', 'search', 'status', 'order_type'));
    }

    // Hiển thị form tạo đơn hàng mới
    public function create()
    {
        return view('admin.orders.create');
    }

    // Lưu đơn hàng vào cơ sở dữ liệu
    public function store(Request $request, FlasherInterface $flasher)
    {
        $request->validate([
            'user_id' => 'required|integer',
            'order_type' => 'required|max:50',
            'total_price' => 'required|numeric',
            'status' => 'required|in:pending,completed,canceled',
        ]);

        // Tạo đơn hàng mới
        Order::create($request->all());

        $flasher->addSuccess('Đơn hàng đã được thêm thành công!');
        return redirect()->route('orders.index');
    }

    // Hiển thị form chỉnh sửa đơn hàng
    public function edit($id)
    {
        $order = Order::findOrFail($id);
        return view('admin.orders.edit', compact('order'));
    }

    // Cập nhật đơn hàng
    public function update(Request $request, $id, FlasherInterface $flasher)
    {
        $order = Order::findOrFail($id);

        $request->validate([
            'user_id' => 'required|integer',
            'order_type' => 'required|max:50',
            'total_price' => 'required|numeric',
            'status' => 'required|in:pending,completed,canceled',
        ]);

        // Cập nhật đơn hàng
        $order->update($request->all());

        $flasher->addSuccess('Đơn hàng đã được cập nhật!');
        return redirect()->route('orders.index');
    }

    // Xóa đơn hàng
    public function destroy($id, FlasherInterface $flasher)
    {
        $order = Order::findOrFail($id);
        $order->delete();

        $flasher->addSuccess('Đơn hàng đã được xóa!');
        return redirect()->route('orders.index');
    }

    // Đổi trạng thái đơn hàng
    public function toggleStatus($id, FlasherInterface $flasher)
    {
        $order = Order::findOrFail($id);
        $order->status = $order->status === 'pending' ? 'completed' : 'pending';
        $order->save();

        $flasher->addSuccess('Trạng thái đơn hàng đã được thay đổi!');
        return redirect()->route('orders.index');
    }
}
