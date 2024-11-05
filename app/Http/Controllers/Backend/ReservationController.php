<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Reservation;
use App\Models\User;
use App\Models\Table;
use Illuminate\Http\Request;
use Flasher\Prime\FlasherInterface;

class ReservationController extends Controller
{
    protected $flasher;

    public function __construct(FlasherInterface $flasher)
    {
        $this->flasher = $flasher;
    }

    // Hiển thị danh sách đặt chỗ với phân trang, tìm kiếm và bộ lọc
    public function index(Request $request)
    {
        $query = Reservation::with(['user', 'table']);
        $status = $request->input('status');
        $userId = $request->input('user_id');
        $tableId = $request->input('table_id');

        $users = User::all();
        $tables = Table::all();

        // Áp dụng bộ lọc nếu có
        if ($status) {
            $query->where('status', $status);
        }
        if ($userId) {
            $query->where('user_id', $userId);
        }
        if ($tableId) {
            $query->where('table_id', $tableId);
        }

        // Tự động cập nhật trạng thái cho các đặt chỗ đã hết hạn
        $this->autoUpdateExpiredReservations();

        $reservations = $query->paginate(10)->appends($request->except('page'));

        return view('admin.reservations.index', compact('reservations', 'status', 'userId', 'tableId', 'users', 'tables'));
    }

    // Phương thức tự động cập nhật trạng thái cho các đặt chỗ hết hạn
    protected function autoUpdateExpiredReservations()
    {
        $reservations = Reservation::whereIn('status', ['reserved', 'in_use'])->get();

        foreach ($reservations as $reservation) {
            $reservation->updateStatusIfExpired();
        }
    }

    // Hiển thị form để tạo mới một đặt chỗ
    public function create()
    {
        $users = User::all();
        $tables = Table::all();
        return view('admin.reservations.create', compact('users', 'tables'));
    }

    // Lưu đặt chỗ mới vào cơ sở dữ liệu
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'user_id' => 'required|exists:users,id',
            'staff_id' => 'nullable|exists:staff,id',
            'table_id' => 'required|exists:tables,id',
            'reservation_date' => 'required|date',
            'guest_count' => 'required|integer|min:1',
            'special_requests' => 'nullable|string',
            'status' => 'required|string|in:reserved,confirmed,canceled,pending,in_use',
        ]);

        Reservation::create($validatedData);
        $this->flasher->addSuccess('Đặt chỗ đã được tạo thành công.');

        return redirect()->route('reservations.index');
    }


    // Hiển thị form để chỉnh sửa một đặt chỗ
    public function edit($id)
    {
        $reservation = Reservation::findOrFail($id);
        $users = User::all();
        $tables = Table::all();
        return view('admin.reservations.edit', compact('reservation', 'users', 'tables'));
    }

    // Cập nhật đặt chỗ đã tồn tại trong cơ sở dữ liệu
    public function update(Request $request, $id)
    {
        $reservation = Reservation::findOrFail($id);

        $validatedData = $request->validate([
            'user_id' => 'required|exists:users,id',
            'staff_id' => 'nullable|exists:staff,id',
            'table_id' => 'required|exists:tables,id',
            'reservation_date' => 'required|date',
            'guest_count' => 'required|integer|min:1',
            'special_requests' => 'nullable|string',
            'status' => 'required|string|in:reserved,confirmed,canceled,pending,in_use',
        ]);

        $reservation->update($validatedData);
        $this->flasher->addSuccess('Đặt chỗ đã được cập nhật thành công.');

        return redirect()->route('reservations.index');
    }

    // Xóa một đặt chỗ khỏi cơ sở dữ liệu
    public function destroy($id)
    {
        $reservation = Reservation::findOrFail($id);
        $reservation->delete();
        $this->flasher->addSuccess('Đặt chỗ đã được xóa thành công.');

        return redirect()->route('reservations.index');
    }

    // Cập nhật trạng thái đặt chỗ
    public function updateStatus(Request $request)
{
    // Xác thực yêu cầu
    $request->validate([
        'id' => 'required|exists:reservations,id', // Kiểm tra ID của đặt chỗ
        'status' => 'required|in:reserved,confirmed,canceled,pending,in_use', // Kiểm tra trạng thái hợp lệ
    ]);

    try {
        // Tìm đặt chỗ theo ID
        $reservation = Reservation::findOrFail($request->id);
        $reservation->status = $request->status; // Cập nhật trạng thái
        $reservation->save(); // Lưu thay đổi

        // Tạo thông điệp phản hồi dựa trên trạng thái mới
        $message = match($reservation->status) {
            'reserved' => 'Đặt chỗ đã được xác nhận.',
            'confirmed' => 'Đặt chỗ đã được xác nhận.',
            'canceled' => 'Đặt chỗ đã bị hủy.',
            'pending' => 'Đặt chỗ đang chờ.',
            'in_use' => 'Đặt chỗ đang được sử dụng.'
        };

        // Trả về phản hồi JSON
        return response()->json(['success' => true, 'message' => $message]);
    } catch (\Exception $e) {
        // Trả về thông báo lỗi nếu có vấn đề xảy ra
        return response()->json(['success' => false, 'message' => 'Có lỗi xảy ra khi cập nhật trạng thái.']);
    }
}



}
