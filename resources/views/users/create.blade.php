@extends('partials.main.index')
@section('content')
    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Tambah Pengguna</h4>
                    <p class="card-description"> Masukkan data pengguna baru pada form di bawah ini. </p>
                    <form action="{{ route('users.store') }}" method="POST" class="forms-sample">
                        @csrf
                        <div class="form-group">
                            <label for="name">Nama Lengkap</label>
                            <input type="text" name="name" class="form-control" id="name" placeholder="Masukan nama lengkap"
                                required>
                        </div>
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" name="email" class="form-control" id="email"
                                placeholder="Masukan alamat email" required>
                        </div>
                        <div class="form-group">
                            <label for="password">Password</label>
                            <input type="password" name="password" class="form-control" id="password"
                                placeholder="Masukan password" required>
                        </div>
                        <div class="form-group">
                            <label for="institution_id">Instansi</label>
                            <select name="institution_id" class="form-control" id="institution_id" required>
                                <option value="">-- Pilih Instansi --</option>
                                @foreach($institutions as $institution)
                                    <option value="{{ $institution->id }}">{{ $institution->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="role">Peran (Role)</label>
                            <select name="role" class="form-control" id="role" required>
                                <option value="">-- Pilih Peran --</option>
                                @foreach($roles as $role)
                                    <option value="{{ $role->name }}">{{ $role->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary me-2 text-white">Simpan</button>
                        <a href="{{ route('users.index') }}" class="btn btn-light">Batal</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection