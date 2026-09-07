@extends('partials.main.index')

@section('content')
<div class="row">
    <div class="col-md-6 grid-margin stretch-card mx-auto">
        <div class="card card-rounded shadow-sm">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h3 class="card-title mb-0">Scan Absensi Event</h3>
                    <a href="{{ route('dashboard.index') }}" class="btn btn-outline-secondary btn-sm">Kembali</a>
                </div>
                <p class="text-small text-muted mb-4">Arahkan kamera ke QR Code peserta untuk mencatat kehadiran di <strong>{{ $event->name }}</strong>.</p>
                
                <div id="reader-wrapper" class="bg-light p-2 rounded mb-3" style="min-height: 300px; display: flex; align-items: center; justify-content: center;">
                    <div id="qr-reader" style="width: 100%; border: none;"></div>
                </div>
                
                <div id="scan-feedback" class="alert alert-info d-none" role="alert">
                    <span id="feedback-msg">Memproses data...</span>
                </div>

                <div class="mt-4 border-top pt-3">
                    <h4>Detail Sesi:</h4>
                    @if($currentBooking)
                        <div class="d-flex justify-content-between small">
                            <span class="text-muted">Ruangan:</span>
                            <span class="fw-bold">{{ $currentBooking->room->name ?? 'N/A' }}</span>
                        </div>
                        <div class="d-flex justify-content-between small">
                            <span class="text-muted">Waktu:</span>
                            <span class="fw-bold">{{ $currentBooking->start_time->format('H:i') }} - {{ $currentBooking->end_time->format('H:i') }}</span>
                        </div>
                    @else
                        <p class="text-danger small mb-0">Tidak ada sesi yang sedang berlangsung untuk event ini saat ini.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

{{-- QR Code Sound Filter (Optional but nice) --}}
<audio id="beep-sound" src="https://assets.mixkit.co/active_storage/sfx/2568/2568-preview.mp3" preload="auto"></audio>

<script src="https://unpkg.com/html5-qrcode"></script>
<script>
    const feedbackEl = document.getElementById('scan-feedback');
    const feedbackMsg = document.getElementById('feedback-msg');
    const beep = document.getElementById('beep-sound');

    function showFeedback(msg, type = 'info') {
        feedbackEl.classList.remove('d-none', 'alert-success', 'alert-danger', 'alert-info');
        feedbackEl.classList.add(`alert-${type}`);
        feedbackMsg.innerText = msg;
    }

    function onScanSuccess(decodedText, decodedResult) {
        // Play beep sound
        beep.play();
        
        console.log(`Scan Result: ${decodedText}`);
        
        // Expected format from Participant Dashboard: user_id-event_id
        let parts = decodedText.split('-');
        let userId = parts[0];
        let scannedEventId = parts[1];

        // Validation
        if (!userId || isNaN(userId) || !scannedEventId) {
            showFeedback('Format QR Code tidak dikenali.', 'danger');
            return;
        }

        // Security check: ensure participant scans the QR for THIS specific event
        if (scannedEventId !== '{{ $event->id }}') {
            showFeedback('QR Code Salah! Ini adalah event yang berbeda.', 'danger');
            return;
        }

        @if(!$currentBooking)
        showFeedback('Tidak ada sesi yang sedang berlangsung untuk event ini.', 'danger');
        html5QrcodeScanner.pause();
        return;
        @endif

        // Disable scanner temporarily to prevent duplicate scans
        html5QrcodeScanner.pause();
        showFeedback('Mengirim data absensi...', 'info');

        fetch('{{ route('attendances.checkin') }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                user_id: userId,
                event_id: '{{ $event->id }}',
                room_booking_id: '{{ $currentBooking->id ?? "" }}'
            })
        })
        .then(response => response.json())
        .then(data => {
            showFeedback(`Berhasil: ${data.message}`, 'success');
            setTimeout(() => {
                feedbackEl.classList.add('d-none');
                html5QrcodeScanner.resume();
            }, 3000); // Resume scanning after 3 seconds
        })
        .catch(error => {
            console.error('Error:', error);
            showFeedback('Gagal mencatat kehadiran.', 'danger');
            setTimeout(() => html5QrcodeScanner.resume(), 2000);
        });
    }

    let html5QrcodeScanner = new Html5QrcodeScanner(
        "qr-reader", { 
            fps: 10, 
            qrbox: {width: 250, height: 250},
            aspectRatio: 1.0
        }, 
        /* verbose= */ false
    );
    
    html5QrcodeScanner.render(onScanSuccess);
</script>
@endsection
