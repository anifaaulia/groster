@extends('partials.main.index')
@section('content')
<div class="row">
    <div class="col-md-8 grid-margin stretch-card">
        <div class="card card-rounded">
            <div class="card-body">
                <h3 class="card-title">Profil Saya</h3>
                <div class="row">
                    <div class="col-sm-8">
                        <p class="mb-2 text-muted">Nama Lengkap</p>
                        <h4 class="fw-bold mb-3">{{ $user->name }}</h4>

                        <p class="mb-2 text-muted">Email</p>
                        <h4 class="fw-bold mb-3">{{ $user->email }}</h4>

                        <p class="mb-2 text-muted">Institusi</p>
                        <h4 class="fw-bold mb-3">{{ $user->institution ? $user->institution->name : '-' }}</h4>
                    </div>
                    <div class="col-sm-4 text-center d-flex flex-column align-items-center justify-content-center">
                        <p class="text-muted small mb-3">QR Code Presensi</p>
                        <button type="button" class="btn btn-primary btn-lg text-white shadow-sm" data-bs-toggle="modal" data-bs-target="#profileQRModal">
                            <i class="mdi mdi-qrcode mdi-24px d-block mb-1"></i>
                            Tampilkan QR
                        </button>
                        <p class="mt-3 small text-info"><i class="mdi mdi-information-outline"></i> Klik untuk scan absensi</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal QR Profile -->
<div class="modal fade" id="profileQRModal" tabindex="-1" aria-labelledby="profileQRModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-primary text-white border-0">
                <h5 class="modal-title fw-bold" id="profileQRModalLabel">
                    <i class="mdi mdi-qrcode-scan me-2"></i>QR Code Absensi
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center p-5">
                <div class="qr-container mb-4">
                    <img src="https://quickchart.io/qr?text={{ auth()->id() }}-profile&size=250&margin=2" 
                         alt="QR Code" class="img-fluid border p-3 rounded-3 bg-white shadow-sm" style="max-width: 250px;">
                </div>
                <h4 class="fw-bold text-dark mb-1">{{ auth()->user()->name }}</h4>
                <p class="text-muted mb-0">{{ auth()->user()->email }}</p>
                @if(auth()->user()->institution)
                    <p class="badge badge-info mt-2 text-white">{{ auth()->user()->institution->name }}</p>
                @endif
                <hr>
                <p class="text-secondary small">
                    <i class="mdi mdi-information-variant me-1"></i>
                    Tunjukkan kode ini kepada petugas/PIC di lokasi untuk mencatat kehadiran Anda.
                </p>
            </div>
            <div class="modal-footer border-0">
                <button type="button" class="btn btn-secondary text-white w-100" data-bs-dismiss="modal">
                    <i class="mdi mdi-close me-1"></i> Tutup
                </button>
            </div>
        </div>
    </div>
</div>
@endsection
