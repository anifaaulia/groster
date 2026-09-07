<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\User;
use App\Exports\AttendanceExport;
use App\Exports\ParticipantsTemplateExport;
use App\Imports\ParticipantsImport;
use Maatwebsite\Excel\Facades\Excel;

class EventController extends Controller
{
    public function index()
    {
        $events = Event::with('user')->get();

        return view('events.index', compact('events'));
    }

    public function create()
    {
        if (auth()->user()->hasRole('pic')) {
            return view('events.create', ['users' => collect([auth()->user()])]);
        }

        $users = User::role('pic')->get();
        if ($users->isEmpty()) {
            $users = User::all();
        }
        return view('events.create', compact('users'));
    }

    public function store(Request $request)
    {
        if (auth()->user()->hasRole('pic')) {
            $request->merge([
                'user_id' => auth()->id(),
                'is_approved' => 0,
            ]);
        } else {
            $request->merge(['is_approved' => 1]);
        }

        $request->validate([
            'name' => 'required',
            'user_id' => 'required',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        Event::create($request->only(['name', 'user_id', 'start_date', 'end_date', 'is_approved']));

        if (auth()->user()->hasRole('pic')) {
            return redirect()->route('events.index')->with('success', 'Acara berhasil ditambahkan dan menunggu persetujuan admin.');
        }

        return redirect()->route('events.index')->with('success', 'Acara berhasil ditambahkan');
    }

    public function show(Request $request, $id)
    {
        $event = Event::with([
            'participants',
            'attendances.user',
            'attendances.room',
            'room_bookings.room',
            'room_bookings.user',
        ])->findOrFail($id);

        $dateFrom = $request->query('date_from');
        $dateTo = $request->query('date_to');

        $attendances = $event->attendances->sortBy('created_at');

        if ($dateFrom) {
            $attendances = $attendances->filter(
                fn($a) => $a->created_at->copy()->startOfDay()->gte(\Carbon\Carbon::parse($dateFrom)->startOfDay())
            );
        }
        if ($dateTo) {
            $attendances = $attendances->filter(
                fn($a) => $a->created_at->copy()->endOfDay()->lte(\Carbon\Carbon::parse($dateTo)->endOfDay())
            );
        }

        $attendances = $attendances->values();

        $users = User::role('participant')->get();
        return view('events.show', compact('event', 'users', 'attendances', 'dateFrom', 'dateTo'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required',
            'user_id' => 'required',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        $event = Event::findOrFail($id);
        $event->update($request->only(['name', 'user_id', 'start_date', 'end_date']));

        return redirect()->route('events.index')->with('success', 'Acara berhasil diperbarui');
    }

    public function destroy($id)
    {
        $event = Event::findOrFail($id);
        $event->delete();
        return redirect()->route('events.index')->with('success', 'Acara berhasil dihapus');
    }

    public function approve(Event $event)
    {
        $event->update(['is_approved' => 1]);
        return redirect()->back()->with('success', 'Acara berhasil disetujui.');
    }

    public function addParticipant(Request $request, Event $event)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id'
        ]);

        $event->participants()->syncWithoutDetaching([$request->user_id]);

        return back()->with('success', 'Peserta berhasil ditambahkan ke event.');
    }

    public function removeParticipant(Event $event, User $user)
    {
        $event->participants()->detach($user->id);

        return back()->with('success', 'Peserta berhasil dihapus dari event.');
    }

    public function importParticipants(Request $request, Event $event)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls,csv|max:2048',
        ]);

        $import = new ParticipantsImport($event);
        Excel::import($import, $request->file('file'));

        $r = $import->results;
        $msg = "Import selesai: {$r['added']} peserta berhasil ditambahkan.";

        if ($r['already_exists'] > 0) {
            $msg .= " {$r['already_exists']} sudah terdaftar (dilewati).";
        }
        if (!empty($r['not_found'])) {
            $msg .= " Email tidak ditemukan/bukan peserta: " . implode(', ', $r['not_found']) . ".";
        }

        return back()->with('success', $msg);
    }

    public function downloadParticipantsTemplate()
    {
        return Excel::download(new ParticipantsTemplateExport(), 'template-import-peserta.xlsx');
    }

    public function exportAttendance(Request $request, Event $event)
    {
        $filename = 'presensi-' . preg_replace('/[^a-zA-Z0-9\-]/', '-', $event->name) . '.xlsx';

        $dateFrom = $request->query('date_from');
        $dateTo = $request->query('date_to');

        return Excel::download(new AttendanceExport($event, $dateFrom, $dateTo), $filename);
    }
}
