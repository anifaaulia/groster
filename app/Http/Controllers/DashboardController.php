<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\Event;
use App\Models\RoomBooking;
use App\Models\Attendance;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if ($user->hasRole('admin')) {
            $data = [
                'total_rooms' => Room::count(),
                'active_events' => Event::count(),
                'total_bookings' => RoomBooking::count(),
                'occupied_rooms' => Room::where('status', 'occupied')->count(),
                'available_rooms' => Room::where('status', 'available')->count(),
                'booked_rooms' => Room::where('status', 'booked')->count(),
                'latest_bookings' => RoomBooking::with(['room', 'user'])->latest()->take(5)->get(),
            ];
            return view('dashboard.admin', $data);
        }

        if ($user->hasRole('pic')) {
            $all_active_bookings = RoomBooking::with(['room', 'event'])
                ->where('user_id', $user->id)
                ->where('is_active', 1)
                ->get();

            $pending_bookings = RoomBooking::with(['room', 'event'])
                ->where('user_id', $user->id)
                ->where('is_active', 0)
                ->latest()
                ->get();

            $now = now();
            $ongoing_bookings = $all_active_bookings->filter(function($b) use ($now) {
                return $b->room->status === 'occupied' && $now->between($b->start_time, $b->end_time);
            });
            $upcoming_bookings = $all_active_bookings->filter(function($b) use ($now, $ongoing_bookings) {
                return !$ongoing_bookings->contains('id', $b->id)
                    && $b->date->gte($now->copy()->startOfDay());
            });

            $data = [
                'ongoing_bookings' => $ongoing_bookings,
                'upcoming_bookings' => $upcoming_bookings,
                'pending_bookings' => $pending_bookings,
                'total_attendance' => Attendance::whereIn('room_id', $all_active_bookings->pluck('room_id'))->count(),
            ];
            return view('dashboard.pic', $data);
        }

        if ($user->hasRole('participant')) {
            $my_events = $user->participatingEvents()->with('room_bookings.room')->get();

            $now = now();
            $ongoing_events = $my_events->filter(function($e) use ($now) {
                return $e->room_bookings->filter(function($b) use ($now) {
                    return $b->is_active && $b->room->status === 'occupied' && $now->between($b->start_time, $b->end_time);
                })->count() > 0;
            });
            $upcoming_events = $my_events->filter(function($e) use ($now, $ongoing_events) {
                if ($ongoing_events->contains('id', $e->id)) {
                    return false;
                }
                return $e->room_bookings->filter(function($b) use ($now) {
                    return $b->is_active && $b->end_time->isFuture();
                })->count() > 0;
            });

            $data = [
                'my_events' => $my_events,
                'ongoing_events' => $ongoing_events,
                'upcoming_events' => $upcoming_events,
                'my_attendance' => Attendance::where('user_id', $user->id)->count(),
            ];
            return view('dashboard.participant', $data);
        }

        return view('dashboard.index');
    }
}
