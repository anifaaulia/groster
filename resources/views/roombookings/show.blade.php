@extends('partials.main.index')
@section('content')
    @php
        $isPic = auth()->user()->hasRole('pic');
        $disabledAttr = $isPic ? 'disabled' : '';
    @endphp

    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h3 class="card-title">Detail / Edit Peminjaman Ruangan</h3>
                    <p class="card-description"> 
                        @if($isPic)
                            Rincian pengajuan peminjaman ruangan Anda.
                        @else
                            Ubah data peminjaman ruangan atau lakukan verifikasi pada form di bawah ini.
                        @endif
                    </p>
                    <form action="{{ route('roombookings.update', $roomBooking->id) }}" method="POST" class="forms-sample">
                        @method('PUT')
                        @csrf
                        <div class="form-group">
                            <label for="room_id">Ruangan</label>
                            <select name="room_id" class="form-control" id="room_id" required {{ $disabledAttr }}>
                                <option value="">-- Pilih Ruangan --</option>
                                @foreach($rooms as $room)
                                    <option value="{{ $room->id }}" {{ $roomBooking->room_id == $room->id ? 'selected' : '' }}>
                                        {{ $room->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="event_id">Kegiatan Acara (Opsional)</label>
                            <select name="event_id" class="form-control" id="event_id" {{ $disabledAttr }}>
                                <option value="">-- Pilih Acara (Tanpa Acara khusus) --</option>
                                @foreach($events as $event)
                                    <option value="{{ $event->id }}" {{ $roomBooking->event_id == $event->id ? 'selected' : '' }}>
                                        {{ $event->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        
                        @if($isPic)
                            <div class="form-group">
                                <label for="user_name">Peminjam (PIC)</label>
                                <input type="text" class="form-control" id="user_name" value="{{ $roomBooking->user ? $roomBooking->user->name : '-' }}" readonly disabled>
                            </div>
                        @else
                            <div class="form-group">
                                <label for="user_id">Peminjam</label>
                                <select name="user_id" class="form-control" id="user_id" required>
                                    <option value="">-- Pilih Peminjam --</option>
                                    @foreach($users as $user)
                                        <option value="{{ $user->id }}" {{ $roomBooking->user_id == $user->id ? 'selected' : '' }}>
                                            {{ $user->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        @endif

                        <div class="form-group">
                            <label for="date">Tanggal Mulai Peminjaman</label>
                            <input type="date" name="date" class="form-control" id="date" value="{{ date('Y-m-d', strtotime($roomBooking->date)) }}"
                                required {{ $disabledAttr }}>
                        </div>
                        <div class="form-group">
                            <label for="end_date">Tanggal Selesai Peminjaman</label>
                            <input type="date" name="end_date" class="form-control" id="end_date"
                                value="{{ $roomBooking->end_date ? date('Y-m-d', strtotime($roomBooking->end_date)) : '' }}"
                                {{ $disabledAttr }}>
                            <small class="text-muted">Kosongkan jika hanya satu hari.</small>
                        </div>
                        <div class="form-group">
                            <label for="start_time">Waktu Mulai (HH:MM)</label>
                            <input type="time" name="start_time" class="form-control" id="start_time"
                                value="{{ date('H:i', strtotime($roomBooking->start_time)) }}" required {{ $disabledAttr }}>
                        </div>
                        <div class="form-group">
                            <label for="end_time">Waktu Selesai (HH:MM)</label>
                            <input type="time" name="end_time" class="form-control" id="end_time"
                                value="{{ date('H:i', strtotime($roomBooking->end_time)) }}" required {{ $disabledAttr }}>
                        </div>
                        <div class="form-group">
                            <label for="status_display">Status Persetujuan</label>
                            @if($roomBooking->is_active)
                                <div class="mt-1">
                                    <span class="badge badge-success p-2">Disetujui</span>
                                </div>
                            @else
                                <div class="mt-1">
                                    <span class="badge badge-warning p-2 text-dark">Menunggu Verifikasi / Ditolak</span>
                                </div>
                            @endif
                        </div>

                        @role('admin')
                            <div class="form-group">
                                <label for="is_active">Ubah Status Manual</label>
                                <select name="is_active" class="form-control" id="is_active" required>
                                    <option value="0" {{ $roomBooking->is_active == false ? 'selected' : '' }}>Menunggu Verifikasi / Nonaktif</option>
                                    <option value="1" {{ $roomBooking->is_active == true ? 'selected' : '' }}>Disetujui</option>
                                </select>
                            </div>
                            
                            <button type="submit" class="btn btn-primary me-2 text-white">Simpan Perubahan</button>
                            
                            @if(!$roomBooking->is_active)
                                <button type="submit" form="approve-form" class="btn btn-success me-2 text-white"><i class="mdi mdi-check"></i> Setujui Pengajuan</button>
                                <button type="submit" form="reject-form" class="btn btn-warning me-2 text-white"><i class="mdi mdi-close"></i> Tolak Pengajuan</button>
                            @endif
                        @endrole

                        <a href="{{ route('roombookings.index') }}" class="btn btn-light">Kembali</a>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @role('admin')
        @if(!$roomBooking->is_active)
            <form id="approve-form" action="{{ route('roombookings.approve', $roomBooking->id) }}" method="POST" class="d-none">
                @csrf
            </form>
            <form id="reject-form" action="{{ route('roombookings.reject', $roomBooking->id) }}" method="POST" class="d-none">
                @csrf
            </form>
        @endif
    @endrole
@endsection