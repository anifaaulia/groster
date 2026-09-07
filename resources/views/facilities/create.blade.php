@extends('partials.main.index')
@section('content')
  <div class="row">
    <div class="col-md-12 grid-margin stretch-card">
      <div class="card">
        <div class="card-body">
          <h3 class="card-title">Tambah Fasilitas</h3>
          <p class="card-description"> Masukkan data fasilitas baru pada form di bawah ini. </p>
          <form action="{{ route('facilities.store') }}" method="POST" class="forms-sample">
            @csrf
            <div class="form-group">
              <label for="name">Nama Fasilitas</label>
              <input type="text" name="name" class="form-control" id="name" placeholder="Masukan nama fasilitas" required>
            </div>
            <button type="submit" class="btn btn-primary me-2 text-white">Simpan</button>
            <a href="{{ route('facilities.index') }}" class="btn btn-light">Batal</a>
          </form>
        </div>
      </div>
    </div>
  </div>
@endsection