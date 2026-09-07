@extends('partials.main.index')
@section('content')
    <div class="row">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h3 class="card-title">Daftar Pengguna</h3>
                    <p class="card-description"> Kelola data pengguna sistem aplikasi. </p>
                    <div class="d-flex justify-content-end gap-2 mb-3">
                        <button type="button" class="btn btn-success btn-sm text-white" data-bs-toggle="modal" data-bs-target="#importModal">
                            <i class="mdi mdi-file-excel me-1"></i>Import Excel
                        </button>
                        <a href="{{ route('users.create') }}" class="btn btn-primary btn-sm text-white" type="button">
                            <i class="mdi mdi-plus"></i>Tambah Pengguna
                        </a>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Lengkap</th>
                                    <th>Email</th>
                                    <th>Instansi</th>
                                    <th>Peran (Role)</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($users as $index => $user)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $user->name }}</td>
                                        <td>{{ $user->email }}</td>
                                        <td>{{ $user->institution ? $user->institution->name : '-' }}</td>
                                        <td>
                                            @foreach($user->roles as $role)
                                                <label class="badge badge-info">{{ $role->name }}</label>
                                            @endforeach
                                        </td>
                                        <td>
                                            <a href="{{ route('users.show', $user->id) }}"
                                                class="btn btn-info btn-sm text-white" title="Edit"><i class="mdi mdi-pencil"></i></a>
                                            <form action="{{ route('users.destroy', $user->id) }}" method="POST"
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

{{-- Modal Import Excel --}}
<div class="modal fade" id="importModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="mdi mdi-file-excel me-2 text-success"></i>Import Pengguna via Excel</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('users.import') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <p class="text-muted small mb-3">
                        Upload file Excel dengan kolom: <code>nama</code>, <code>email</code>, <code>password</code>,
                        <code>instansi</code>, <code>peran</code>.<br>
                        Nilai <strong>peran</strong> yang valid: <code>admin</code>, <code>pic</code>, <code>participant</code>.<br>
                        Kolom <strong>instansi</strong> harus sesuai nama instansi yang sudah ada di sistem.
                    </p>
                    <div class="form-group mb-3">
                        <label class="form-label fw-bold small">Pilih File</label>
                        <input type="file" name="file" class="form-control" accept=".xlsx,.xls,.csv" required>
                        <small class="text-muted">Format: .xlsx, .xls, atau .csv (maks. 2MB)</small>
                    </div>
                    <a href="{{ route('users.template') }}" class="btn btn-outline-secondary btn-sm w-100">
                        <i class="mdi mdi-download me-1"></i> Unduh Template Excel
                    </a>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success text-white">
                        <i class="mdi mdi-upload me-1"></i> Import
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection