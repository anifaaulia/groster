@extends('partials.main.index')
@section('content')
    <div class="row">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h3 class="card-title">Daftar Peminjaman Ruangan</h3>
                    <p class="card-description"> Kelola data peminjaman ruangan oleh pengguna. </p>
                    @role(['admin', 'pic'])
                    <div class="d-flex justify-content-end mb-3 gap-2">
                        <a href="{{ route('roombookings.export') }}" class="btn btn-success btn-sm text-white mb-0" type="button">
                            <i class="mdi mdi-microsoft-excel"></i> Export Excel
                        </a>
                        <a href="{{ route('roombookings.create') }}" class="btn btn-primary btn-sm text-white mb-0" type="button">
                            <i class="mdi mdi-plus"></i>Tambah Peminjaman
                        </a>
                    </div>
                    @endrole
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Ruangan</th>
                                    <th>Kegiatan</th>
                                    <th>Peminjam</th>
                                    <th>Tanggal</th>
                                    <th>Waktu Mulai</th>
                                    <th>Waktu Selesai</th>
                                    <th>Status</th>
                                    @role(['admin', 'pic'])
                                    <th>Aksi</th>
                                    @endrole
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($roomBookings as $index => $booking)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $booking->room ? $booking->room->name : '-' }}</td>
                                        <td>{{ $booking->event ? $booking->event->name : '-' }}</td>
                                        <td>{{ $booking->user ? $booking->user->name : '-' }}</td>
                                        <td>{{ \Carbon\Carbon::parse($booking->date)->format('Y-m-d') }}</td>
                                        <td>{{ \Carbon\Carbon::parse($booking->start_time)->format('H:i') }}</td>
                                        <td>{{ \Carbon\Carbon::parse($booking->end_time)->format('H:i') }}</td>
                                        <td>
                                            @if($booking->is_active)
                                                <label class="badge badge-success">Disetujui</label>
                                            @else
                                                <label class="badge badge-warning">Menunggu Verifikasi</label>
                                            @endif
                                        </td>
                                        @role(['admin', 'pic'])
                                        <td>
                                            @role('admin')
                                                @if(!$booking->is_active)
                                                    <form action="{{ route('roombookings.approve', $booking->id) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        <button type="submit" class="btn btn-success btn-sm text-white" title="Setujui Peminjaman"><i class="mdi mdi-check"></i></button>
                                                    </form>
                                                    <form action="{{ route('roombookings.reject', $booking->id) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        <button type="submit" class="btn btn-warning btn-sm text-white" title="Tolak Peminjaman"><i class="mdi mdi-close"></i></button>
                                                    </form>
                                                @endif
                                                <a href="{{ route('roombookings.show', $booking->id) }}"
                                                    class="btn btn-info btn-sm text-white" title="Edit"><i class="mdi mdi-pencil"></i></a>
                                                <form action="{{ route('roombookings.destroy', $booking->id) }}" method="POST"
                                                    class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm text-white" title="Hapus"
                                                        onclick="return confirm('Apakah anda yakin ingin menghapus data ini?')"><i class="mdi mdi-delete"></i></button>
                                                </form>
                                            @else
                                                <a href="{{ route('roombookings.show', $booking->id) }}"
                                                    class="btn btn-info btn-sm text-white" title="Lihat Detail"><i class="mdi mdi-eye"></i></a>
                                            @endrole
                                        </td>
                                        @endrole
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection