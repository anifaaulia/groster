@extends('partials.main.index')
@section('content')
    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h3 class="card-title">Tambah Peminjaman Ruangan</h3>
                    <p class="card-description"> Masukkan data peminjaman ruangan baru pada form di bawah ini. </p>
                    <form action="{{ route('roombookings.store') }}" method="POST" class="forms-sample">
                        @csrf
                        <div class="form-group">
                            <label for="capacity">Kapasitas yang diinginkan</label>
                            <input type="number" name="capacity" class="form-control" id="capacity" min="1" value="{{ old('capacity') }}" required>
                        </div>

                        <div class="form-group">
                            <label for="event_id">Kegiatan Acara (Opsional)</label>
                            <select name="event_id" class="form-control" id="event_id">
                                <option value="">-- Pilih Acara --</option>
                                @foreach($events as $event)
                                    <option value="{{ $event->id }}" {{ (old('event_id', request('event_id')) == $event->id) ? 'selected' : '' }}>{{ $event->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        @role('pic')
                            <div class="form-group">
                                <label for="user_name_display">Peminjam (PIC)</label>
                                <input type="text" class="form-control" id="user_name_display" value="{{ auth()->user()->name }}" readonly disabled>
                                <input type="hidden" name="user_id" value="{{ auth()->id() }}">
                            </div>
                        @else
                            <div class="form-group">
                                <label for="user_id">Peminjam</label>
                                <select name="user_id" class="form-control" id="user_id" required>
                                    <option value="">-- Pilih Peminjam --</option>
                                    @foreach($users as $user)
                                        <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        @endrole

                        <div class="form-group">
                            <label for="date">Tanggal Mulai Peminjaman</label>
                            <input type="date" name="date" class="form-control" id="date" value="{{ old('date') }}" required>
                        </div>
                        <div class="form-group">
                            <label for="end_date">Tanggal Selesai Peminjaman</label>
                            <input type="date" name="end_date" class="form-control" id="end_date" value="{{ old('end_date') }}">
                            <small class="text-muted">Kosongkan jika hanya satu hari.</small>
                        </div>
                        <div class="form-group">
                            <label for="start_time">Waktu Mulai</label>
                            <input type="time" name="start_time" class="form-control" id="start_time" value="{{ old('start_time') }}" required>
                        </div>
                        <div class="form-group">
                            <label for="end_time">Waktu Selesai</label>
                            <input type="time" name="end_time" class="form-control" id="end_time" value="{{ old('end_time') }}" required>
                        </div>


                        @role('pic')
                            <input type="hidden" name="is_active" value="0">
                        @else
                            <div class="form-group">
                                <label for="is_active">Status Peminjaman</label>
                                <select name="is_active" class="form-control" id="is_active" required>
                                    <option value="">-- Pilih Status --</option>
                                    <option value="0" {{ old('is_active') === '0' ? 'selected' : '' }}>Menunggu</option>
                                    <option value="1" {{ old('is_active') === '1' ? 'selected' : '' }}>Disetujui</option>
                                </select>
                            </div>
                        @endrole
                        <div class="form-group mt-4 pt-3 border-top">
                            <label>Ruangan</label>
                            <input type="hidden" name="room_id" id="room_id" value="{{ old('room_id') }}" required>

                            <div id="room-status-message" class="text-muted small mb-2">
                                Lengkapi kapasitas, tanggal, dan waktu untuk melihat rekomendasi ruangan.
                            </div>

                            <div id="room-cards" class="row g-2"></div>
                        </div>

                        <button type="submit" id="btn-submit" class="btn btn-primary me-2 text-white" disabled>Simpan</button>
                        <a href="{{ route('roombookings.index') }}" class="btn btn-light">Batal</a>

                        <style>
                            .room-card {
                                cursor: pointer;
                                border: 2px solid #e0e0e0;
                                border-radius: 10px;
                                padding: 10px 12px;
                                transition: border-color .15s ease, background-color .15s ease;
                                height: 100%;
                            }
                            .room-card:hover {
                                border-color: #94a3f8;
                            }
                            .room-card.selected {
                                border-color: #6366f1;
                                background-color: #eef0ff;
                            }
                            .room-card .room-card-name {
                                font-weight: 600;
                                font-size: .9rem;
                            }
                            .room-card .room-card-capacity {
                                font-size: .8rem;
                                color: #6c757d;
                            }
                        </style>

                        <script>
                        document.addEventListener('DOMContentLoaded', function () {
                            const dateEl = document.getElementById('date');
                            const startEl = document.getElementById('start_time');
                            const endEl = document.getElementById('end_time');
                            const capacityEl = document.getElementById('capacity');
                            const roomIdEl = document.getElementById('room_id');
                            const submitBtn = document.getElementById('btn-submit');
                            const statusEl = document.getElementById('room-status-message');
                            const cardsEl = document.getElementById('room-cards');
                            const oldRoomId = "{{ old('room_id') }}";

                            let debounceTimer = null;

                            function buildUnavailableMessage(suggestion, requestedCapacity) {
                                if (!suggestion) {
                                    return 'Tidak ada ruangan yang memenuhi kriteria kapasitas dan waktu tersebut.';
                                }

                                if (!suggestion.fits_capacity) {
                                    return `Tidak ada ruangan dengan kapasitas ${requestedCapacity} orang. Kapasitas maksimal ruangan yang tersedia saat ini adalah ${suggestion.max_capacity} orang.`;
                                }

                                if (suggestion.next_available_time) {
                                    return `Ruangan dengan kapasitas tersebut sudah dipakai pada jam yang diminta. Ruangan akan tersedia kembali mulai pukul ${suggestion.next_available_time}.`;
                                }

                                return 'Tidak ada ruangan yang memenuhi kriteria kapasitas dan waktu tersebut.';
                            }

                            function resetSelection() {
                                roomIdEl.value = '';
                                submitBtn.disabled = true;
                                cardsEl.innerHTML = '';
                            }

                            function selectRoom(card, roomId) {
                                cardsEl.querySelectorAll('.room-card').forEach(c => c.classList.remove('selected'));
                                card.classList.add('selected');
                                roomIdEl.value = roomId;
                                submitBtn.disabled = false;
                            }

                            function renderRooms(rooms) {
                                cardsEl.innerHTML = '';
                                rooms.forEach(room => {
                                    const card = document.createElement('div');
                                    card.className = 'col-6 col-md-3 col-lg-2';
                                    card.innerHTML = `
                                        <div class="room-card" data-room-id="${room.id}">
                                            <div class="room-card-name">${room.name}</div>
                                            <div class="room-card-capacity">Kapasitas ${room.capacity}</div>
                                        </div>
                                    `;
                                    const cardInner = card.querySelector('.room-card');
                                    cardInner.addEventListener('click', () => selectRoom(cardInner, room.id));
                                    cardsEl.appendChild(card);

                                    if (oldRoomId && String(room.id) === String(oldRoomId)) {
                                        selectRoom(cardInner, room.id);
                                    }
                                });
                            }

                            function updateRooms() {
                                const capacity = capacityEl.value;
                                const date = dateEl.value;
                                const startTime = startEl.value;
                                const endTime = endEl.value;

                                if (!capacity || !date || !startTime || !endTime) {
                                    statusEl.className = 'text-muted small mb-2';
                                    statusEl.textContent = 'Lengkapi kapasitas, tanggal, dan waktu untuk melihat rekomendasi ruangan.';
                                    resetSelection();
                                    return;
                                }

                                statusEl.className = 'text-muted small mb-2';
                                statusEl.textContent = 'Mencari ruangan yang tersedia...';

                                const params = new URLSearchParams({
                                    date: date,
                                    start_time: startTime,
                                    end_time: endTime,
                                    capacity: capacity
                                });

                                fetch(`{{ route('roombookings.recommend') }}?${params.toString()}`, {
                                    headers: {
                                        'X-Requested-With': 'XMLHttpRequest',
                                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                    }
                                })
                                .then(res => {
                                    if (!res.ok) throw new Error('Fetch failed');
                                    return res.json();
                                })
                                .then(data => {
                                    const rooms = data.rooms || [];
                                    resetSelection();

                                    if (rooms.length === 0) {
                                        statusEl.className = 'text-danger small mb-2';
                                        statusEl.textContent = buildUnavailableMessage(data.suggestion, capacity);
                                    } else {
                                        statusEl.className = 'text-muted small mb-2';
                                        statusEl.textContent = 'Pilih salah satu ruangan yang tersedia:';
                                        renderRooms(rooms);
                                    }
                                })
                                .catch(err => {
                                    console.error(err);
                                    statusEl.className = 'text-danger small mb-2';
                                    statusEl.textContent = 'Gagal memeriksa ketersediaan ruangan. Silakan coba lagi.';
                                });
                            }

                            // Auto check availability whenever criteria change (debounced)
                            [dateEl, startEl, endEl, capacityEl].forEach(el => {
                                const eventName = (el === capacityEl) ? 'input' : 'change';
                                el.addEventListener(eventName, function () {
                                    clearTimeout(debounceTimer);
                                    debounceTimer = setTimeout(updateRooms, 400);
                                });
                            });

                            // Run on load if old inputs are present
                            if (dateEl.value && startEl.value && endEl.value && capacityEl.value) {
                                updateRooms();
                            }
                        });
                        </script>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection