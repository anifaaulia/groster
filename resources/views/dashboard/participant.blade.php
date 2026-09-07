@extends('partials.main.index')

@section('content')
<div class="row">
    <div class="col-sm-12">
        <div class="home-tab">
            <div class="d-sm-flex align-items-center justify-content-between border-bottom">
                <ul class="nav nav-tabs" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active ps-0" id="home-tab" data-bs-toggle="tab" href="#overview" role="tab"
                            aria-controls="overview" aria-selected="true">Dashboard Peserta</a>
                    </li>
                </ul>
            </div>
            <div class="tab-content tab-content-basic">
                <div class="tab-pane fade show active" id="overview" role="tabpanel" aria-labelledby="overview">
                    <div class="row">
                        <div class="col-md-4 grid-margin stretch-card">
                            <div class="card card-rounded bg-gradient-blue-dark text-white shadow-lg overflow-hidden border-0">
                                <div class="card-body p-4 position-relative">
                                    <div class="d-flex align-items-center mb-4 gap-3">
                                        <div class="bg-translucent-white p-3 rounded-4 shadow-sm">
                                            <i class="mdi mdi-calendar-check mdi-36px text-white"></i>
                                        </div>
                                        <div>
                                            <p class="text-uppercase extra-small text-white mb-0 fw-bold">Event Saya</p>
                                            <h3 class="display-5 fw-bold mb-0 text-white">{{ count($my_events) }}</h3>
                                        </div>
                                    </div>
                                    <p class="mb-0 fw-medium opacity-75 small">Anda terdaftar di {{ count($my_events) }} acara aktif pada sistem G-Roster.</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-8 grid-margin stretch-card">
                            <div class="card card-rounded bg-gradient-blue text-white shadow-lg overflow-hidden border-0">
                                <div class="card-body p-4">
                                    <div class="d-flex align-items-center mb-3 text-white">
                                        <i class="mdi mdi-information-outline mdi-24px me-2"></i>
                                        <h3 class="fw-bold mb-0">Panduan Presensi</h3>
                                    </div>
                                    <p class="text-white small lh-lg mb-3" style="opacity: 0.95;">
                                        Untuk mencatat kehadiran, silakan pilih event di bawah dan klik tombol <strong>"QR CODE"</strong>. Tampilkan kode tersebut kepada PIC di lokasi untuk dipindai sebagai syarat presensi.
                                    </p>
                                    <div class="mt-2 text-white small fw-bold d-flex align-items-center" style="opacity: 0.9;">
                                        <i class="mdi mdi-clock-check-outline me-2 fs-5"></i> Jangan terlambat agar status Anda terekam "Tepat Waktu".
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-12">
                            <h3 class="mb-4 fw-bold text-success"><i class="mdi mdi-play-circle-outline me-2"></i>Event Sedang Berlangsung</h3>
                        </div>
                    </div>
                    <div class="row mb-5">
                        @forelse($ongoing_events as $event)
                        @php
                            $currentBooking = $event->room_bookings->first(function ($b) {
                                return $b->is_active && $b->room && $b->room->status === 'occupied' && now()->between($b->start_time, $b->end_time);
                            });
                            $alreadyAttended = $currentBooking
                                ? auth()->user()->attendances()->where('room_booking_id', $currentBooking->id)->exists()
                                : false;
                        @endphp
                        <div class="col-md-6 grid-margin stretch-card">
                            <div class="card card-rounded shadow-sm border-top border-5 border-success bg-success bg-opacity-10">
                                <div class="card-body p-4">
                                    <div class="d-flex justify-content-between align-items-start mb-3">
                                        <div>
                                            <h4 class="card-title fw-bold mb-1 text-dark">{{ $event->name }}</h4>
                                            <p class="text-muted small"><i class="mdi mdi-map-marker me-1"></i>{{ $currentBooking->room->name ?? 'N/A' }}</p>
                                        </div>
                                        <span class="badge bg-success text-white rounded-pill px-3 anim-pulse">LIVE</span>
                                    </div>
                                    <div class="d-grid mt-4">
                                        @if($alreadyAttended)
                                            <button type="button" class="btn btn-outline-success py-2 fw-bold rounded-pill" disabled>
                                                <i class="mdi mdi-check-circle me-2"></i> Sudah Presensi Sesi Ini
                                            </button>
                                        @else
                                            <button type="button" class="btn btn-dark text-white py-2 fw-bold rounded-pill shadow-sm" data-bs-toggle="modal" data-bs-target="#qrModal{{ $event->id }}">
                                                <i class="mdi mdi-qrcode-scan me-2"></i> TAMPILKAN QR ABSENSI
                                            </button>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        @empty
                        <div class="col-12">
                            <div class="card card-rounded border-0 bg-white shadow-sm" style="border: 1px dashed rgba(77, 163, 255, 0.4) !important;">
                                <div class="card-body text-center py-4">
                                    <p class="text-primary mb-0 fw-semibold"><i class="mdi mdi-information-outline me-2 fs-5"></i> Belum ada kegiatan Anda yang dimulai saat ini.</p>
                                </div>
                            </div>
                        </div>
                        @endforelse
                    </div>

                    <div class="row mt-2">
                        <div class="col-sm-12">
                            <h3 class="mb-4 fw-bold text-muted">Jadwal Mendatang</h3>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12 grid-margin stretch-card">
                            <div class="card card-rounded shadow-sm border-0">
                                <div class="card-body p-0">
                                    <div class="table-responsive">
                                        <table class="table table-hover align-middle mb-0">
                                            <thead class="bg-light">
                                                <tr>
                                                    <th class="ps-4">Nama Acara</th>
                                                    <th>Lokasi</th>
                                                    <th>Waktu</th>
                                                    <th class="text-center">Status</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse($upcoming_events as $event)
                                                @php
                                                    $nextBooking = $event->room_bookings
                                                        ->filter(fn($b) => $b->is_active && $b->end_time->isFuture())
                                                        ->sortBy('start_time')
                                                        ->first();
                                                @endphp
                                                <tr>
                                                    <td class="ps-4">
                                                        <div class="fw-bold text-dark">{{ $event->name }}</div>
                                                    </td>
                                                    <td>
                                                        <div class="d-flex align-items-center small">
                                                            <i class="mdi mdi-map-marker text-danger me-1"></i>
                                                            <span>{{ $nextBooking->room->name ?? 'N/A' }}</span>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="d-flex flex-column small">
                                                            <span class="fw-semibold">{{ \Carbon\Carbon::parse($event->start_date)->format('d M Y') }}</span>
                                                            <span class="text-muted">{{ \Carbon\Carbon::parse($event->start_date)->format('H:i') }} - {{ \Carbon\Carbon::parse($event->end_date)->format('H:i') }}</span>
                                                        </div>
                                                    </td>
                                                    <td class="text-center">
                                                        <span class="badge badge-opacity-info rounded-pill px-3">Terjadwal</span>
                                                    </td>
                                                </tr>
                                                @empty
                                                <tr>
                                                    <td colspan="4" class="text-center py-4 text-muted small">Tidak ada jadwal mendatang.</td>
                                                </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@foreach($my_events as $event)
    @php
        $currentBooking = $event->room_bookings->first(function ($b) {
            return $b->is_active && $b->room && $b->room->status === 'occupied' && now()->between($b->start_time, $b->end_time);
        });

        // Attendance must be checked per CURRENT session (room booking), not just per
        // event - an event can have multiple sessions/bookings over time, and a
        // participant who already attended a past session should still be able to
        // attend a new one.
        $attendance = $currentBooking
            ? auth()->user()->attendances()->where('room_booking_id', $currentBooking->id)->first()
            : auth()->user()->attendances()->where('event_id', $event->id)->first();
    @endphp
    @if($currentBooking && !$attendance)
    <!-- Modal QR for this Event - Moved outside to fix backdrop issue -->
    <div class="modal fade" id="qrModal{{ $event->id }}" tabindex="-1" aria-labelledby="qrModalLabel{{ $event->id }}" aria-hidden="true" data-bs-backdrop="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header bg-primary text-white border-0">
                    <h5 class="modal-title fw-bold" id="qrModalLabel{{ $event->id }}">
                        <i class="mdi mdi-qrcode-scan me-2"></i>QR Presensi Event
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center p-5">
                    <p class="mb-4 text-muted">Scan QR ini di lokasi: <br><strong class="h4 text-primary">{{ $currentBooking->room->name ?? 'N/A' }}</strong></p>
                    
                    <div class="qr-container mb-4">
                        <img src="https://quickchart.io/qr?text={{ auth()->id() }}-{{ $event->id }}&size=250&margin=2" 
                             alt="QR Code" class="img-fluid border p-3 rounded-3 bg-white shadow-sm" style="max-width: 250px;">
                    </div>
                    
                    <h4 class="fw-bold text-dark mb-1">{{ $event->name }}</h4>
                    <p class="text-info small fw-bold">ID Peserta: {{ auth()->id() }}</p>
                    
                    <div class="alert alert-light mt-4 mb-0 border">
                        <p class="text-secondary small mb-0">
                            <i class="mdi mdi-clock-outline me-1"></i>
                            Silakan scan tepat waktu sesuai jadwal acara.
                        </p>
                    </div>
                </div>
                <div class="modal-footer border-0 p-3">
                    <button type="button" class="btn btn-secondary text-white w-100" data-bs-dismiss="modal">
                        <i class="mdi mdi-close me-1"></i> Tutup
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif
@endforeach
@endsection
