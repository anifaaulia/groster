<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Room;
use App\Models\Institution;
use App\Models\Facility;

class RoomController extends Controller
{
    public function index()
    {
        $rooms = Room::with(['institution', 'facilities'])->get();

        // Auto-revert stale statuses: a room left as 'occupied'/'booked' should
        // go back to 'available' once its approved booking(s) have fully ended,
        // since PIC doesn't always click "Selesaikan Sesi" on time.
        $now = now();
        foreach ($rooms as $room) {
            $hasOngoingOrUpcomingBooking = $room->room_booking()
                ->where('is_active', 1)
                ->where('end_time', '>=', $now)
                ->exists();

            if (!$hasOngoingOrUpcomingBooking && $room->status !== 'available') {
                $room->update(['status' => 'available']);
            }
        }

        return view('rooms.index', compact('rooms'));
    }

    public function create()
    {
        $institutions = Institution::all();
        $facilities = Facility::all();
        return view('rooms.create', compact('institutions', 'facilities'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'institution_id' => 'required',
            'capacity' => 'required|numeric',
            'status' => 'required',
        ]);

        $room = Room::create($request->except('facilities'));

        if ($request->has('facilities')) {
            $syncData = [];
            foreach ($request->facilities as $facility_id) {
                $qty = $request->quantities[$facility_id] ?? 1;
                $syncData[$facility_id] = ['quantity' => $qty];
            }
            $room->facilities()->attach($syncData);
        }

        return redirect()->route('rooms.index')->with('success', 'Ruangan berhasil ditambahkan');
    }

    public function show($id)
    {
        $room = Room::with('facilities')->findOrFail($id);
        $institutions = Institution::all();
        $facilities = Facility::all();
        return view('rooms.show', compact('room', 'institutions', 'facilities'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required',
            'institution_id' => 'required',
            'capacity' => 'required|integer',
            'status' => 'required',
        ]);

        $room = Room::findOrFail($id);
        $room->update($request->except(['facilities', 'quantities']));

        if ($request->has('facilities')) {
            $syncData = [];
            foreach ($request->facilities as $facility_id) {
                $qty = $request->quantities[$facility_id] ?? 1;
                $syncData[$facility_id] = ['quantity' => $qty];
            }
            $room->facilities()->sync($syncData);
        } else {
            $room->facilities()->detach();
        }

        return redirect()->route('rooms.index')->with('success', 'Ruangan berhasil diperbarui');
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:available,occupied',
        ]);

        $room = Room::findOrFail($id);
        $room->update(['status' => $request->status]);

        return redirect()->back()->with('success', 'Status ruangan berhasil diperbarui');
    }

    public function destroy($id)
    {
        $room = Room::findOrFail($id);
        $room->delete();

        return redirect()->route('rooms.index')->with('success', 'Ruangan berhasil dihapus');
    }
}
