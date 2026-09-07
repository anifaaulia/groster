@extends('partials.main.index')
@section('content')
    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h3 class="card-title">Edit Kehadiran</h3>
                    <p class="card-description"> Ubah data kehadiran pada form di bawah ini. </p>
                    <form action="{{ route('attendances.update', $attendance->id) }}" method="POST" class="forms-sample">
                        @method('PUT')
                        @csrf
                        <div class="form-group">
                            <label for="room_id">Ruangan</label>
                            <select name="room_id" class="form-control" id="room_id" required>
                                <option value="">-- Pilih Ruangan --</option>
                                @foreach($rooms as $room)
                                    <option value="{{ $room->id }}" {{ $attendance->room_id == $room->id ? 'selected' : '' }}>
                                        {{ $room->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="user_id">Nama Peserta</label>
                            <select name="user_id" class="form-control" id="user_id" required>
                                <option value="">-- Pilih Peserta --</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}" {{ $attendance->user_id == $user->id ? 'selected' : '' }}>
                                        {{ $user->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="status">Status Kehadiran</label>
                            <select name="status" class="form-control" id="status" required>
                                <option value="">-- Pilih Status --</option>
                                <option value="on_time" {{ $attendance->status == 'on_time' ? 'selected' : '' }}>Tepat Waktu
                                    (On Time)</option>
                                <option value="late" {{ $attendance->status == 'late' ? 'selected' : '' }}>Terlambat (Late)
                                </option>
                                <option value="left_early" {{ $attendance->status == 'left_early' ? 'selected' : '' }}>Pulang
                                    Cepat (Left Early)</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary me-2 text-white">Simpan Perubahan</button>
                        <a href="{{ route('attendances.index') }}" class="btn btn-light">Batal</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection