@extends('partials.main.index')
@section('content')
    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h3 class="card-title">Tambah Acara</h3>
                    <p class="card-description">Masukkan data acara baru pada form di bawah ini.</p>
                    @role('pic')
                    <div class="alert alert-info small mb-3">
                        <i class="mdi mdi-information me-1"></i>
                        Acara yang Anda buat akan menunggu persetujuan admin sebelum aktif.
                    </div>
                    @endrole
                    <form action="{{ route('events.store') }}" method="POST" class="forms-sample">
                        @csrf
                        <div class="form-group">
                            <label for="name">Nama Acara</label>
                            <input type="text" name="name" class="form-control" id="name"
                                placeholder="Masukan nama acara" required value="{{ old('name') }}">
                        </div>
                        @role('pic')
                            <div class="form-group">
                                <label>Penanggung Jawab (PIC)</label>
                                <input type="text" class="form-control" value="{{ auth()->user()->name }}" readonly disabled>
                                <input type="hidden" name="user_id" value="{{ auth()->id() }}">
                            </div>
                        @else
                            <div class="form-group">
                                <label for="user_id">Penanggung Jawab (Staf)</label>
                                <select name="user_id" class="form-control" id="user_id" required>
                                    <option value="">-- Pilih Staf --</option>
                                    @foreach($users as $user)
                                        <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>
                                            {{ $user->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        @endrole
                        <div class="form-group">
                            <label for="start_date">Waktu Mulai</label>
                            <input type="datetime-local" name="start_date" class="form-control" id="start_date"
                                required value="{{ old('start_date') }}">
                        </div>
                        <div class="form-group">
                            <label for="end_date">Waktu Selesai</label>
                            <input type="datetime-local" name="end_date" class="form-control" id="end_date"
                                required value="{{ old('end_date') }}">
                        </div>
                        <button type="submit" class="btn btn-primary me-2 text-white">Simpan</button>
                        <a href="{{ route('events.index') }}" class="btn btn-light">Batal</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
