@extends('partials.main.index')

@section('content')
<div class="row">
    <div class="col-sm-12">
        <div class="home-tab">
            <div class="d-sm-flex align-items-center justify-content-between border-bottom">
                <ul class="nav nav-tabs" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active ps-0" id="home-tab" data-bs-toggle="tab" href="#overview" role="tab"
                            aria-controls="overview" aria-selected="true">Dashboard PIC</a>
                    </li>
                </ul>
            </div>
            <div class="tab-content tab-content-basic">
                <div class="tab-pane fade show active" id="overview" role="tabpanel" aria-labelledby="overview">
                    <div class="row">
                        <div class="col-sm-12">
                            <h3 class="mb-4 fw-bold">Kegiatan Saat Ini</h3>
                        </div>
                    </div>
                    <div class="row mb-4">
                        @forelse($ongoing_bookings as $booking)
                        <div class="col-md-6 grid-margin stretch-card">
                            <div class="card card-rounded shadow-sm border-top border-5 border-success">
                                <div class="card-body p-4">
                                    <div class="d-flex justify-content-between align-items-start mb-3">
                                        <div class="d-flex align-items-center">
                                            <div>
                                                <h4 class="card-title fw-bold mb-1">{{ $booking->event->name }}</h4>
                                                <p class="text-muted small"><i class="mdi mdi-map-marker me-1"></i>{{ $booking->room->name }}</p>
                                            </div>
                                        </div>
                                        <span class="badge badge-opacity-success rounded-pill px-3">SEDANG BERLANGSUNG</span>
                                    </div>
                                    
                                    <div class="bg-light p-3 rounded-4 mb-4">
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <span class="text-muted small">Waktu Sesi:</span>
                                            <span class="fw-bold text-dark">{{ \Carbon\Carbon::parse($booking->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($booking->end_time)->format('H:i') }}</span>
                                        </div>
                                    </div>

                                    <div class="d-grid gap-2">
                                        <form action="{{ route('rooms.updateStatus', $booking->room->id) }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="status" value="available">
                                            <button type="submit" class="btn btn-outline-success w-100 py-2 fw-bold">
                                                <i class="mdi mdi-check-circle-outline me-2"></i> Selesaikan Sesi
                                            </button>
                                        </form>
                                        <a href="{{ route('attendances.scan', ['event_id' => $booking->event_id]) }}" class="btn btn-dark text-white w-100 py-2 fw-bold shadow-sm">
                                            <i class="mdi mdi-qrcode-scan me-2"></i> Scan Presensi Peserta
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @empty
                        <div class="col-12">
                            <div class="card card-rounded bg-light border-0">
                                <div class="card-body text-center py-4">
                                    <p class="text-muted mb-0"><i class="mdi mdi-information-outline me-1"></i> Tidak ada kegiatan yang sedang berlangsung saat ini.</p>
                                </div>
                            </div>
                        </div>
                        @endforelse
                    </div>

                    <div class="row mt-2">
                        <div class="col-sm-12">
                            <h3 class="mb-4 fw-bold">Jadwal Mendatang</h3>
                        </div>
                    </div>
                    <div class="row">
                        @forelse($upcoming_bookings as $booking)
                        <div class="col-md-6 grid-margin stretch-card">
                            <div class="card card-rounded shadow-sm">
                                <div class="card-body p-4">
                                    <div class="d-flex justify-content-between align-items-start mb-3">
                                        <div>
                                            <h4 class="card-title fw-bold mb-1 text-muted">{{ $booking->event->name }}</h4>
                                            <p class="text-muted small"><i class="mdi mdi-map-marker me-1"></i>{{ $booking->room->name }}</p>
                                        </div>
                                        <span class="badge badge-opacity-warning rounded-pill px-3">Terjadwal</span>
                                    </div>
                                    <div class="bg-light p-3 rounded-4 mb-3">
                                        <div class="d-flex justify-content-between align-items-center mb-1">
                                            <span class="text-muted small">Tanggal:</span>
                                            <span class="fw-bold text-dark">
                                                {{ \Carbon\Carbon::parse($booking->date)->format('d M Y') }}
                                                @if($booking->end_date && $booking->end_date->format('Y-m-d') !== \Carbon\Carbon::parse($booking->date)->format('Y-m-d'))
                                                    &mdash; {{ \Carbon\Carbon::parse($booking->end_date)->format('d M Y') }}
                                                @endif
                                            </span>
                                        </div>
                                        <div class="d-flex justify-content-between align-items-center">
                                            <span class="text-muted small">Waktu:</span>
                                            <span class="fw-bold text-dark">{{ \Carbon\Carbon::parse($booking->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($booking->end_time)->format('H:i') }}</span>
                                        </div>
                                    </div>
                                    @php
                                        $canStartSession = now()->between($booking->start_time, $booking->end_time);
                                    @endphp
                                    <div class="d-grid gap-2">
                                        @if($canStartSession)
                                            <form action="{{ route('roombookings.startSession', $booking->id) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="btn btn-primary text-white w-100 py-2 fw-bold shadow-sm">
                                                    <i class="mdi mdi-play-circle-outline me-2"></i> Mulai Sesi Sekarang
                                                </button>
                                            </form>
                                        @else
                                            <button type="button" class="btn btn-secondary text-white w-100 py-2 fw-bold shadow-sm" disabled
                                                title="Sesi hanya dapat dimulai pada pukul {{ \Carbon\Carbon::parse($booking->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($booking->end_time)->format('H:i') }}">
                                                <i class="mdi mdi-clock-outline me-2"></i> Belum Bisa Dimulai
                                            </button>
                                            <small class="text-muted text-center">Sesi dapat dimulai pukul {{ \Carbon\Carbon::parse($booking->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($booking->end_time)->format('H:i') }}</small>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        @empty
                        <p class="text-muted ms-3">Belum ada jadwal mendatang.</p>
                        @endforelse
                    </div>

                    <div class="row mt-4">
                        <div class="col-sm-12">
                            <h3 class="mb-4 fw-bold">Pengajuan Peminjaman Menunggu Verifikasi</h3>
                        </div>
                    </div>
                    <div class="row">
                        @forelse($pending_bookings as $booking)
                        <div class="col-md-6 grid-margin stretch-card">
                            <div class="card card-rounded shadow-sm border-top border-5 border-warning">
                                <div class="card-body p-4">
                                    <div class="d-flex justify-content-between align-items-start mb-3">
                                        <div>
                                            <h4 class="card-title fw-bold mb-1 text-muted">{{ $booking->event ? $booking->event->name : 'Tanpa Acara khusus' }}</h4>
                                            <p class="text-muted small"><i class="mdi mdi-map-marker me-1"></i>{{ $booking->room ? $booking->room->name : '-' }}</p>
                                        </div>
                                        <span class="badge badge-opacity-warning rounded-pill px-3">Menunggu</span>
                                    </div>
                                    <div class="bg-light p-3 rounded-4 mb-3">
                                        <div class="d-flex justify-content-between align-items-center mb-1">
                                            <span class="text-muted small">Tanggal:</span>
                                            <span class="fw-bold text-dark">{{ \Carbon\Carbon::parse($booking->date)->format('d M Y') }}</span>
                                        </div>
                                        <div class="d-flex justify-content-between align-items-center">
                                            <span class="text-muted small">Waktu:</span>
                                            <span class="fw-bold text-dark">{{ \Carbon\Carbon::parse($booking->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($booking->end_time)->format('H:i') }}</span>
                                        </div>
                                    </div>
                                    <div class="d-grid gap-2">
                                        <a href="{{ route('roombookings.show', $booking->id) }}" class="btn btn-outline-secondary py-2 fw-bold">
                                            <i class="mdi mdi-eye me-2"></i> Lihat Detail Pengajuan
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @empty
                        <div class="col-12">
                            <div class="card card-rounded bg-light border-0">
                                <div class="card-body text-center py-4">
                                    <p class="text-muted mb-0"><i class="mdi mdi-information-outline me-1"></i> Tidak ada pengajuan peminjaman yang sedang menunggu verifikasi.</p>
                                </div>
                            </div>
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
