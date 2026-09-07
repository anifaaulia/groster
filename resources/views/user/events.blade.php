@extends('partials.main.index')
@section('content')
    <div class="row">
        <div class="col-md-12 grid-margin">
            <div class="card card-rounded shadow-sm">
                <div class="card-body">
                    <h3 class="card-title">Acara Saya</h3>
                    <p class="card-description">Daftar agenda acara yang Anda ikuti beserta status kehadiran.</p>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Acara</th>
                                    <th>Waktu Mulai</th>
                                    <th>Waktu Selesai</th>
                                    <th class="text-center">Status Kehadiran</th>
                                    <th class="text-center">QR Code Saya (Presensi)</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($events as $index => $event)
                                    @php
                                        $myAttendance = $event->attendances->first();
                                    @endphp
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td><strong>{{ $event->name }}</strong></td>
                                        <td>{{ \Carbon\Carbon::parse($event->start_date)->format('d M Y, H:i') }}</td>
                                        <td>{{ \Carbon\Carbon::parse($event->end_date)->format('d M Y, H:i') }}</td>
                                        <td class="text-center">
                                            @if($myAttendance)
                                                @if($myAttendance->status == 'on_time')
                                                    <span class="badge badge-success">Hadir (Tepat Waktu)</span>
                                                @elseif($myAttendance->status == 'late')
                                                    <span class="badge badge-warning">Terlambat</span>
                                                @elseif($myAttendance->status == 'left_early')
                                                    <span class="badge badge-danger">Pulang Cepat</span>
                                                @endif
                                            @else
                                                <span class="text-muted small italic">Belum Presensi</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <button type="button" class="btn btn-primary btn-sm text-white show-qr-btn"
                                                data-user-id="{{ auth()->id() }}"
                                                data-event-id="{{ $event->id }}"
                                                data-event-name="{{ $event->name }}"
                                                data-bs-toggle="modal" data-bs-target="#qrModal">
                                                <i class="mdi mdi-qrcode me-1"></i> Tampilkan QR
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center italic text-muted p-4">Belum ada acara yang diikuti.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Single Modal QR (Reused for all events) -->
    <div class="modal fade" id="qrModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTitle">QR Presensi</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center p-5">
                    <p class="mb-4 text-muted border-bottom pb-2">Tunjukkan kode ini ke petugas di lokasi acara.</p>
                    <div id="qrcode-container" class="d-flex justify-content-center mb-3">
                        <div id="qrcode" class="border p-3 rounded shadow-sm bg-white"></div>
                    </div>
                    <h4 class="fw-bold mb-1">{{ auth()->user()->name }}</h4>
                    <p class="text-primary small" id="modalEventId"></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary text-white" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    {{-- Use QRCodeJS for offline/local QR generation --}}
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
                    const eventId = this.getAttribute('data-event-id');
                    const eventName = this.getAttribute('data-event-name');

                    const qrText = userId + '-' + eventId;

                    document.getElementById('modalTitle').innerText = 'QR Presensi: ' + eventName;
                    document.getElementById('modalEventId').innerText = 'Event ID: ' + eventId;

                    qrcode.clear();
                    qrcode.makeCode(qrText);
                });
            });
        });
    </script>
@endsection
