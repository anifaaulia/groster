@extends('partials.main.index')
@section('content')
    <div class="row">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h3 class="card-title">Daftar Kehadiran</h3>
                    <p class="card-description"> Kelola data kehadiran peserta acara. </p>
                    <div class="d-flex justify-content-end mb-3">
                        <a href="{{ route('attendances.create') }}" class="btn btn-primary btn-sm text-white mb-0 me-0"
                            type="button"><i class="mdi mdi-plus"></i>Tambah Kehadiran</a>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Peserta</th>
                                    <th>Ruangan</th>
                                    <th>Waktu Rekam/Hadir</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($attendances as $index => $attendance)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $attendance->user ? $attendance->user->name : '-' }}</td>
                                        <td>{{ $attendance->room ? $attendance->room->name : '-' }}</td>
                                        <td>{{ $attendance->updated_at ?? $attendance->created_at }}</td>
                                        <td>
                                            @if($attendance->status == 'on_time')
                                                <label class="badge badge-success">Tepat Waktu</label>
                                            @elseif($attendance->status == 'late')
                                                <label class="badge badge-warning">Terlambat</label>
                                            @else
                                                <label class="badge badge-danger">Pulang Cepat</label>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('attendances.show', $attendance->id) }}"
                                                class="btn btn-info btn-sm text-white" title="Edit"><i class="mdi mdi-pencil"></i></a>
                                            <form action="{{ route('attendances.destroy', $attendance->id) }}" method="POST"
                                                class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm text-white" title="Hapus"
                                                    onclick="return confirm('Apakah anda yakin ingin menghapus data ini?')"><i class="mdi mdi-delete"></i></button>
                                            </form>
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