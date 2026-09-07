<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserPanelController extends Controller
{
    public function dashboard()
    {
        return view('user.dashboard');
    }

    public function events()
    {
        $user = Auth::user();
        if ($user) {
            $events = $user->participatingEvents()
                ->with(['attendances' => function ($q) use ($user) {
                    $q->where('user_id', $user->id);
                }])
                ->get();
        } else {
            $events = collect();
        }
        return view('user.events', compact('events'));
    }

    public function attendance()
    {
        $user = Auth::user();
        if ($user) {
            $attendances = $user->attendances()->with(['room'])->get();
        } else {
            $attendances = collect();
        }
        return view('user.attendance', compact('attendances'));
    }

    public function profile()
    {
        $user = Auth::user();
        return view('user.profile', compact('user'));
    }
}
