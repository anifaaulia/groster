@extends('partials.main.index')
@section('content')
    <div class="row">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h3 class="card-title">Daftar Fasilitas</h3>
                    <p class="card-description"> Kelola data master fasilitas yang tersedia. </p>
                    <div class="d-flex justify-content-end mb-3">
                        <a href="{{ route('facilities.create') }}" class="btn btn-primary btn-sm text-white mb-0 me-0"
                            type="button"><i class="mdi mdi-plus"></i>Tambah Fasilitas</a>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Fasilitas</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($facilities as $index => $facility)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $facility->name }}</td>
                                        <td>
                                            <a href="{{ route('facilities.show', $facility->id) }}"
                                                class="btn btn-info btn-sm text-white" title="Edit"><i class="mdi mdi-pencil"></i></a>
                                            <form action="{{ route('facilities.destroy', $facility->id) }}" method="POST"
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