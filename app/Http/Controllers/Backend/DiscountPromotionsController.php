<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\DiscountPromotion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use Flasher\Prime\FlasherInterface;

class DiscountPromotionsController extends Controller
{
    // Hiển thị danh sách chương trình khuyến mãi với tìm kiếm và phân trang
    public function index(Request $request)
    {
        $search = $request->query('search');
        $status = $request->query('status');

        $query = DiscountPromotion::query();

        // Thực hiện tìm kiếm
        if ($search) {
            $query->where('name', 'LIKE', "%{$search}%");
        }

        // Lọc theo trạng thái
        if ($status) {
            $query->where('status', $status);
        }

        // Phân trang với 10 chương trình khuyến mãi mỗi trang
        $discountPromotions = $query->paginate(10)->appends($request->except('page'));

        return view('admin.discount_promotions.index', compact('discountPromotions'));
    }

    // Hiển thị form tạo chương trình khuyến mãi
    public function create()
    {
        return view('admin.discount_promotions.create');
    }

    // Lưu chương trình khuyến mãi mới vào cơ sở dữ liệu
    public function store(Request $request, FlasherInterface $flasher)
    {
        // Xác thực dữ liệu đầu vào
        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:discount_promotions,name',
            'description' => 'nullable|string',
            'discount_value' => 'required|numeric',
            'discount_type' => 'required|in:percentage,fixed',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
        ]);

        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $error) {
                $flasher->addError($error);
            }
            return redirect()->back()->withInput();
        }

        // Lưu chương trình khuyến mãi mới
        DiscountPromotion::create([
            'name' => $request->name,
            'description' => $request->description,
            'discount_value' => $request->discount_value,
            'discount_type' => $request->discount_type,
            'start_date' => Carbon::parse($request->start_date),
            'end_date' => Carbon::parse($request->end_date),
            'status' => 'active',
        ]);

        $flasher->addSuccess('Chương trình khuyến mãi đã được tạo thành công!');
        return redirect()->route('discounts_promotions.index');
    }

    // Hiển thị form chỉnh sửa chương trình khuyến mãi
    public function edit($id)
    {
        $discountPromotion = DiscountPromotion::findOrFail($id);
        return view('admin.discount_promotions.edit', compact('discountPromotion'));
    }

    // Cập nhật chương trình khuyến mãi
    public function update(Request $request, $id, FlasherInterface $flasher)
    {
        // Tìm chương trình khuyến mãi
        $discountPromotion = DiscountPromotion::findOrFail($id);

        // Xác thực dữ liệu
        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:discount_promotions,name,' . $id,
            'description' => 'nullable|string',
            'discount_value' => 'required|numeric',
            'discount_type' => 'required|in:percentage,fixed',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
        ]);

        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $error) {
                $flasher->addError($error);
            }
            return redirect()->back()->withInput();
        }

        // Cập nhật thông tin chương trình khuyến mãi
        $discountPromotion->update([
            'name' => $request->name,
            'description' => $request->description,
            'discount_value' => $request->discount_value,
            'discount_type' => $request->discount_type,
            'start_date' => Carbon::parse($request->start_date),
            'end_date' => Carbon::parse($request->end_date),
        ]);

        $flasher->addSuccess('Chương trình khuyến mãi đã được cập nhật thành công!');
        return redirect()->route('discounts_promotions.index');
    }

    // Xóa mềm chương trình khuyến mãi
    public function destroy($id, FlasherInterface $flasher)
    {
        $discountPromotion = DiscountPromotion::findOrFail($id);
        $discountPromotion->delete();

        $flasher->addSuccess('Chương trình khuyến mãi đã được xóa thành công!');
        return redirect()->route('discounts_promotions.index');
    }

    // Khôi phục chương trình khuyến mãi đã bị xóa mềm
    public function restore($id, FlasherInterface $flasher)
    {
        $discountPromotion = DiscountPromotion::withTrashed()->findOrFail($id);
        $discountPromotion->restore();

        $flasher->addSuccess('Chương trình khuyến mãi đã được khôi phục thành công!');
        return redirect()->route('discounts_promotions.index');
    }

    // Xóa vĩnh viễn chương trình khuyến mãi
    public function forceDelete($id, FlasherInterface $flasher)
    {
        $discountPromotion = DiscountPromotion::withTrashed()->findOrFail($id);
        $discountPromotion->forceDelete();

        $flasher->addSuccess('Chương trình khuyến mãi đã được xóa vĩnh viễn!');
        return redirect()->route('discounts_promotions.index');
    }

    // Cập nhật trạng thái của chương trình khuyến mãi (active/inactive)
    public function updateStatus(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:discount_promotions,id',
            'status' => 'required|in:active,inactive',
        ]);

        $discountPromotion = DiscountPromotion::findOrFail($request->id);
        $discountPromotion->status = $request->status;
        $discountPromotion->save();

        $message = $discountPromotion->status === 'active'
            ? 'Chương trình khuyến mãi đã được kích hoạt.'
            : 'Chương trình khuyến mãi đã bị vô hiệu hóa.';

        return response()->json(['success' => true, 'message' => $message]);
    }
}
