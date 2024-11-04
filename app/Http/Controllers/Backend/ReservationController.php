<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Reservation;
use Illuminate\Http\Request;
use Flasher\Prime\FlasherInterface;
use Carbon\Carbon;

class ReservationController extends Controller
{
    protected $flasher;

    public function __construct(FlasherInterface $flasher)
    {
        $this->flasher = $flasher;
    }

    // Display a listing of reservations with pagination, search, and filter
    public function index(Request $request)
    {
        $query = Reservation::query();
        $status = $request->input('status');
        $userId = $request->input('user_id');
        $tableId = $request->input('table_id');

        // Apply filters if present
        if ($status) {
            $query->where('status', $status);
        }
        if ($userId) {
            $query->where('user_id', $userId);
        }
        if ($tableId) {
            $query->where('table_id', $tableId);
        }

        // Automatically update expired reservations
        $this->autoUpdateExpiredReservations();

        $reservations = $query->paginate(10)->appends($request->except('page'));

        return view('admin.reservations.index', compact('reservations', 'status', 'userId', 'tableId'));
    }

    // Method to automatically update expired reservations
    protected function autoUpdateExpiredReservations()
    {
        // Fetch reservations that may need status updates
        $reservations = Reservation::whereIn('status', ['reserved', 'in_use'])->get();
        
        foreach ($reservations as $reservation) {
            $reservation->updateStatusIfExpired();
        }
    }

    // Show the form for creating a new reservation
    public function create()
    {
        return view('admin.reservations.create');
    }

    // Store a newly created reservation in storage
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
        $this->flasher->addSuccess('Reservation created successfully.');

        return redirect()->route('admin.reservations.index');
    }

    // Show the form for editing the specified reservation
    public function edit($id)
    {
        $reservation = Reservation::findOrFail($id);
        return view('admin.reservations.edit', compact('reservation'));
    }

    // Update the specified reservation in storage
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
        $this->flasher->addSuccess('Reservation updated successfully.');

        return redirect()->route('admin.reservations.index');
    }

    // Mark a reservation as "in use" if the customer arrives and orders at the table
    public function markAsInUse($id)
    {
        $reservation = Reservation::findOrFail($id);
        
        if ($reservation->isReserved()) {
            $reservation->update(['status' => 'in_use']);
            $this->flasher->addSuccess('Reservation marked as in use.');
        } else {
            $this->flasher->addWarning('Reservation is not available for use.');
        }

        return redirect()->route('admin.reservations.index');
    }

    // Remove the specified reservation from storage
    public function destroy($id)
    {
        $reservation = Reservation::findOrFail($id);
        $reservation->delete();
        $this->flasher->addSuccess('Reservation deleted successfully.');

        return redirect()->route('admin.reservations.index');
    }
}
