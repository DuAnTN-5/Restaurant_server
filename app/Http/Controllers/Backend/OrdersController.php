<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\User;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Table;
use Flasher\Prime\FlasherInterface;

class OrdersController extends Controller
{
    // Hiển thị danh sách đơn hàng
    public function index(Request $request)
    {
        $search = $request->query('search');
        $status = $request->query('status');
        $order_type = $request->query('order_type');

        $query = Order::with(['user', 'table']);

        if ($search) {
            $query->whereHas('user', function ($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%");
            });
        }

        if ($status) {
            $query->where('status', $status);
        }

        if ($order_type) {
            $query->where('order_type', $order_type);
        }

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
        $users = User::all();
        $tables = Table::all();
        return view('admin.orders.create', compact('users', 'tables'));
    }

    // Lưu đơn hàng vào cơ sở dữ liệu
    public function store(Request $request, FlasherInterface $flasher)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'table_id' => 'nullable|exists:tables,id',
            'order_type' => 'required|max:50',
            'total_price' => 'required|numeric',
            'status' => 'required|in:pending,completed,canceled',
            'order_date' => 'required|date',
        ]);

        // Xử lý discount_promotion_id, nếu không có giá trị thì đặt là NULL
        $discount_promotion_id = is_numeric($request->input('discount_promotion_id')) ? $request->input('discount_promotion_id') : null;

        try {
            $order = Order::create([
                'user_id' => $request->input('user_id'),
                'table_id' => $request->input('table_id'),
                'discount_promotion_id' => $discount_promotion_id,
                'coupon_code' => $request->input('coupon_code'),
                'order_type' => $request->input('order_type'),
                'order_date' => $request->input('order_date'),
                'total_price' => $request->input('total_price'),
                'payment_status' => $request->input('payment_status'),
                'status' => $request->input('status'),
                'delivery_address' => $request->input('delivery_address'),
                'estimated_delivery_time' => $request->input('estimated_delivery_time'),
                'note' => $request->input('note'),
            ]);

            $flasher->addSuccess('Đơn hàng đã được thêm thành công!');
            return redirect()->route('orders.index');
        } catch (\Exception $e) {
            $flasher->addError('Có lỗi xảy ra khi tạo đơn hàng: ' . $e->getMessage());
            return redirect()->back()->withInput();
        }
    }


    // Hiển thị form chỉnh sửa đơn hàng
    public function edit($id)
    {
        $order = Order::with(['user', 'table'])->findOrFail($id);
        $users = User::all();
        $tables = Table::all();
        return view('admin.orders.edit', compact('order', 'users', 'tables'));
    }

    // Cập nhật đơn hàng
    public function update(Request $request, $id, FlasherInterface $flasher)
    {
        $order = Order::findOrFail($id);

        $request->validate([
            'user_id' => 'required|exists:users,id',
            'table_id' => 'nullable|exists:tables,id',
            'order_type' => 'required|max:50',
            'total_price' => 'required|numeric',
            'status' => 'required|in:pending,completed,canceled',
            'order_date' => 'required|date',
        ]);

        try {
            $order->update($request->only([
                'user_id',
                'table_id',
                'discount_promotion_id',
                'coupon_code',
                'order_type',
                'order_date',
                'total_price',
                'payment_status',
                'status',
                'delivery_address',
                'estimated_delivery_time',
                'note'
            ]));

            $flasher->addSuccess('Đơn hàng đã được cập nhật!');
            return redirect()->route('orders.index');
        } catch (\Exception $e) {
            $flasher->addError('Có lỗi xảy ra khi cập nhật đơn hàng: ' . $e->getMessage());
            return redirect()->back()->withInput();
        }
    }
    public function updateStatus(Request $request)
    {
        // Xác thực yêu cầu
        $request->validate([
            'id' => 'required|exists:orders,id', // Kiểm tra ID của đơn hàng
            'status' => 'required|in:pending,confirmed,preparing,ready,completed,canceled', // Kiểm tra trạng thái hợp lệ
        ]);

        try {
            // Tìm đơn hàng theo ID
            $order = Order::findOrFail($request->id);
            $order->status = $request->status; // Cập nhật trạng thái
            $order->save(); // Lưu thay đổi

            // Tạo thông điệp phản hồi dựa trên trạng thái mới
            $message = match ($order->status) {
                'pending' => 'Đơn hàng đang chờ xử lý.',
                'confirmed' => 'Đơn hàng đã được xác nhận.',
                'preparing' => 'Đơn hàng đang được chuẩn bị.',
                'ready' => 'Đơn hàng đã sẵn sàng.',
                'completed' => 'Đơn hàng đã hoàn thành.',
                'canceled' => 'Đơn hàng đã bị hủy.'
            };

            // Trả về phản hồi JSON
            return response()->json(['success' => true, 'message' => $message]);
        } catch (\Exception $e) {
            // Trả về thông báo lỗi nếu có vấn đề xảy ra
            return response()->json(['success' => false, 'message' => 'Có lỗi xảy ra khi cập nhật trạng thái.']);
        }
    }
    // Xóa đơn hàng
    public function destroy($id, FlasherInterface $flasher)
    {
        $order = Order::findOrFail($id);
        $order->delete();

        $flasher->addSuccess('Đơn hàng đã được xóa!');
        return redirect()->route('orders.index');
    }
    public function showItems($orderId)
{
    $order = Order::findOrFail($orderId);
    $products = Product::all(); // Lấy danh sách sản phẩm từ bảng products

    // Trả về partial view chỉ chứa danh sách sản phẩm
    return view('admin.orders.component.order_items', compact('products'));
}


public function storeItems(Request $request, $orderId)
{
    $order = Order::findOrFail($orderId);
    $quantities = $request->input('quantities');

    foreach ($quantities as $productId => $quantity) {
        if ($quantity > 0) {
            // Cập nhật hoặc tạo mới OrderItem cho sản phẩm
            OrderItem::updateOrCreate(
                ['order_id' => $orderId, 'product_id' => $productId],
                ['quantity' => $quantity, 'price' => Product::find($productId)->price]
            );
        }
    }

    return response()->json(['success' => true, 'message' => 'Đã thêm sản phẩm vào đơn hàng thành công!']);
}


}
