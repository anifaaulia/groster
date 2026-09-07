@extends('partials.main.index')
@section('content')
    <div class="row">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h3 class="card-title">Daftar Acara</h3>
                    <p class="card-description">Kelola data acara yang akan dilaksanakan.</p>
                    @role(['admin', 'pic'])
                    <div class="d-flex justify-content-end mb-3">
                        <a href="{{ route('events.create') }}" class="btn btn-primary btn-sm text-white mb-0 me-0"
                            type="button"><i class="mdi mdi-plus"></i>Tambah Acara</a>
                    </div>
                    @endrole
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Acara</th>
                                    <th>Penanggung Jawab</th>
                                    <th>Waktu Mulai</th>
                                    <th>Waktu Selesai</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($events as $index => $event)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $event->name }}</td>
                                        <td>{{ $event->user ? $event->user->name : '-' }}</td>
                                        <td>{{ \Carbon\Carbon::parse($event->start_date)->format('d M Y, H:i') }}</td>
                                        <td>{{ \Carbon\Carbon::parse($event->end_date)->format('d M Y, H:i') }}</td>
                                        <td>
                                            @if($event->is_approved)
                                                <label class="badge badge-success">Disetujui</label>
                                            @else
                                                <label class="badge badge-warning">Menunggu Persetujuan</label>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('events.show', $event->id) }}"
                                                class="btn btn-info btn-sm text-white" title="Detail & Peserta">
                                                <i class="mdi mdi-account-group"></i>
                                            </a>
                                            @role('admin')
                                            @if(!$event->is_approved)
                                                <form action="{{ route('events.approve', $event->id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="btn btn-success btn-sm text-white" title="Setujui Acara">
                                                        <i class="mdi mdi-check"></i>
                                                    </button>
                                                </form>
                                            @endif
                                            <form action="{{ route('events.destroy', $event->id) }}" method="POST"
                                                class="d-inline">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm text-white" title="Hapus"
                                                    onclick="return confirm('Apakah anda yakin ingin menghapus data ini?')">
                                                    <i class="mdi mdi-delete"></i>
                                                </button>
                                            </form>
                                            @endrole
                                        </td>
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
