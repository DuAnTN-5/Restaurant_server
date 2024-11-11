<?php

namespace App\Http\Controllers\Backend;

use App\Models\Coupon;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use Flasher\Prime\FlasherInterface;

class CouponsController extends Controller
{
    // Hiển thị danh sách mã giảm giá với tìm kiếm và phân trang
    public function index(Request $request)
    {
        $search = $request->query('search');
        $status = $request->query('status');

        $query = Coupon::query();

        // Thực hiện tìm kiếm
        if ($search) {
            $query->where('code', 'LIKE', "%{$search}%");
        }

        // Lọc theo trạng thái
        if ($status) {
            $query->where('status', $status);
        }

        // Phân trang với 10 mã giảm giá mỗi trang
        $coupons = $query->paginate(10)->appends($request->except('page'));

        return view('admin.coupons.index', compact('coupons'));
    }

    // Hiển thị form tạo mã giảm giá
    public function create()
    {
        return view('admin.coupons.create');
    }

    // Lưu mã giảm giá mới vào cơ sở dữ liệu
    public function store(Request $request, FlasherInterface $flasher)
    {
        // Xác thực dữ liệu đầu vào
        $validator = Validator::make($request->all(), [
            'code' => 'required|unique:coupons,code',
            'value' => 'required|numeric|min:0',
            'discount_type' => 'required|in:percentage,fixed',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'usage_limit' => 'nullable|integer|min:1',
            'minimum_order_value' => 'nullable|numeric|min:0',
        ]);

        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $error) {
                $flasher->addError($error);
            }
            return redirect()->back()->withInput();
        }

        // Lưu mã giảm giá mới
        Coupon::create([
            'code' => $request->code,
            'value' => number_format($request->value, 2, '.', ''), // Định dạng số tiền
            'discount_type' => $request->discount_type,
            'start_date' => Carbon::parse($request->start_date),
            'end_date' => Carbon::parse($request->end_date),
            'status' => 'active',
            'usage_limit' => $request->usage_limit,
            'minimum_order_value' => number_format($request->minimum_order_value, 2, '.', ''), // Định dạng số tiền
        ]);

        $flasher->addSuccess('Mã giảm giá đã được tạo thành công!');
        return redirect()->route('coupons.index');
    }

    // Hiển thị form chỉnh sửa mã giảm giá
    public function edit($id)
    {
        $coupon = Coupon::findOrFail($id);
        return view('admin.coupons.edit', compact('coupon'));
    }

    // Cập nhật mã giảm giá
    public function update(Request $request, $id, FlasherInterface $flasher)
    {
        // Tìm mã giảm giá
        $coupon = Coupon::findOrFail($id);

        // Xác thực dữ liệu
        $validator = Validator::make($request->all(), [
            'code' => 'required|unique:coupons,code,' . $id,
            'value' => 'required|numeric|min:0',
            'discount_type' => 'required|in:percentage,fixed',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'usage_limit' => 'nullable|integer|min:1',
            'minimum_order_value' => 'nullable|numeric|min:0',
        ]);

        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $error) {
                $flasher->addError($error);
            }
            return redirect()->back()->withInput();
        }

        // Cập nhật thông tin mã giảm giá
        $coupon->update([
            'code' => $request->code,
            'value' => number_format($request->value, 2, '.', ''), // Định dạng số tiền
            'discount_type' => $request->discount_type,
            'start_date' => Carbon::parse($request->start_date),
            'end_date' => Carbon::parse($request->end_date),
            'usage_limit' => $request->usage_limit,
            'minimum_order_value' => number_format($request->minimum_order_value, 2, '.', ''), // Định dạng số tiền
        ]);

        $flasher->addSuccess('Mã giảm giá đã được cập nhật thành công!');
        return redirect()->route('coupons.index');
    }

    // Xóa mềm mã giảm giá
    public function destroy($id, FlasherInterface $flasher)
    {
        $coupon = Coupon::findOrFail($id);
        $coupon->delete();

        $flasher->addSuccess('Mã giảm giá đã được xóa thành công!');
        return redirect()->route('coupons.index');
    }

    // Khôi phục mã giảm giá đã bị xóa mềm
    public function restore($id, FlasherInterface $flasher)
    {
        $coupon = Coupon::withTrashed()->findOrFail($id);
        $coupon->restore();

        $flasher->addSuccess('Mã giảm giá đã được khôi phục thành công!');
        return redirect()->route('coupons.index');
    }

    // Xóa vĩnh viễn mã giảm giá
    public function forceDelete($id, FlasherInterface $flasher)
    {
        $coupon = Coupon::withTrashed()->findOrFail($id);
        $coupon->forceDelete();

        $flasher->addSuccess('Mã giảm giá đã được xóa vĩnh viễn!');
        return redirect()->route('coupons.index');
    }

    // Cập nhật trạng thái của mã giảm giá (active/inactive)
    public function updateStatus(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:coupons,id',
            'status' => 'required|in:active,inactive',
        ]);

        $coupon = Coupon::findOrFail($request->id);
        $coupon->status = $request->status;
        $coupon->save();

        $message = $coupon->status === 'active'
            ? 'Mã giảm giá đã được kích hoạt.'
            : 'Mã giảm giá đã bị vô hiệu hóa.';

        return response()->json(['success' => true, 'message' => $message]);
    }
}
