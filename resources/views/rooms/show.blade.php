@extends('partials.main.index')
@section('content')
    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h3 class="card-title">Edit Ruangan</h3>
                    <p class="card-description"> Ubah data ruangan pada form di bawah ini. </p>
                    <form action="{{ route('rooms.update', $room->id) }}" method="POST" class="forms-sample">
                        @method('PUT')
                        @csrf
                        <div class="form-group">
                            <label for="name">Nama Ruangan</label>
                            <input type="text" name="name" class="form-control" id="name" value="{{ $room->name }}"
                                required>
                        </div>
                        <div class="form-group">
                            <label for="institution_id">Institusi</label>
                            <select name="institution_id" class="form-control" id="institution_id" required>
                                <option value="">-- Pilih Institusi --</option>
                                @foreach($institutions as $inst)
                                    <option value="{{ $inst->id }}" {{ $room->institution_id == $inst->id ? 'selected' : '' }}>
                                        {{ $inst->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="capacity">Kapasitas (Orang)</label>
                            <input type="number" name="capacity" class="form-control" id="capacity"
                                value="{{ $room->capacity }}" required>
                        </div>
                        <div class="form-group">
                            <label>Fasilitas Utama</label><br>
                            <div class="row">
                                @php
                                    $room_facilities = $room->facilities->pluck('id')->toArray();
                                @endphp
                                @foreach($facilities as $facility)
                                    <div class="col-md-3 mb-2">
                                        <div class="form-check d-flex align-items-center">
                                            <label class="form-check-label me-2">
                                                <input type="checkbox" name="facilities[]" class="form-check-input"
                                                    value="{{ $facility->id }}" {{ in_array($facility->id, $room_facilities) ? 'checked' : '' }}> {{ $facility->name }}
                                            </label>
                                            @php
                                                $qty = 1;
                                                if (in_array($facility->id, $room_facilities)) {
                                                    $pivotFacility = $room->facilities->where('id', $facility->id)->first();
                                                    if ($pivotFacility) {
                                                        $qty = $pivotFacility->pivot->quantity;
                                                    }
                                                }
                                            @endphp
                                            <input type="number" name="quantities[{{ $facility->id }}]" class="form-control form-control-sm"
                                                value="{{ $qty }}" min="1" style="width: 70px;">
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="status">Status Ruangan</label>
                            <select name="status" class="form-control" id="status" required>
                                <option value="">-- Pilih Status --</option>
                                <option value="available" {{ $room->status == 'available' ? 'selected' : '' }}>Tersedia
                                </option>
                                <option value="booked" {{ $room->status == 'booked' ? 'selected' : '' }}>Dipesan</option>
                                <option value="occupied" {{ $room->status == 'occupied' ? 'selected' : '' }}>Terisi
                                    (Digunakan)</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary me-2 text-white">Simpan Perubahan</button>
                        <a href="{{ route('rooms.index') }}" class="btn btn-light">Batal</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection