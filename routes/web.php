<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\InstitutionController;
use App\Http\Controllers\FacilityController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\RoomBookingController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserPanelController;

Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route('dashboard.index');
    }
    return view('welcome');
});

Route::middleware('guest')->group(function () {
    Route::get('login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('login', [AuthController::class, 'login']);
});

Route::post('logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware(['auth'])->group(function () {
    // Shared Dashboard
    Route::get('/dashboard', [\App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard.index');

    // Admin Only
    Route::middleware(['role:admin'])->prefix('admin')->group(function () {
        Route::prefix('institutions')->group(function () {
            Route::get('/', [InstitutionController::class, 'index'])->name('institutions.index');
            Route::get('form', [InstitutionController::class, 'create'])->name('institutions.create');
            Route::post('', [InstitutionController::class, 'store'])->name('institutions.store');
            Route::put('update/{id}', [InstitutionController::class, 'update'])->name('institutions.update');
            Route::delete('delete/{id}', [InstitutionController::class, 'delete'])->name('institutions.destroy');
            Route::get('show/{institution}', [InstitutionController::class, 'show'])->name('institutions.show');
        });

        Route::prefix('facilities')->group(function () {
            Route::get('/', [FacilityController::class, 'index'])->name('facilities.index');
            Route::get('form', [FacilityController::class, 'create'])->name('facilities.create');
            Route::post('', [FacilityController::class, 'store'])->name('facilities.store');
            Route::put('update/{id}', [FacilityController::class, 'update'])->name('facilities.update');
            Route::delete('delete/{id}', [FacilityController::class, 'destroy'])->name('facilities.destroy');
            Route::get('show/{facility}', [FacilityController::class, 'show'])->name('facilities.show');
        });

        Route::prefix('users')->group(function () {
            Route::get('/', [UserController::class, 'index'])->name('users.index');
            Route::get('form', [UserController::class, 'create'])->name('users.create');
            Route::post('', [UserController::class, 'store'])->name('users.store');
            Route::put('update/{id}', [UserController::class, 'update'])->name('users.update');
            Route::delete('delete/{id}', [UserController::class, 'destroy'])->name('users.destroy');
            Route::get('show/{user}', [UserController::class, 'show'])->name('users.show');
            Route::post('import', [UserController::class, 'importUsers'])->name('users.import');
            Route::get('template', [UserController::class, 'downloadUsersTemplate'])->name('users.template');
        });
    });

    // Shared Rooms View
    Route::get('/rooms', [RoomController::class, 'index'])->name('rooms.index');

    // Admin & PIC (Managerial)
    Route::middleware(['role:admin|pic'])->group(function () {
        Route::prefix('rooms')->group(function () {
            Route::post('update-status/{room}', [RoomController::class, 'updateStatus'])->name('rooms.updateStatus');
        });

        // Only Admin can CREATE/EDIT rooms
        Route::middleware(['role:admin'])->prefix('rooms')->group(function () {
            Route::get('form', [RoomController::class, 'create'])->name('rooms.create');
            Route::post('', [RoomController::class, 'store'])->name('rooms.store');
            Route::put('update/{id}', [RoomController::class, 'update'])->name('rooms.update');
            Route::delete('delete/{id}', [RoomController::class, 'destroy'])->name('rooms.destroy');
            Route::get('show/{room}', [RoomController::class, 'show'])->name('rooms.show');
        });

        Route::prefix('roombookings')->group(function () {
            Route::get('/', [RoomBookingController::class, 'index'])->name('roombookings.index');
            Route::get('export', [RoomBookingController::class, 'export'])->name('roombookings.export');
            Route::get('recommend', [RoomBookingController::class, 'recommendRooms'])->name('roombookings.recommend');

            // Both Admin and PIC can CREATE bookings
            Route::get('form', [RoomBookingController::class, 'create'])->name('roombookings.create');
            Route::post('', [RoomBookingController::class, 'store'])->name('roombookings.store');

            Route::middleware(['role:admin'])->group(function () {
                Route::put('update/{id}', [RoomBookingController::class, 'update'])->name('roombookings.update');
                Route::delete('delete/{id}', [RoomBookingController::class, 'destroy'])->name('roombookings.destroy');
                Route::post('{roomBooking}/approve', [RoomBookingController::class, 'approve'])->name('roombookings.approve');
                Route::post('{roomBooking}/reject', [RoomBookingController::class, 'reject'])->name('roombookings.reject');
            });
            Route::get('show/{roomBooking}', [RoomBookingController::class, 'show'])->name('roombookings.show');
            Route::post('{roomBooking}/start-session', [RoomBookingController::class, 'startSession'])->name('roombookings.startSession');
        });

        Route::prefix('events')->group(function () {
            Route::get('/', [EventController::class, 'index'])->name('events.index');
            // Admin & PIC can create events
            Route::get('form', [EventController::class, 'create'])->name('events.create');
            Route::post('', [EventController::class, 'store'])->name('events.store');
            Route::get('show/{event}', [EventController::class, 'show'])->name('events.show');
            Route::get('{event}/export-attendance', [EventController::class, 'exportAttendance'])->name('events.export_attendance');
            // Admin & PIC can add participants
            Route::post('{event}/participants', [EventController::class, 'addParticipant'])->name('events.participants.add');
            // Only Admin can update, delete, approve, remove participants, and import
            Route::middleware(['role:admin'])->group(function () {
                Route::put('update/{id}', [EventController::class, 'update'])->name('events.update');
                Route::delete('delete/{id}', [EventController::class, 'destroy'])->name('events.destroy');
                Route::post('{event}/approve', [EventController::class, 'approve'])->name('events.approve');
                Route::delete('{event}/participants/{user}', [EventController::class, 'removeParticipant'])->name('events.participants.remove');
                Route::post('{event}/participants/import', [EventController::class, 'importParticipants'])->name('events.participants.import');
                Route::get('participants/template', [EventController::class, 'downloadParticipantsTemplate'])->name('events.participants.template');
            });
        });

        Route::prefix('attendances')->group(function () {
            Route::get('/', [AttendanceController::class, 'index'])->name('attendances.index');
            Route::get('form', [AttendanceController::class, 'create'])->name('attendances.create');
            Route::post('', [AttendanceController::class, 'store'])->name('attendances.store');
            Route::put('update/{id}', [AttendanceController::class, 'update'])->name('attendances.update');
            Route::delete('delete/{id}', [AttendanceController::class, 'destroy'])->name('attendances.destroy');
            Route::get('show/{attendance}', [AttendanceController::class, 'show'])->name('attendances.show');
            Route::get('scan', [AttendanceController::class, 'scan'])->name('attendances.scan');
            Route::post('checkin', [AttendanceController::class, 'checkin'])->name('attendances.checkin');
            Route::post('manual', [AttendanceController::class, 'manualCheckin'])->name('attendances.manual');
        });
    });

    // Participant
    Route::middleware(['role:participant'])->group(function () {
        Route::get('/my-events', [\App\Http\Controllers\UserPanelController::class, 'events'])->name('user.events');
        Route::get('/my-attendance', [\App\Http\Controllers\UserPanelController::class, 'attendance'])->name('user.attendance');
        Route::get('/my-profile', [\App\Http\Controllers\UserPanelController::class, 'profile'])->name('user.profile');
    });
});
