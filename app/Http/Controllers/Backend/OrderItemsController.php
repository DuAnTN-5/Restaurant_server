<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\OrderItem;
use Flasher\Prime\FlasherInterface;

class OrderItemsController extends Controller
{
    // Hiển thị danh sách mặt hàng trong đơn hàng
    public function index($orderId)
    {
        // Lấy các mặt hàng của đơn hàng với ID là $orderId
        $items = OrderItem::where('order_id', $orderId)->paginate(10);
        return view('admin.order_items.index', compact('items', 'orderId'));
    }

    // Hiển thị form thêm mặt hàng mới vào đơn hàng
    public function create($orderId)
    {
        // Chuyển sang trang tạo mới item và truyền orderId
        return view('admin.order_items.create', compact('orderId'));
    }

    // Lưu mặt hàng vào đơn hàng
    public function store(Request $request, $orderId, FlasherInterface $flasher)
    {
        // Xác thực dữ liệu đầu vào
        $request->validate([
            'product_id' => 'required|integer|exists:products,id', // Kiểm tra sản phẩm có tồn tại
            'quantity' => 'required|integer|min:1',
            'price' => 'required|numeric',
        ]);

        // Tạo mới mặt hàng
        OrderItem::create([
            'order_id' => $orderId,
            'product_id' => $request->product_id,
            'quantity' => $request->quantity,
            'price' => $request->price,
            'discount_applied' => $request->discount_applied ?? 0,
        ]);

        // Hiển thị thông báo thành công
        $flasher->addSuccess('Mặt hàng đã được thêm thành công!');
        return redirect()->route('order_items.index', ['orderId' => $orderId]);
    }

    // Hiển thị form chỉnh sửa mặt hàng trong đơn
    public function edit($orderId, $id)
    {
        // Lấy thông tin mặt hàng theo ID
        $item = OrderItem::where('order_id', $orderId)->findOrFail($id);
        return view('admin.order_items.edit', compact('item', 'orderId'));
    }

    // Cập nhật mặt hàng trong đơn
    public function update(Request $request, $orderId, $id, FlasherInterface $flasher)
    {
        // Lấy thông tin mặt hàng theo ID
        $item = OrderItem::where('order_id', $orderId)->findOrFail($id);

        // Xác thực dữ liệu đầu vào
        $request->validate([
            'product_id' => 'required|integer|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'price' => 'required|numeric',
        ]);

        // Cập nhật mặt hàng với dữ liệu mới
        $item->update([
            'product_id' => $request->product_id,
            'quantity' => $request->quantity,
            'price' => $request->price,
            'discount_applied' => $request->discount_applied ?? 0,
        ]);

        // Hiển thị thông báo thành công
        $flasher->addSuccess('Mặt hàng đã được cập nhật!');
        return redirect()->route('order_items.index', ['orderId' => $orderId]);
    }

    // Xóa mặt hàng trong đơn
    public function destroy($orderId, $id, FlasherInterface $flasher)
    {
        // Lấy thông tin mặt hàng theo ID
        $item = OrderItem::where('order_id', $orderId)->findOrFail($id);
        $item->delete();

        // Hiển thị thông báo thành công
        $flasher->addSuccess('Mặt hàng đã được xóa!');
        return redirect()->route('order_items.index', ['orderId' => $orderId]);
    }
}
