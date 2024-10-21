<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Table;
use App\Models\User;
use Illuminate\Http\Request;
use Flasher\Prime\FlasherInterface;
use App\Models\Reservation;
use App\Http\Controllers\Banking\PayPalController;

class TableController extends Controller
{
    protected $paypal;

    // public function __construct(PayPalController $paypal)
    // {
    //     $this->paypal = $paypal;
    // }

    // Hiển thị danh sách bàn với tìm kiếm và phân trang
    public function index(Request $request)
    {
        // Lấy các thông tin tìm kiếm và lọc từ request
        $search = $request->input('search');
        $status = $request->input('status'); // Lấy giá trị lọc theo tình trạng bàn

        // Query để lấy danh sách bàn
        $query = Table::query();

        // Nếu có tìm kiếm theo số bàn
        if ($search) {
            $query->where('number', 'LIKE', "%{$search}%");
        }

        // Nếu có lọc theo tình trạng bàn
        if ($status) {
            $query->where('status', $status);
        }

        // Lấy danh sách người dùng
        $users = User::all();

        // Phân trang kết quả
        $tables = $query->paginate(10)->appends($request->except('page'));

        return view('admin.tables.index', compact('tables', 'search', 'status', 'users')); // Truyền thêm biến $users
    }

    // Lưu thông tin bàn vào cơ sở dữ liệu và thực hiện thanh toán qua PayPal
    // Lưu thông tin bàn vào cơ sở dữ liệu và thực hiện thanh toán qua PayPal
public function store(Request $request, FlasherInterface $flasher)
{
    $request->validate([
        'user_id' => 'required|exists:users,id',
        'table_id' => 'required|exists:tables,id',
        'guest_count' => 'required|integer|min:1',
        'reservation_date' => 'required|date|after:today',
    ]);

    // Cập nhật trạng thái bàn thành 'reserved'
    $table = Table::find($request->table_id);
    if ($table->status === 'available') {
        $table->update(['status' => 'reserved']);
    }

    // Tạo đặt chỗ mới
    $reservation = Reservation::create(array_merge($request->all(), ['status' => 'pending']));

    // Flash message to indicate the booking was successful
    $flasher->addSuccess('Đặt bàn thành công!');

    // Redirect to the reservations list
    return redirect()->route('reservations.index');
}


    // Hiển thị form chỉnh sửa bàn
    public function edit($id)
    {
        $table = Table::findOrFail($id);
        return view('admin.tables.edit', compact('table'));
    }

    // Cập nhật thông tin bàn
    public function update(Request $request, $id, FlasherInterface $flasher)
    {
        $table = Table::findOrFail($id);

        $request->validate([
            'number' => 'required|max:10|unique:tables,number,' . $table->id,
            'seats' => 'required|integer|min:1',
            'location' => 'required|max:255',
            'status' => 'required|in:available,reserved,occupied',
        ]);

        $table->update($request->all());

        $flasher->addSuccess('Thông tin bàn đã được cập nhật!');
        return redirect()->route('tables.index');
    }

    // Cập nhật trạng thái bàn thay vì xóa bàn
    public function updateStatus($id, $status, FlasherInterface $flasher)
    {
        $table = Table::findOrFail($id);

        if (in_array($status, ['available', 'reserved', 'occupied'])) {
            $table->update(['status' => $status]);
            $flasher->addSuccess("Trạng thái bàn đã được cập nhật thành '{$status}'!");
        } else {
            $flasher->addError('Trạng thái không hợp lệ.');
        }

        return redirect()->route('tables.index');
    }
}
