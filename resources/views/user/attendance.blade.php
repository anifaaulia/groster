@extends('partials.main.index')
@section('content')
<div class="row">
    <div class="col-md-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h3 class="card-title">Status Kehadiran</h3>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Ruangan</th>
                                <th>Waktu Presensi</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($attendances as $index => $att)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $att->room ? $att->room->name : '-' }}</td>
                                <td>{{ $att->created_at }}</td>
                                <td>
                                    @if($att->status == 'on_time')
                                    <span class="badge badge-success">Tepat Waktu</span>
                                    @elseif($att->status == 'late')
                                    <span class="badge badge-warning">Terlambat</span>
                                    @elseif($att->status == 'left_early')
                                    <span class="badge badge-danger">Pulang Cepat</span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                            @if($attendances->isEmpty())
                            <tr><td colspan="4" class="text-center">Belum ada data kehadiran</td></tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
