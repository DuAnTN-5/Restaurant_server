<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\PaymentMethod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PaymentMethodsController extends Controller
{
    /**
     * Hiển thị danh sách các phương thức thanh toán.
     */
    public function index()
    {
        // Lấy tất cả các phương thức thanh toán từ cơ sở dữ liệu
        $paymentMethods = PaymentMethod::all();
        return view('admin.payment_methods.index', compact('paymentMethods'));
    }

    /**
     * Hiển thị form tạo phương thức thanh toán mới.
     */
    public function create()
    {
        return view('admin.payment_methods.create');
    }

    /**
     * Lưu phương thức thanh toán mới vào cơ sở dữ liệu.
     */
    public function store(Request $request)
    {
        // Xác thực dữ liệu nhập vào
        $request->validate([
            'name' => 'required|string|max:255',
            'icon' => 'nullable|image|mimes:jpg,png,jpeg,gif,svg|max:2048',
            'status' => 'boolean',
            'partner_code' => 'nullable|string|max:255',
            'access_key' => 'nullable|string|max:255',
            'secret_key' => 'nullable|string|max:255',
            'endpoint_url' => 'nullable|url|max:255',
        ]);

        // Xử lý tải lên biểu tượng
        $iconName = null;
        if ($request->hasFile('icon')) {
            $icon = $request->file('icon');
            $iconName = time() . '_' . uniqid() . '.' . $icon->getClientOriginalExtension();
            $icon->move(public_path('icons'), $iconName); // Lưu vào thư mục public/icons
        }

        // Tạo phương thức thanh toán mới
        PaymentMethod::create([
            'name' => $request->name,
            'icon' => $iconName,
            'status' => $request->has('status') ? 1 : 0,
            'partner_code' => $request->partner_code,
            'access_key' => $request->access_key,
            'secret_key' => $request->secret_key,
            'endpoint_url' => $request->endpoint_url,
        ]);

        return redirect()->route('payment_methods.index')->with('success', 'Phương thức thanh toán đã được tạo thành công.');
    }

    /**
     * Hiển thị form chỉnh sửa phương thức thanh toán.
     */
    public function edit($id)
    {
        $paymentMethod = PaymentMethod::findOrFail($id);
        return view('admin.payment_methods.edit', compact('paymentMethod'));
    }

    /**
     * Cập nhật phương thức thanh toán.
     */
    public function update(Request $request, $id)
    {
        // Xác thực dữ liệu nhập vào
        $request->validate([
            'name' => 'required|string|max:255',
            'icon' => 'nullable|image|mimes:jpg,png,jpeg,gif,svg|max:2048',
            'status' => 'boolean',
            'partner_code' => 'nullable|string|max:255',
            'access_key' => 'nullable|string|max:255',
            'secret_key' => 'nullable|string|max:255',
            'endpoint_url' => 'nullable|url|max:255',
        ]);

        $paymentMethod = PaymentMethod::findOrFail($id);

        // Xử lý tải lên biểu tượng mới nếu có
        if ($request->hasFile('icon')) {
            // Xóa biểu tượng cũ nếu tồn tại
            if ($paymentMethod->icon && file_exists(public_path('icons/' . $paymentMethod->icon))) {
                unlink(public_path('icons/' . $paymentMethod->icon));
            }

            $icon = $request->file('icon');
            $iconName = time() . '_' . uniqid() . '.' . $icon->getClientOriginalExtension();
            $icon->move(public_path('icons'), $iconName);
            $paymentMethod->icon = $iconName;
        }

        // Cập nhật các trường khác
        $paymentMethod->name = $request->name;
        $paymentMethod->status = $request->has('status') ? 1 : 0;
        $paymentMethod->partner_code = $request->partner_code;
        $paymentMethod->access_key = $request->access_key;
        $paymentMethod->secret_key = $request->secret_key;
        $paymentMethod->endpoint_url = $request->endpoint_url;
        $paymentMethod->save();

        return redirect()->route('payment_methods.index')->with('success', 'Phương thức thanh toán đã được cập nhật thành công.');
    }

    /**
     * Xóa phương thức thanh toán.
     */
    public function destroy($id)
    {
        $paymentMethod = PaymentMethod::findOrFail($id);

        // Xóa biểu tượng nếu tồn tại
        if ($paymentMethod->icon && file_exists(public_path('icons/' . $paymentMethod->icon))) {
            unlink(public_path('icons/' . $paymentMethod->icon));
        }

        // Xóa phương thức thanh toán
        $paymentMethod->delete();

        return redirect()->route('payment_methods.index')->with('success', 'Phương thức thanh toán đã được xóa.');
    }

    /**
     * Cập nhật trạng thái của phương thức thanh toán.
     */
    public function updateStatus(Request $request)
    {
        
        $validate = Validator::make($request->all(),[
            'id' => 'required|exists:payment_methods,id',
            'status' => 'required|in:active,inactive',
        ] );
        if ($validate->fails()) {
            return response()->json(['success' => false, 'errors' => $validate->errors()], 422);
        }
        $paymentMethod = PaymentMethod::findOrFail($request->id);
        $paymentMethod->status = $request->status = 'active' ? 1: 0;
        $paymentMethod->save();

        $message = $request->status = 'active' ? 'Phương thức thanh toán đã được kích hoạt.' : 'Phương thức thanh toán đã bị vô hiệu hóa.';
        return response()->json(['success' => true, 'message' => $message]);
    }
}
