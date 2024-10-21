<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Reservation;
use App\Models\Table;
use App\Models\User;
use Illuminate\Http\Request;
use Flasher\Prime\FlasherInterface;

class ReservationController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('search');
        $status = $request->query('status');
        $startDate = $request->query('start_date');

        $query = Reservation::query();

        if ($search) {
            $query->whereHas('user', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($status) {
            $query->where('status', $status);
        }

        if ($startDate) {
            $query->where('reservation_date', '>=', $startDate);
        }

        $reservations = $query->paginate(10)->appends([
            'search' => $search,
            'status' => $status,
            'start_date' => $startDate,
        ]);

        return view('admin.reservations.index', compact('reservations'));
    }


    public function store(Request $request, FlasherInterface $flasher)
    {
        // Validate the input
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'table_id' => 'required|exists:tables,id',
            'guest_count' => 'required|integer|min:1',
            'reservation_date' => 'required|date|after:today',
        ]);

        // Update the table status
        $table = Table::find($request->table_id);
        if ($table->status === 'available') {
            $table->update(['status' => 'reserved']);
        }

        // Create a new reservation
        $reservation = Reservation::create([
            'user_id' => $request->user_id,
            'table_id' => $request->table_id,
            'guest_count' => $request->guest_count,
            'reservation_date' => $request->reservation_date,
            'special_requests' => $request->special_requests,
            'status' => 'pending',
        ]);

        // Flash a success message
        $flasher->addSuccess('Đặt bàn thành công!');

        return redirect()->route('reservations.index');
    }

}
