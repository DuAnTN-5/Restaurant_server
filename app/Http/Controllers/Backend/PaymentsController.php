<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Payment; // Đảm bảo bạn có model Payment
use Illuminate\Http\Request;

class PaymentsController extends Controller
{
    /**
     * Hiển thị danh sách các phương thức thanh toán.
     */
    public function index()
    {
        // Lấy tất cả các bản ghi thanh toán từ cơ sở dữ liệu
        $payments = Payment::all();
        return view('admin.payments.index', compact('payments'));
    }

    /**
     * Hiển thị form tạo thanh toán mới.
     */
    public function create()
    {
        return view('admin.payments.create');
    }

    /**
     * Lưu thanh toán mới vào cơ sở dữ liệu.
     */
    public function store(Request $request)
    {
        // Xác thực dữ liệu nhập vào
        $request->validate([
            'order_id' => 'required|integer',
            'table_id' => 'required|integer',
            'payment_method' => 'required|string|max:255',
            'payment_status' => 'required|string|max:50',
            'transaction_id' => 'nullable|string|max:255',
            'amount' => 'required|numeric',
            'tax_amount' => 'nullable|numeric',
            'total_amount' => 'required|numeric',
            'provider_response' => 'nullable|string',
            'error_message' => 'nullable|string',
            'payment_date' => 'required|date',
            'coupon_id' => 'nullable|integer',
        ]);

        // Tạo thanh toán mới
        Payment::create([
            'order_id' => $request->order_id,
            'table_id' => $request->table_id,
            'payment_method' => $request->payment_method,
            'payment_status' => $request->payment_status,
            'transaction_id' => $request->transaction_id,
            'amount' => $request->amount,
            'tax_amount' => $request->tax_amount,
            'total_amount' => $request->total_amount,
            'provider_response' => $request->provider_response,
            'error_message' => $request->error_message,
            'payment_date' => $request->payment_date,
            'coupon_id' => $request->coupon_id,
        ]);

        return redirect()->route('payments.index')->with('success', 'Thanh toán đã được tạo thành công.');
    }

    /**
     * Hiển thị form chỉnh sửa thanh toán.
     */
    public function edit($id)
    {
        $payment = Payment::findOrFail($id);
        return view('admin.payments.edit', compact('payment'));
    }

    /**
     * Cập nhật thanh toán.
     */
    public function update(Request $request, $id)
    {
        // Xác thực dữ liệu nhập vào
        $request->validate([
            'order_id' => 'required|integer',
            'table_id' => 'required|integer',
            'payment_method' => 'required|string|max:255',
            'payment_status' => 'required|string|max:50',
            'transaction_id' => 'nullable|string|max:255',
            'amount' => 'required|numeric',
            'tax_amount' => 'nullable|numeric',
            'total_amount' => 'required|numeric',
            'provider_response' => 'nullable|string',
            'error_message' => 'nullable|string',
            'payment_date' => 'required|date',
            'coupon_id' => 'nullable|integer',
        ]);

        $payment = Payment::findOrFail($id);

        // Cập nhật các trường khác
        $payment->order_id = $request->order_id;
        $payment->table_id = $request->table_id;
        $payment->payment_method = $request->payment_method;
        $payment->payment_status = $request->payment_status;
        $payment->transaction_id = $request->transaction_id;
        $payment->amount = $request->amount;
        $payment->tax_amount = $request->tax_amount;
        $payment->total_amount = $request->total_amount;
        $payment->provider_response = $request->provider_response;
        $payment->error_message = $request->error_message;
        $payment->payment_date = $request->payment_date;
        $payment->coupon_id = $request->coupon_id;
        $payment->save();

        return redirect()->route('payments.index')->with('success', 'Thanh toán đã được cập nhật thành công.');
    }

    /**
     * Xóa thanh toán.
     */
    public function destroy($id)
    {
        $payment = Payment::findOrFail($id);
        $payment->delete();

        return redirect()->route('payments.index')->with('success', 'Thanh toán đã được xóa.');
    }

    /**
     * Cập nhật trạng thái của thanh toán.
     */
    public function updateStatus(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:payments,id',
            'payment_status' => 'required|string|max:50',
        ]);

        $payment = Payment::findOrFail($request->id);
        $payment->payment_status = $request->payment_status;
        $payment->save();

        $message = 'Trạng thái thanh toán đã được cập nhật.';
        return response()->json(['success' => true, 'message' => $message]);
    }
}
