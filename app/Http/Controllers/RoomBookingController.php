<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RoomBooking;
use App\Models\Room;
use App\Models\User;
use App\Models\Event;
use Carbon\Carbon;
use App\Exports\RoomBookingsExport;
use Maatwebsite\Excel\Facades\Excel;

class RoomBookingController extends Controller
{
    public function index()
    {
        $roomBookings = RoomBooking::with(['room', 'user', 'event'])->latest()->get();
        return view('roombookings.index', compact('roomBookings'));
    }

    public function export()
    {
        return Excel::download(new RoomBookingsExport(), 'peminjaman-ruangan.xlsx');
    }

    /**
     * Recommend available rooms for a given date and time range.
     *
     * Expects request parameters:
     *   - date (Y-m-d)
     *   - start_time (H:i)
     *   - end_time (H:i)
     *
     * Returns JSON list of rooms that are not booked during the requested period
     * and have a status of "available".
     */
    public function recommendRooms(Request $request)
    {
        $request->validate([
            'date' => 'required|date',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'capacity' => 'required|integer|min:1',
        ]);

        // Combine date and times into full datetime strings
        $start = $request->date . ' ' . $request->start_time . ':00';
        $end   = $request->date . ' ' . $request->end_time . ':00';

        // Identify rooms that have an active overlapping booking
        $bookedRoomIds = RoomBooking::whereDate('date', $request->date)
            ->where('is_active', 1)
            ->where(function ($q) use ($start, $end) {
                $q->where('start_time', '<', $end)
                  ->where('end_time', '>', $start);
            })
            ->pluck('room_id')
            ->unique()
            ->toArray();

        // Fetch rooms that meet capacity, are not currently occupied, and have no overlapping booking
        $availableRooms = Room::where('status', '!=', 'occupied')
            ->where('capacity', '>=', $request->capacity)
            ->whereNotIn('id', $bookedRoomIds)
            ->orderBy('name')
            ->get(['id', 'name', 'capacity']);

        $suggestion = null;

        if ($availableRooms->isEmpty()) {
            $suggestion = $this->buildAvailabilitySuggestion($request, $start, $end);
        }

        return response()->json([
            'rooms' => $availableRooms,
            'suggestion' => $suggestion,
        ]);
    }

    /**
     * Build a helpful suggestion when no room matches the requested capacity/time,
     * pointing out either the largest available capacity or when a fitting room frees up.
     */
    private function buildAvailabilitySuggestion(Request $request, string $start, string $end): array
    {
        $maxCapacity = Room::where('status', '!=', 'occupied')->max('capacity');

        $candidateRooms = Room::where('status', '!=', 'occupied')
            ->where('capacity', '>=', $request->capacity)
            ->get(['id']);

        $nextAvailableTime = null;

        if ($candidateRooms->isNotEmpty()) {
            $endTimes = RoomBooking::whereIn('room_id', $candidateRooms->pluck('id'))
                ->whereDate('date', $request->date)
                ->where('is_active', 1)
                ->where(function ($q) use ($start, $end) {
                    $q->where('start_time', '<', $end)
                      ->where('end_time', '>', $start);
                })
                ->pluck('end_time');

            if ($endTimes->isNotEmpty()) {
                $nextAvailableTime = $endTimes->map(fn ($t) => Carbon::parse($t)->format('H:i'))
                    ->sort()
                    ->first();
            }
        }

        return [
            'max_capacity' => $maxCapacity,
            'fits_capacity' => $candidateRooms->isNotEmpty(),
            'next_available_time' => $nextAvailableTime,
        ];
    }


    public function create()
    {
        $rooms = Room::all();
        $users = User::role('pic')->get();
        $events = Event::all();
        return view('roombookings.create', compact('rooms', 'users', 'events'));
    }

    /**
     * Start a room session, only allowed within the booking's scheduled time window.
     */
    public function startSession(RoomBooking $roomBooking)
    {
        $now = Carbon::now();

        if ($now->lt($roomBooking->start_time) || $now->gt($roomBooking->end_time)) {
            return redirect()->back()->with(
                'error',
                'Sesi hanya dapat dimulai pada rentang waktu yang telah dijadwalkan ('
                    . $roomBooking->start_time->format('H:i') . ' - ' . $roomBooking->end_time->format('H:i') . ').'
            );
        }

        $roomBooking->room->update(['status' => 'occupied']);

        return redirect()->back()->with('success', 'Sesi berhasil dimulai.');
    }

    public function store(Request $request)
    {
        if (auth()->user()->hasRole('pic')) {
            $request->merge([
                'user_id' => auth()->id(),
                'is_active' => 0
            ]);
        }

        $request->validate([
            'room_id' => 'required',
            'user_id' => 'required',
            'event_id' => 'nullable',
            'date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:date',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'is_active' => 'required|boolean',
        ]);

        $startTime = $request->date . ' ' . $request->start_time . ':00';
        $endTime = $request->date . ' ' . $request->end_time . ':00';

        // Check for overlap
        $existingBooking = RoomBooking::where('room_id', $request->room_id)
            ->where('is_active', 1)
            ->where(function ($query) use ($startTime, $endTime) {
                $query->where('start_time', '<', $endTime)
                      ->where('end_time', '>', $startTime);
            })
            ->first();

        if ($existingBooking && $request->is_active == 1) {
            return redirect()->back()->withInput()->with('error', 'Gagal! Ruangan sudah dipesan oleh ' . $existingBooking->user->name . ' pada waktu tersebut.');
        }

        $data = $request->all();
        $data['start_time'] = $startTime;
        $data['end_time'] = $endTime;
        $data['date'] = $request->date . ' 00:00:00';
        $data['end_date'] = $request->filled('end_date') ? $request->end_date : null;

        $newBooking = RoomBooking::create($data);

        // Update User Role to PIC
        $picUser = User::find($request->user_id);
        if ($picUser && !$picUser->hasRole('pic')) {
            $picUser->assignRole('pic');
        }

        // Update Room Status
        $room = Room::find($request->room_id);
        if ($request->is_active == 1) {
            $room->update(['status' => 'booked']);
        } else {
            $room->update(['status' => 'available']);
        }

        // Send Email Notifications
        try {
            $booking = RoomBooking::with(['room', 'user', 'event'])->findOrFail($newBooking->id);
            
            // 1. Send confirmation to PIC
            if ($booking->user && $booking->user->email) {
                \Illuminate\Support\Facades\Mail::to($booking->user->email)->send(new \App\Mail\RoomBookingMail($booking, 'requested_pic'));
            }

            // 2. Send notification to all Admins
            $admins = User::role('admin')->get();
            foreach ($admins as $admin) {
                if ($admin->email) {
                    \Illuminate\Support\Facades\Mail::to($admin->email)->send(new \App\Mail\RoomBookingMail($booking, 'requested_admin'));
                }
            }
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Gagal mengirim email peminjaman baru: ' . $e->getMessage());
        }

        return redirect()->route('roombookings.index')->with('success', 'Peminjaman ruangan berhasil ditambahkan dan sedang menunggu verifikasi.');
    }

    public function show($id)
    {
        $roomBooking = RoomBooking::findOrFail($id);
        $rooms = Room::all();
        $users = User::role('pic')->get();
        $events = Event::all();
        return view('roombookings.show', compact('roomBooking', 'rooms', 'users', 'events'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'room_id' => 'required',
            'user_id' => 'required',
            'event_id' => 'nullable',
            'date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:date',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'is_active' => 'required|boolean',
        ]);

        $startTime = $request->date . ' ' . $request->start_time . ':00';
        $endTime = $request->date . ' ' . $request->end_time . ':00';

        // Check for overlap
        $existingBooking = RoomBooking::where('room_id', $request->room_id)
            ->where('is_active', 1)
            ->where('id', '!=', $id)
            ->where(function ($query) use ($startTime, $endTime) {
                $query->where('start_time', '<', $endTime)
                      ->where('end_time', '>', $startTime);
            })
            ->first();

        if ($existingBooking && $request->is_active == 1) {
            return redirect()->back()->withInput()->with('error', 'Gagal! Ruangan sudah dipesan oleh ' . $existingBooking->user->name . ' pada waktu tersebut.');
        }

        $booking = RoomBooking::findOrFail($id);
        $oldIsActive = $booking->is_active;

        $data = $request->all();
        $data['start_time'] = $startTime;
        $data['end_time'] = $endTime;
        $data['date'] = $request->date . ' 00:00:00';
        $data['end_date'] = $request->filled('end_date') ? $request->end_date : null;
        
        $booking->update($data);

        // Update User Role to PIC
        $picUser = User::find($request->user_id);
        if ($picUser && !$picUser->hasRole('pic')) {
            $picUser->assignRole('pic');
        }

        // Update Room Status
        $room = Room::find($request->room_id);
        if ($request->is_active == 1) {
            $room->update(['status' => 'booked']);
        } else {
            $room->update(['status' => 'available']);
        }

        // Send email if status changed
        if ($oldIsActive != $booking->is_active) {
            try {
                $freshBooking = RoomBooking::with(['room', 'user', 'event'])->find($id);
                if ($freshBooking && $freshBooking->user) {
                    $type = $freshBooking->is_active ? 'approved' : 'rejected';
                    \Illuminate\Support\Facades\Mail::to($freshBooking->user->email)->send(new \App\Mail\RoomBookingMail($freshBooking, $type));
                }
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::error('Gagal mengirim email perubahan status: ' . $e->getMessage());
            }
        }

        return redirect()->route('roombookings.index')->with('success', 'Peminjaman ruangan berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $roomBooking = RoomBooking::findOrFail($id);
        $roomId = $roomBooking->room_id;
        $roomBooking->delete();

        // Update Room Status back to available
        $room = Room::find($roomId);
        if ($room) {
            $room->update(['status' => 'available']);
        }

        return redirect()->route('roombookings.index')->with('success', 'Peminjaman ruangan berhasil dihapus.');
    }

    public function approve($id)
    {
        $booking = RoomBooking::with(['room', 'user', 'event'])->findOrFail($id);

        // Check for overlap
        $existingBooking = RoomBooking::where('room_id', $booking->room_id)
            ->where('is_active', 1)
            ->where('id', '!=', $id)
            ->where(function ($query) use ($booking) {
                $query->where('start_time', '<', $booking->end_time)
                      ->where('end_time', '>', $booking->start_time);
            })
            ->first();

        if ($existingBooking) {
            return redirect()->back()->with('error', 'Gagal menyetujui! Ruangan sudah dipesan oleh ' . $existingBooking->user->name . ' pada waktu tersebut.');
        }

        $booking->update(['is_active' => 1]);

        // Update Room Status
        if ($booking->room) {
            $booking->room->update(['status' => 'booked']);
        }

        // Send Email to PIC
        try {
            if ($booking->user && $booking->user->email) {
                \Illuminate\Support\Facades\Mail::to($booking->user->email)->send(new \App\Mail\RoomBookingMail($booking, 'approved'));
            }
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Gagal mengirim email persetujuan: ' . $e->getMessage());
        }

        return redirect()->back()->with('success', 'Peminjaman ruangan berhasil disetujui.');
    }

    public function reject($id)
    {
        $booking = RoomBooking::with(['room', 'user', 'event'])->findOrFail($id);

        $booking->update(['is_active' => 0]);

        // Update Room Status
        if ($booking->room) {
            $booking->room->update(['status' => 'available']);
        }

        // Send Email to PIC
        try {
            if ($booking->user && $booking->user->email) {
                \Illuminate\Support\Facades\Mail::to($booking->user->email)->send(new \App\Mail\RoomBookingMail($booking, 'rejected'));
            }
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Gagal mengirim email penolakan: ' . $e->getMessage());
        }

        return redirect()->back()->with('success', 'Peminjaman ruangan berhasil ditolak/dibatalkan.');
    }
}
