<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Attendance;
use App\Models\User;
use App\Models\Room;
use App\Models\Event;
use App\Models\RoomBooking;
use Carbon\Carbon;

class AttendanceController extends Controller
{
    public function index()
    {
        $attendances = Attendance::with(['user', 'room', 'event'])->get();
        return view('attendances.index', compact('attendances'));
    }

    public function create()
    {
        $users = User::all();
        $rooms = Room::all();
        return view('attendances.create', compact('users', 'rooms'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required',
            'room_id' => 'required',
            'status' => 'required|in:late,left_early,on_time',
        ]);

        Attendance::create($request->all());
        return redirect()->route('attendances.index')->with('success', 'Kehadiran berhasil ditambahkan');
    }

    public function show($id)
    {
        $attendance = Attendance::findOrFail($id);
        $users = User::all();
        $rooms = Room::all();
        return view('attendances.show', compact('attendance', 'users', 'rooms'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'user_id' => 'required',
            'room_id' => 'required',
            'status' => 'required|in:late,left_early,on_time',
        ]);

        $attendance = Attendance::findOrFail($id);
        $attendance->update($request->all());

        return redirect()->route('attendances.index')->with('success', 'Kehadiran berhasil diperbarui');
    }

    /**
     * Find the room booking (session) of an event that is currently in progress,
     * i.e. now() falls between its start_time and end_time. An event can have
     * multiple bookings/sessions over time (e.g. a new room booking submitted
     * after a previous session already ended), so attendance must always be
     * tied to the specific session, not just the event.
     */
    private function currentSessionFor(Event $event): ?RoomBooking
    {
        return RoomBooking::where('event_id', $event->id)
            ->where('is_active', 1)
            ->where('start_time', '<=', now())
            ->where('end_time', '>=', now())
            ->latest('start_time')
            ->first();
    }

    public function scan(Request $request)
    {
        $eventId = $request->query('event_id');
        $event = Event::with('room_bookings.room')->findOrFail($eventId);
        $currentBooking = $this->currentSessionFor($event);

        return view('attendances.scan', compact('event', 'currentBooking'));
    }

    public function checkin(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'event_id' => 'required|exists:events,id',
            'room_booking_id' => 'required|exists:room_bookings,id',
        ]);

        $user = User::findOrFail($request->user_id);
        $event = Event::findOrFail($request->event_id);
        $roomBooking = RoomBooking::where('id', $request->room_booking_id)
            ->where('event_id', $event->id)
            ->firstOrFail();

        // Security check: is user a legitimate participant of this event?
        if (!$event->participants()->where('users.id', $user->id)->exists()) {
            return response()->json([
                'message' => "User " . $user->name . " tidak terdaftar di event ini!"
            ], 403);
        }

        // Check for duplicate scan within THIS specific session/room booking.
        // A participant must be able to check in again for a new session
        // (e.g. a new room booking submitted for the same event) even if they
        // already attended a previous session of that event.
        $exists = Attendance::where('user_id', $user->id)
            ->where('room_booking_id', $roomBooking->id)
            ->exists();

        if ($exists) {
            return response()->json([
                'message' => $user->name . " sudah diabsen sebelumnya untuk sesi ini."
            ], 422);
        }

        // AUTO VALIDATION: "on_time" only within the first 30 minutes after the
        // session's scheduled start; otherwise it's marked "late".
        $currentTime = Carbon::now();
        $status = 'on_time';

        if ($currentTime->greaterThan(Carbon::parse($roomBooking->start_time)->addMinutes(30))) {
            $status = 'late';
        }

        Attendance::create([
            'user_id' => $request->user_id,
            'room_id' => $roomBooking->room_id,
            'event_id' => $request->event_id,
            'room_booking_id' => $roomBooking->id,
            'status' => $status
        ]);

        $msg = "Absensi berhasil untuk " . $user->name . "!";
        if ($status == 'late') {
            $msg = "Absensi berhasil untuk " . $user->name . " (TERLAMBAT)";
        }

        return response()->json([
            'message' => $msg,
            'user_name' => $user->name,
            'status' => $status
        ]);
    }

    public function manualCheckin(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'event_id' => 'required|exists:events,id',
            'status' => 'required|in:on_time,late,left_early',
        ]);

        $event = Event::with('room_bookings')->findOrFail($request->event_id);
        $roomBooking = $this->currentSessionFor($event) ?? $event->room_bookings->sortByDesc('start_time')->first();
        $room_id = $roomBooking->room_id ?? Room::first()->id;

        $attendance = Attendance::updateOrCreate(
            ['user_id' => $request->user_id, 'room_booking_id' => $roomBooking->id ?? null, 'event_id' => $request->event_id],
            ['status' => $request->status, 'room_id' => $room_id]
        );

        return back()->with('success', 'Status kehadiran berhasil diperbarui untuk ' . $attendance->user->name);
    }

    public function destroy($id)
    {
        $attendance = Attendance::findOrFail($id);
        $attendance->delete();
        return redirect()->route('attendances.index')->with('success', 'Kehadiran berhasil dihapus');
    }
}
