<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Table;
use App\Models\User;
use App\Models\Reservation;
use Illuminate\Http\Request;
use Flasher\Prime\FlasherInterface;

class TableController extends Controller
{
    protected $flasher;

    public function __construct(FlasherInterface $flasher)
    {
        $this->flasher = $flasher;
    }

    // Hiển thị danh sách bàn với phân trang, tìm kiếm và lọc
    public function index(Request $request)
    {
        $query = Table::query();
        $search = $request->input('search');
        $status = $request->input('status');

        if ($search) {
            $query->where('number', 'LIKE', "%{$search}%");
        }

        if ($status) {
            $query->where('status', $status);
        }

        $tables = $query->paginate(8)->appends($request->except('page'));

        return view('admin.tables.index', compact('tables', 'search', 'status'));
    }

    // Hiển thị form tạo bàn mới
    public function create()
    {
        return view('admin.tables.create');
    }

    // Lưu bàn mới vào cơ sở dữ liệu
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'number' => 'required|unique:tables',
            'seats' => 'required|integer',
            'status' => 'required|string',
            'location' => 'nullable|string',
            'special_features' => 'nullable|string',
            'suitable_for_events' => 'nullable|string',
            'custom_availability' => 'nullable|string',
        ]);

        Table::create($validatedData);
        $this->flasher->addSuccess('Bàn đã được tạo thành công.');

        return redirect()->route('admin.tables.index');
    }

    // Hiển thị form chỉnh sửa bàn cụ thể
    public function edit($id)
    {
        $table = Table::findOrFail($id);
        return view('admin.tables.edit', compact('table'));
    }

    // Cập nhật bàn cụ thể trong cơ sở dữ liệu
    public function update(Request $request, $id)
    {
        $table = Table::findOrFail($id);

        $validatedData = $request->validate([
            'number' => 'required|unique:tables,number,' . $table->id,
            'seats' => 'required|integer',
            'status' => 'required|string',
            'location' => 'nullable|string',
            'special_features' => 'nullable|string',
            'suitable_for_events' => 'nullable|string',
            'custom_availability' => 'nullable|string',
        ]);

        $table->update($validatedData);
        $this->flasher->addSuccess('Bàn đã được cập nhật thành công.');

        return redirect()->route('tables.index');
    }

    // Xóa bàn cụ thể khỏi cơ sở dữ liệu
    public function destroy($id)
    {
        $table = Table::findOrFail($id);
        $table->delete();
        $this->flasher->addSuccess('Bàn đã được xóa thành công.');

        return redirect()->route('tables.index');
    }

    // Cập nhật trạng thái của bàn
    public function updateStatus(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:tables,id',
            'status' => 'required|string'
        ]);

        $table = Table::findOrFail($request->id);
        $table->status = $request->status;
        $table->save();

        $message = $table->status === 'active' ? 'Bàn đã được kích hoạt.' : 'Bàn đã được tắt kích hoạt.';
        return response()->json(['success' => true, 'message' => $message]);
    }
}
