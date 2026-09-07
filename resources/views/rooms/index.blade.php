@extends('partials.main.index')
@section('content')
    <div class="row">
        <div class="col-lg-12">
            <h3 class="card-title">Status Ketersediaan Ruangan</h3>
            <p class="card-description mb-4"> Halaman ini menampilkan informasi real-time mengenai penggunaan ruangan. </p>
            
            @role('admin')
            <div class="d-flex justify-content-end mb-3">
                <a href="{{ route('rooms.create') }}" class="btn btn-primary btn-sm text-white" type="button">
                    <i class="mdi mdi-plus"></i> Tambah Ruangan
                </a>
            </div>
            @endrole

            @role('admin')
            {{-- Admin View: Table --}}
            <div class="card card-rounded">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Ruangan</th>
                                    <th>Kapasitas</th>
                                    <th>Status</th>
                                    <th>Kegiatan Aktif</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($rooms as $room)
                                    <tr>
                                        <td>{{ $room->name }}</td>
                                        <td>{{ $room->capacity }} orang</td>
                                        <td>
                                            @if($room->status == 'occupied')
                                                <label class="badge badge-danger">DIGUNAKAN</label>
                                            @elseif($room->status == 'booked')
                                                <label class="badge badge-warning">DIBOOKING</label>
                                            @else
                                                <label class="badge badge-success">TERSEDIA</label>
                                            @endif
                                        </td>
                                        <td>
                                            @php
                                                $activeBooking = $room->room_booking()
                                                    ->where('is_active', 1)
                                                    ->where('start_time', '<=', now())
                                                    ->where('end_time', '>=', now())
                                                    ->latest()
                                                    ->first();
                                            @endphp
                                            @if($activeBooking && $activeBooking->event)
                                                <strong>{{ $activeBooking->event->name }}</strong><br>
                                                <small>{{ $activeBooking->user->name }} ({{ $activeBooking->start_time->format('H:i') }} - {{ $activeBooking->end_time->format('H:i') }})</small>
                                            @else
                                                <span class="text-muted small"><em>- Tidak ada kegiatan -</em></span>
                                            @endif
                                        </td>
                                        <td>
                                            <button type="button" class="btn btn-info btn-sm text-white" data-bs-toggle="modal" data-bs-target="#detailModal{{ $room->id }}"><i class="mdi mdi-information-variant"></i></button>
                                            <a href="{{ route('rooms.show', $room->id) }}" class="btn btn-warning btn-sm text-white" aria-label="Edit Ruangan"><i class="mdi mdi-pencil"></i></a>
                                            <form action="{{ route('rooms.destroy', $room->id) }}" method="POST" class="d-inline">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm text-white" onclick="return confirm('Yakin?')"><i class="mdi mdi-delete"></i></button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            @else
            {{-- PIC & Participant View: Cards --}}
            <div class="row">
                @foreach($rooms as $room)
                <div class="col-md-4 grid-margin stretch-card">
                    <div class="card card-rounded shadow-sm">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <div>
                                    <h4 class="card-title card-title-dash mb-1">{{ $room->name }}</h4>
                                    <p class="text-muted small"><i class="mdi mdi-account-group me-1"></i> Kapasitas: {{ $room->capacity }} orang</p>
                                </div>
                                @if($room->status == 'occupied')
                                    <span class="badge badge-danger">DIGUNAKAN</span>
                                @elseif($room->status == 'booked')
                                    <span class="badge badge-warning">DIBOOKING</span>
                                @else
                                    <span class="badge badge-success">TERSEDIA</span>
                                @endif
                            </div>

                            <div class="activity-info bg-light p-3 rounded mb-3">
                                <h5 class="text-uppercase small fw-bold text-muted mb-2">Kegiatan Saat Ini:</h5>
                                @php
                                    $activeBooking = $room->room_booking()
                                        ->where('is_active', 1)
                                        ->where('start_time', '<=', now())
                                        ->where('end_time', '>=', now())
                                        ->latest()
                                        ->first();
                                @endphp
                                @if($activeBooking && $activeBooking->event)
                                    <p class="mb-1 fw-bold text-dark">{{ $activeBooking->event->name }}</p>
                                    <p class="mb-0 small text-muted"><i class="mdi mdi-clock-outline me-1"></i>{{ $activeBooking->start_time->format('H:i') }} - {{ $activeBooking->end_time->format('H:i') }}</p>
                                    <p class="mb-0 small text-muted"><i class="mdi mdi-account-circle me-1"></i>PIC: {{ $activeBooking->user->name }}</p>
                                @else
                                    <p class="mb-0 small text-muted"><em>- Tidak ada kegiatan aktif -</em></p>
                                @endif
                            </div>

                            <button type="button" class="btn btn-outline-info btn-sm w-100" data-bs-toggle="modal" data-bs-target="#detailModal{{ $room->id }}">
                                <i class="mdi mdi-information-variant me-1"></i> Lihat Fasilitas Ruangan
                            </button>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            @endrole
        </div>
    </div>

    {{-- Place Modals outside of the loop for better rendering --}}
    @foreach($rooms as $room)
        @include('rooms.partials.modal_facilities', ['room' => $room])
    @endforeach

@endsection