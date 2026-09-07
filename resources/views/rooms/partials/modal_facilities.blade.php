<!-- Facilities Modal -->
<div class="modal fade" id="detailModal{{ $room->id }}" tabindex="-1"
    aria-labelledby="detailModalLabel{{ $room->id }}" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="detailModalLabel{{ $room->id }}"><i class="mdi mdi-tools me-2"></i> Fasilitas: {{ $room->name }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="bg-light p-3 rounded mb-3">
                    <p class="mb-1 text-muted small uppercase">Kapasitas Maksimal:</p>
                    <h6 class="mb-0 fw-bold">{{ $room->capacity }} Orang</h6>
                </div>
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead class="bg-primary text-white">
                            <tr>
                                <th>Nama Fasilitas</th>
                                <th class="text-center">Jumlah</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($room->facilities as $facility)
                                <tr>
                                    <td><i class="mdi mdi-checkbox-marked-circle-outline me-1 text-success"></i> {{ $facility->name }}</td>
                                    <td class="text-center fw-bold">{{ $facility->pivot->quantity }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="2" class="text-center italic text-muted">Belum ada fasilitas terdaftar untuk ruangan ini.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary text-white" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>
