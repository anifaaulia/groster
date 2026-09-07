@extends('partials.main.index')
@section('content')
    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Edit Pengguna</h4>
                    <p class="card-description"> Ubah data pengguna pada form di bawah ini. </p>
                    <form action="{{ route('users.update', $user->id) }}" method="POST" class="forms-sample">
                        @method('PUT')
                        @csrf
                        <div class="form-group">
                            <label for="name">Nama Lengkap</label>
                            <input type="text" name="name" class="form-control" id="name" value="{{ $user->name }}"
                                required>
                        </div>
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" name="email" class="form-control" id="email" value="{{ $user->email }}"
                                required>
                        </div>
                        <div class="form-group">
                            <label for="password">Password Baru (Opsional)</label>
                            <input type="password" name="password" class="form-control" id="password"
                                placeholder="Kosongkan jika tidak ingin mengubah password">
                        </div>
                        <div class="form-group">
                            <label for="institution_id">Instansi</label>
                            <select name="institution_id" class="form-control" id="institution_id" required>
                                <option value="">-- Pilih Instansi --</option>
                                @foreach($institutions as $institution)
                                    <option value="{{ $institution->id }}" {{ $user->institution_id == $institution->id ? 'selected' : '' }}>{{ $institution->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="role">Peran (Role)</label>
                            <select name="role" class="form-control" id="role" required>
                                <option value="">-- Pilih Peran --</option>
                                @foreach($roles as $role)
                                    <option value="{{ $role->name }}" {{ $user->hasRole($role->name) ? 'selected' : '' }}>
                                        {{ $role->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary me-2 text-white">Simpan Perubahan</button>
                        <a href="{{ route('users.index') }}" class="btn btn-light">Batal</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection