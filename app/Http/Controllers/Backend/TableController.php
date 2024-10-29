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

    // Display a listing of tables with pagination, search, and filter
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

    // Show the form for creating a new table
    public function create()
    {
        return view('tables.create');
    }

    // Store a newly created table in storage
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
        $this->flasher->addSuccess('Table created successfully.');

        return redirect()->route('admin.tables.index');
    }

    // Show the form for editing the specified table
    public function edit($id)
    {
        $table = Table::findOrFail($id);
        return view('admin.tables.edit', compact('table'));
    }

    // Update the specified table in storage
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
        $this->flasher->addSuccess('Table updated successfully.');

        return redirect()->route('admin.tables.index');
    }

    // Remove the specified table from storage
    public function destroy($id)
    {
        $table = Table::findOrFail($id);
        $table->delete();
        $this->flasher->addSuccess('Table deleted successfully.');

        return redirect()->route('admin.tables.index');
    }

    // Reserve a table
    public function reserve(Request $request)
    {
        $validatedData = $request->validate([
            'table_id' => 'required|exists:tables,id',
            'user_id' => 'required|exists:users,id',
            'reservation_date' => 'required|date',
        ]);

        // Assuming a Reservation model exists
        $reservation = new Reservation($validatedData);
        $reservation->status = 'reserved';
        $reservation->save();

        $this->flasher->addSuccess('Table reserved successfully.');

        return redirect()->route('admin.tables.index');
    }
}
