@extends('partials.main.index')

@section('content')
<div class="row">
    <div class="col-sm-12">
        <div class="home-tab">
            <div class="d-sm-flex align-items-center justify-content-between border-bottom">
                <ul class="nav nav-tabs" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active ps-0" id="home-tab" data-bs-toggle="tab" href="#overview" role="tab"
                            aria-controls="overview" aria-selected="true">Overview</a>
                    </li>
                </ul>
            </div>
            <div class="tab-content tab-content-basic">
                <div class="tab-pane fade show active" id="overview" role="tabpanel" aria-labelledby="overview">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="statistics-details d-flex align-items-center justify-content-between">
                                <div>
                                    <i class="mdi mdi-office-building mdi-24px text-primary mb-2 d-block"></i>
                                    <p class="statistics-title">Total Ruangan</p>
                                    <h3 class="rate-percentage">{{ $total_rooms }}</h3>
                                </div>
                                <div>
                                    <i class="mdi mdi-calendar-star mdi-24px text-info mb-2 d-block"></i>
                                    <p class="statistics-title">Event Aktif</p>
                                    <h3 class="rate-percentage">{{ $active_events }}</h3>
                                </div>
                                <div>
                                    <i class="mdi mdi-bookmark-check mdi-24px text-success mb-2 d-block"></i>
                                    <p class="statistics-title">Peminjaman</p>
                                    <h3 class="rate-percentage">{{ $total_bookings }}</h3>
                                </div>
                                <div class="d-none d-md-block">
                                    <i class="mdi mdi-door-closed mdi-24px text-danger mb-2 d-block"></i>
                                    <p class="statistics-title">Ruangan Terpakai</p>
                                    <h3 class="rate-percentage text-danger">{{ $occupied_rooms }}</h3>
                                </div>
                                <div class="d-none d-md-block">
                                    <i class="mdi mdi-door-open mdi-24px text-success mb-2 d-block"></i>
                                    <p class="statistics-title">Ruangan Tersedia</p>
                                    <h3 class="rate-percentage text-success">{{ $available_rooms }}</h3>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row pt-4 mt-2">
                        <div class="col-lg-8 d-flex flex-column">
                            <div class="row flex-grow">
                                <div class="col-12 grid-margin stretch-card">
                                    <div class="card card-rounded shadow-sm">
                                        <div class="card-body p-4">
                                            <div class="d-sm-flex justify-content-between align-items-start mb-4">
                                                <div>
                                                    <h4 class="card-title card-title-dash fw-bold">Booking Terakhir</h4>
                                                    <p class="text-muted small">Update peminjaman ruangan yang baru saja masuk.</p>
                                                </div>
                                            </div>
                                            <div class="table-responsive">
                                                <table class="table table-hover align-middle">
                                                    <thead>
                                                        <tr>
                                                            <th>Ruangan</th>
                                                            <th>PIC</th>
                                                            <th>Waktu</th>
                                                            <th class="text-center">Status</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach($latest_bookings as $booking)
                                                        <tr>
                                                            <td>
                                                                <div class="d-flex align-items-center">
                                                                    <div class="p-2 rounded-3 bg-light me-3">
                                                                        <i class="mdi mdi-door-open text-primary"></i>
                                                                    </div>
                                                                    <div>
                                                                        <h5 class="mb-0 fw-bold">{{ $booking->room->name }}</h5>
                                                                        <small class="text-muted">ID: #RB-{{ $booking->id }}</small>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="d-flex align-items-center">
                                                                    <img src="https://ui-avatars.com/api/?name={{ urlencode($booking->user->name) }}&background=random&color=fff" 
                                                                         class="rounded-circle me-2" style="width: 28px; height: 28px;">
                                                                    <span class="fw-semibold">{{ $booking->user->name }}</span>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="fw-bold">{{ \Carbon\Carbon::parse($booking->date)->format('d M Y') }}</div>
                                                                <small class="text-muted"><i class="mdi mdi-clock-outline"></i> {{ \Carbon\Carbon::parse($booking->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($booking->end_time)->format('H:i') }}</small>
                                                            </td>
                                                            <td class="text-center">
                                                                @if($booking->is_active)
                                                                    <span class="badge badge-opacity-success px-3 py-2 rounded-pill">Selesai</span>
                                                                @else
                                                                    <span class="badge badge-opacity-warning px-3 py-2 rounded-pill">Pending</span>
                                                                @endif
                                                            </td>
                                                        </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4 d-flex flex-column">
                            <div class="row flex-grow">
                                <div class="col-12 grid-margin stretch-card">
                                    <div class="card card-rounded shadow-sm">
                                        <div class="card-body p-4 text-center">
                                            <h4 class="card-title fw-bold mb-4 text-start">Utilisasi Ruangan</h4>
                                            <div class="chart-container" style="position: relative; height:200px; width:100%">
                                                <canvas id="roomUsageChart"></canvas>
                                            </div>
                                            <div class="mt-4 pt-2">
                                                <div class="d-flex justify-content-between mb-2 small">
                                                    <span><i class="mdi mdi-circle text-danger me-1"></i> Terpakai</span>
                                                    <span class="fw-bold">{{ $occupied_rooms }}</span>
                                                </div>
                                                <div class="d-flex justify-content-between mb-2 small">
                                                    <span><i class="mdi mdi-circle text-warning me-1"></i> Dipesan</span>
                                                    <span class="fw-bold">{{ $booked_rooms }}</span>
                                                </div>
                                                <div class="d-flex justify-content-between small">
                                                    <span><i class="mdi mdi-circle text-success me-1"></i> Tersedia</span>
                                                    <span class="fw-bold">{{ $available_rooms }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 grid-margin stretch-card">
                                    <div class="card card-rounded shadow-sm">
                                        <div class="card-body p-4">
                                            <h4 class="card-title fw-bold mb-4">Aksi Cepat</h4>
                                            <div class="d-grid gap-3">
                                                <a href="{{ route('roombookings.create') }}" class="btn btn-primary d-flex align-items-center justify-content-between p-3 rounded-4 text-white hover-up shadow-sm">
                                                    <div class="d-flex align-items-center">
                                                        <i class="mdi mdi-plus-box mdi-24px me-3"></i>
                                                        <div class="text-start">
                                                            <div class="fw-bold fs-6">Tambah Booking</div>
                                                            <small class="opacity-75">Pinjam ruangan baru</small>
                                                        </div>
                                                    </div>
                                                    <i class="mdi mdi-chevron-right"></i>
                                                </a>
                                                <a href="{{ route('events.create') }}" class="btn btn-info d-flex align-items-center justify-content-between p-3 rounded-4 text-white hover-up shadow-sm">
                                                    <div class="d-flex align-items-center">
                                                        <i class="mdi mdi-calendar-star mdi-24px me-3"></i>
                                                        <div class="text-start">
                                                            <div class="fw-bold fs-6">Buat Event</div>
                                                            <small class="opacity-75">Kelola agenda baru</small>
                                                        </div>
                                                    </div>
                                                    <i class="mdi mdi-chevron-right"></i>
                                                </a>
                                                <a href="{{ route('users.create') }}" class="btn btn-success d-flex align-items-center justify-content-between p-3 rounded-4 text-white hover-up shadow-sm">
                                                    <div class="d-flex align-items-center">
                                                        <i class="mdi mdi-account-group mdi-24px me-3"></i>
                                                        <div class="text-start">
                                                            <div class="fw-bold fs-6">Tambah User</div>
                                                            <small class="opacity-75">Daftarkan peserta/PIC</small>
                                                        </div>
                                                    </div>
                                                    <i class="mdi mdi-chevron-right"></i>
                                                </a>
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
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        if ($("#roomUsageChart").length) {
            const ctx = document.getElementById('roomUsageChart');
            new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: ['Terpakai', 'Dipesan', 'Tersedia'],
                    datasets: [{
                        data: [{{ $occupied_rooms }}, {{ $booked_rooms }}, {{ $available_rooms }}],
                        backgroundColor: [
                            "#ef4444",
                            "#f59e0b",
                            "#10b981",
                        ],
                        borderColor: "rgba(0,0,0,0)",
                        hoverOffset: 12
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '75%',
                    plugins: {
                        legend: {
                            display: false,
                        },
                        tooltip: {
                            backgroundColor: '#1f2937',
                            titleFont: { size: 13 },
                            bodyFont: { size: 13 },
                            padding: 12,
                            displayColors: false
                        }
                    }
                }
            });
        }
    });
</script>
@endsection
