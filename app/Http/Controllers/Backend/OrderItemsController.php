<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\OrderItem;
use Flasher\Prime\FlasherInterface;

class OrderItemsController extends Controller
{
    // Hiển thị danh sách mặt hàng trong đơn
    public function index($orderId)
    {
        $items = OrderItem::where('order_id', $orderId)->paginate(10);
        return view('admin.order_items.index', compact('items', 'orderId'));
    }

    // Hiển thị form thêm mặt hàng mới vào đơn hàng
    public function create($orderId)
    {
        return view('admin.order_items.create', compact('orderId'));
    }

    // Lưu mặt hàng vào đơn hàng
    public function store(Request $request, $orderId, FlasherInterface $flasher)
    {
        $request->validate([
            'product_id' => 'required|integer',
            'quantity' => 'required|integer|min:1',
            'price' => 'required|numeric',
        ]);

        OrderItem::create([
            'order_id' => $orderId,
            'product_id' => $request->product_id,
            'quantity' => $request->quantity,
            'price' => $request->price,
            'discount_applied' => $request->discount_applied ?? 0,
        ]);

        $flasher->addSuccess('Mặt hàng đã được thêm thành công!');
        return redirect()->route('order-items.index', $orderId);
    }

    // Hiển thị form chỉnh sửa mặt hàng trong đơn
    public function edit($id)
    {
        $item = OrderItem::findOrFail($id);
        return view('admin.order_items.edit', compact('item'));
    }

    // Cập nhật mặt hàng trong đơn
    public function update(Request $request, $id, FlasherInterface $flasher)
    {
        $item = OrderItem::findOrFail($id);

        $request->validate([
            'product_id' => 'required|integer',
            'quantity' => 'required|integer|min:1',
            'price' => 'required|numeric',
        ]);

        $item->update($request->all());

        $flasher->addSuccess('Mặt hàng đã được cập nhật!');
        return redirect()->route('order-items.index', $item->order_id);
    }

    // Xóa mặt hàng trong đơn
    public function destroy($id, FlasherInterface $flasher)
    {
        $item = OrderItem::findOrFail($id);
        $item->delete();

        $flasher->addSuccess('Mặt hàng đã được xóa!');
        return redirect()->route('order-items.index', $item->order_id);
    }
}
