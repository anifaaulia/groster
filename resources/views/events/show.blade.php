@extends('partials.main.index')

@section('content')

{{-- ===== SECTION: Penggunaan Ruangan per Tanggal ===== --}}
<div class="row mb-4">
    <div class="col-md-12 grid-margin stretch-card">
        <div class="card card-rounded shadow-sm">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div>
                        <h3 class="card-title mb-1">{{ $event->name }}</h3>
                        <p class="text-muted small mb-0">
                            <i class="mdi mdi-calendar-range me-1"></i>
                            {{ \Carbon\Carbon::parse($event->start_date)->format('d M Y, H:i') }}
                            &nbsp;&mdash;&nbsp;
                            {{ \Carbon\Carbon::parse($event->end_date)->format('d M Y, H:i') }}
                            &nbsp;|&nbsp;
                            <strong>PIC:</strong> {{ $event->user->name ?? '-' }}
                            &nbsp;|&nbsp;
                            @if($event->is_approved)
                                <span class="badge badge-success">Disetujui</span>
                            @else
                                <span class="badge badge-warning">Menunggu Persetujuan</span>
                            @endif
                        </p>
                    </div>
                    <div class="d-flex gap-2">
                        <a href="{{ route('roombookings.create') }}?event_id={{ $event->id }}"
                            class="btn btn-primary btn-sm text-white">
                            <i class="mdi mdi-plus me-1"></i>Tambah Peminjaman Ruangan
                        </a>
                    </div>
                </div>

                <h4 class="fw-bold mb-2"><i class="mdi mdi-home-clock me-1"></i>Tracking Penggunaan Ruangan per Tanggal</h4>
                @if($event->room_bookings->isEmpty())
                    <p class="text-muted text-center py-3 mb-0">
                        <i class="mdi mdi-calendar-remove me-1"></i>Belum ada peminjaman ruangan untuk acara ini.
                    </p>
                @else
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>Tanggal Mulai</th>
                                    <th>Tanggal Selesai</th>
                                    <th>Ruangan</th>
                                    <th>Waktu Mulai</th>
                                    <th>Waktu Selesai</th>
                                    <th>Peminjam</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($event->room_bookings->sortBy('date') as $booking)
                                    <tr>
                                        <td>{{ \Carbon\Carbon::parse($booking->date)->format('d M Y') }}</td>
                                        <td>{{ $booking->end_date ? \Carbon\Carbon::parse($booking->end_date)->format('d M Y') : '-' }}</td>
                                        <td><strong>{{ $booking->room ? $booking->room->name : '-' }}</strong></td>
                                        <td>{{ \Carbon\Carbon::parse($booking->start_time)->format('H:i') }}</td>
                                        <td>{{ \Carbon\Carbon::parse($booking->end_time)->format('H:i') }}</td>
                                        <td>{{ $booking->user ? $booking->user->name : '-' }}</td>
                                        <td>
                                            @if($booking->is_active)
                                                <label class="badge badge-success mb-0">Disetujui</label>
                                            @else
                                                <label class="badge badge-warning mb-0">Menunggu</label>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

{{-- ===== SECTION: Peserta & Presensi Terkini ===== --}}
<div class="row">
    {{-- Left: Participants Table --}}
    <div class="col-md-8 grid-margin stretch-card">
        <div class="card card-rounded shadow-sm">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h3 class="card-title mb-0">Peserta & Presensi</h3>
                    <div class="d-flex gap-2 align-items-center flex-wrap">
                        <span class="badge badge-info">{{ count($event->participants) }} Peserta</span>
                        <a href="{{ route('attendances.scan') }}?event_id={{ $event->id }}"
                            class="btn btn-secondary btn-sm text-white" title="Buka Scanner QR">
                            <i class="mdi mdi-qrcode-scan me-1"></i>Scan QR
                        </a>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Nama Peserta</th>
                                <th>Email</th>
                                <th class="text-center">Waktu Presensi</th>
                                <th class="text-center">Status Kehadiran</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($event->participants as $participant)
                            <tr>
                                <td><strong>{{ $participant->name }}</strong></td>
                                <td>{{ $participant->email }}</td>
                                <td class="text-center">
                                    @php
                                        $att = $event->attendances->where('user_id', $participant->id)->sortByDesc('created_at')->first();
                                    @endphp
                                    @if($att)
                                        <span class="small">{{ \Carbon\Carbon::parse($att->created_at)->format('d M Y, H:i') }}</span>
                                    @else
                                        <span class="text-muted extra-small italic">-</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if($att)
                                        @if($att->status == 'on_time')
                                            <label class="badge badge-success mb-0">Hadir (On Time)</label>
                                        @elseif($att->status == 'late')
                                            <label class="badge badge-warning mb-0">Terlambat</label>
                                        @else
                                            <label class="badge badge-danger mb-0">Pulang Cepat</label>
                                        @endif
                                    @else
                                        <span class="text-muted extra-small italic">Belum Presensi</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <div class="d-flex justify-content-center gap-1">
                                        {{-- Manual Attendance Buttons --}}
                                        <form action="{{ route('attendances.manual') }}" method="POST" class="d-inline">
                                            @csrf
                                            <input type="hidden" name="user_id" value="{{ $participant->id }}">
                                            <input type="hidden" name="event_id" value="{{ $event->id }}">
                                            <input type="hidden" name="status" value="on_time">
                                            <button type="submit" class="btn btn-inverse-success btn-xs px-2" title="Mark Tepat Waktu">
                                                <i class="mdi mdi-check"></i>
                                            </button>
                                        </form>
                                        <form action="{{ route('attendances.manual') }}" method="POST" class="d-inline">
                                            @csrf
                                            <input type="hidden" name="user_id" value="{{ $participant->id }}">
                                            <input type="hidden" name="event_id" value="{{ $event->id }}">
                                            <input type="hidden" name="status" value="late">
                                            <button type="submit" class="btn btn-inverse-warning btn-xs px-2" title="Mark Terlambat">
                                                <i class="mdi mdi-clock-alert"></i>
                                            </button>
                                        </form>

                                        {{-- QR Modal Trigger --}}
                                        <button type="button" class="btn btn-inverse-primary btn-xs px-2 show-qr-btn"
                                            data-user-id="{{ $participant->id }}"
                                            data-user-name="{{ $participant->name }}"
                                            data-bs-toggle="modal" data-bs-target="#qrModal" title="Lihat QR">
                                            <i class="mdi mdi-qrcode"></i>
                                        </button>

                                        {{-- Remove Participant --}}
                                        @role('admin')
                                        <form action="{{ route('events.participants.remove', [$event->id, $participant->id]) }}" method="POST" class="d-inline">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn btn-inverse-danger btn-xs px-2"
                                                onclick="return confirm('Hapus peserta dari event ini?')" title="Hapus dari Event">
                                                <i class="mdi mdi-account-minus"></i>
                                            </button>
                                        </form>
                                        @endrole
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center italic text-muted p-4">Belum ada peserta terdaftar.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-4">
                    <a href="{{ route('events.index') }}" class="btn btn-light">
                        <i class="mdi mdi-arrow-left me-1"></i> Kembali ke Daftar Acara
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- Right: Management & Info --}}
    <div class="col-md-4 grid-margin stretch-card">
        <div class="card card-rounded shadow-sm">
            <div class="card-body">
                @if(request()->query('edit'))
                    <h3 class="card-title">Edit Detail Event</h3>
                    <form action="{{ route('events.update', $event->id) }}" method="POST">
                        @csrf @method('PUT')
                        <div class="form-group mb-3">
                            <label>Nama Event</label>
                            <input type="text" name="name" class="form-control" value="{{ $event->name }}" required>
                        </div>
                        <div class="form-group mb-3">
                            <label>Penanggung Jawab (PIC)</label>
                            <select name="user_id" class="form-control" required>
                                @foreach($users as $user)
                                    @if($user->hasRole('pic') || $user->id == $event->user_id)
                                    <option value="{{ $user->id }}" {{ $event->user_id == $user->id ? 'selected' : '' }}>
                                        {{ $user->name }}
                                    </option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group mb-3">
                            <label>Waktu Mulai</label>
                            <input type="datetime-local" name="start_date" class="form-control"
                                value="{{ \Carbon\Carbon::parse($event->start_date)->format('Y-m-d\TH:i') }}" required>
                        </div>
                        <div class="form-group mb-3">
                            <label>Waktu Selesai</label>
                            <input type="datetime-local" name="end_date" class="form-control"
                                value="{{ \Carbon\Carbon::parse($event->end_date)->format('Y-m-d\TH:i') }}" required>
                        </div>
                        <div class="d-grid mt-4">
                            <button type="submit" class="btn btn-warning text-white mb-2">Simpan Perubahan</button>
                            <a href="{{ route('events.show', $event->id) }}" class="btn btn-link">Batal</a>
                        </div>
                    </form>
                @else
                    @if(auth()->user()->hasRole('admin') || auth()->user()->hasRole('pic'))
                    <div class="mb-3">
                        <h3 class="card-title">Tambahkan Peserta</h3>
                        <p class="small text-muted">Daftarkan user baru ke event ini.</p>
                        <form action="{{ route('events.participants.add', $event->id) }}" method="POST">
                            @csrf
                            <div class="form-group mb-3">
                                <label class="small">Pilih User</label>
                                <select name="user_id" class="form-control select2" required>
                                    <option value="">-- Pilih User --</option>
                                    @foreach($users as $user)
                                        @if($user->hasRole('participant') && !$event->participants->pluck('id')->contains($user->id))
                                            <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary text-white w-100">
                                <i class="mdi mdi-account-plus me-1"></i> Daftarkan Peserta
                            </button>
                        </form>
                    </div>
                    @endif

                    @role('admin')
                    <div class="mb-4 pt-3 border-top">
                        <h4 class="fw-bold mb-1"><i class="mdi mdi-file-excel me-1 text-success"></i>Import Peserta via Excel</h4>
                        <p class="small text-muted mb-2">
                            Upload file <code>.xlsx</code> / <code>.xls</code> dengan kolom <strong>email</strong>.
                        </p>
                        <form action="{{ route('events.participants.import', $event->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="form-group mb-2">
                                <input type="file" name="file" class="form-control form-control-sm" accept=".xlsx,.xls,.csv" required>
                            </div>
                            <button type="submit" class="btn btn-success text-white w-100 mb-2">
                                <i class="mdi mdi-upload me-1"></i> Import Excel
                            </button>
                        </form>
                        <a href="{{ route('events.participants.template') }}" class="btn btn-outline-secondary btn-sm w-100">
                            <i class="mdi mdi-download me-1"></i> Unduh Template Excel
                        </a>
                    </div>
                    @endrole

                    <div class="border-top pt-3 mt-3">
                        <h4>Ringkasan Event:</h4>
                        <ul class="list-unstyled small">
                            <li class="mb-2"><strong>PIC:</strong><br>{{ $event->user->name ?? '-' }}</li>
                            <li class="mb-2">
                                <strong>Mulai:</strong><br>{{ \Carbon\Carbon::parse($event->start_date)->format('d M Y, H:i') }}
                            </li>
                            <li class="mb-2">
                                <strong>Selesai:</strong><br>{{ \Carbon\Carbon::parse($event->end_date)->format('d M Y, H:i') }}
                            </li>
                            <li class="mb-2">
                                <strong>Jumlah Ruangan:</strong><br>{{ $event->room_bookings->count() }} ruangan digunakan
                            </li>
                        </ul>
                        @role('admin')
                        <a href="{{ route('events.show', $event->id) }}?edit=true"
                            class="btn btn-outline-warning btn-sm w-100 mt-2">
                            <i class="mdi mdi-pencil me-1"></i> Edit Informasi Event
                        </a>
                        @endrole
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

{{-- ===== SECTION: Riwayat Kehadiran ===== --}}
<div class="row mb-4">
    <div class="col-md-12 grid-margin stretch-card">
        <div class="card card-rounded shadow-sm">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
                    <h3 class="card-title mb-0">Riwayat Kehadiran</h3>
                    <a id="export-presensi-btn" href="{{ route('events.export_attendance', $event->id) }}"
                        class="btn btn-success btn-sm text-white" title="Export Presensi ke Excel">
                        <i class="mdi mdi-microsoft-excel me-1"></i>Export Presensi
                    </a>
                </div>

                <form method="GET" action="{{ route('events.show', $event->id) }}" class="row g-2 align-items-end mb-3">
                    <div class="col-auto">
                        <label class="form-label mb-0 small text-muted">Dari Tanggal</label>
                        <input type="date" name="date_from" id="filter-date-from" value="{{ $dateFrom }}" class="form-control form-control-sm">
                    </div>
                    <div class="col-auto">
                        <label class="form-label mb-0 small text-muted">Sampai Tanggal</label>
                        <input type="date" name="date_to" id="filter-date-to" value="{{ $dateTo }}" class="form-control form-control-sm">
                    </div>
                    <div class="col-auto">
                        <button type="submit" class="btn btn-outline-primary btn-sm">
                            <i class="mdi mdi-filter-variant me-1"></i>Filter
                        </button>
                        @if($dateFrom || $dateTo)
                        <a href="{{ route('events.show', $event->id) }}" class="btn btn-outline-secondary btn-sm">Reset</a>
                        @endif
                    </div>
                </form>

                @if($attendances->isEmpty())
                    <p class="text-muted text-center py-3 mb-0">
                        <i class="mdi mdi-calendar-remove me-1"></i>
                        @if($dateFrom || $dateTo)
                            Tidak ada data kehadiran pada rentang waktu yang dipilih.
                        @else
                            Belum ada data kehadiran untuk acara ini.
                        @endif
                    </p>
                @else
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Nama Peserta</th>
                                    <th>Ruangan</th>
                                    <th>Waktu Presensi</th>
                                    <th class="text-center">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($attendances as $i => $attendance)
                                <tr>
                                    <td>{{ $i + 1 }}</td>
                                    <td><strong>{{ $attendance->user ? $attendance->user->name : '-' }}</strong></td>
                                    <td>{{ $attendance->room ? $attendance->room->name : '-' }}</td>
                                    <td>{{ \Carbon\Carbon::parse($attendance->created_at)->format('d M Y, H:i') }}</td>
                                    <td class="text-center">
                                        @if($attendance->status == 'on_time')
                                            <label class="badge badge-success mb-0">Hadir (On Time)</label>
                                        @elseif($attendance->status == 'late')
                                            <label class="badge badge-warning mb-0">Terlambat</label>
                                        @else
                                            <label class="badge badge-danger mb-0">Pulang Cepat</label>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Modal QR (Reused for all participants) -->
<div class="modal fade" id="qrModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">QR Presensi: {{ $event->name }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center p-5">
                <p class="mb-3 text-muted">Arahkan scanner ke kode di bawah ini.</p>
                <div id="qrcode-container" class="d-flex justify-content-center mb-3">
                    <div id="qrcode" class="border p-3 rounded shadow-sm bg-white"></div>
                </div>
                <h4 class="fw-bold mb-1" id="modalUserName">User Name</h4>
                <p class="text-info small">Event ID: {{ $event->id }}</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary text-white" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

{{-- Local QR generation via QRCodeJS --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var qrcode = new QRCode(document.getElementById("qrcode"), {
            width: 250,
            height: 250
        });

        const showBtn = document.querySelectorAll('.show-qr-btn');
        showBtn.forEach(btn => {
            btn.addEventListener('click', function() {
                const userId = this.getAttribute('data-user-id');
                const userName = this.getAttribute('data-user-name');
                const eventId = '{{ $event->id }}';

                document.getElementById('modalUserName').innerText = userName;

                qrcode.clear();
                qrcode.makeCode(userId + '-' + eventId);
            });
        });

        const exportBtn = document.getElementById('export-presensi-btn');
        const dateFromInput = document.getElementById('filter-date-from');
        const dateToInput = document.getElementById('filter-date-to');
        const exportBaseUrl = exportBtn.getAttribute('href');

        function updateExportLink() {
            const params = new URLSearchParams();
            if (dateFromInput.value) params.set('date_from', dateFromInput.value);
            if (dateToInput.value) params.set('date_to', dateToInput.value);
            const query = params.toString();
            exportBtn.setAttribute('href', exportBaseUrl + (query ? '?' + query : ''));
        }

        dateFromInput.addEventListener('change', updateExportLink);
        dateToInput.addEventListener('change', updateExportLink);
        updateExportLink();
    });
</script>
@endsection
