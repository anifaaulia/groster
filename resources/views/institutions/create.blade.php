@extends('partials.main.index')
@section('content')
  <div class="row">
    <div class="col-md-12 grid-margin stretch-card">
      <div class="card">
        <div class="card-body">
          <h3 class="card-title">Tambah Institusi</h3>
          <p class="card-description"> Masukkan data institusi baru pada form di bawah ini. </p>
          <form action="{{ route('institutions.store') }}" method="POST" class="forms-sample">
            @csrf
            <div class="form-group">
              <label for="name">Nama Institusi</label>
              <input type="text" name="name" class="form-control" id="name" placeholder="Masukan nama institusi" required>
            </div>
            <div class="form-group">
              <label for="address">Alamat</label>
              <input type="text" name="address" class="form-control" id="address" placeholder="Masukan alamat institusi"
                required>
            </div>
            <div class="form-group">
              <label for="contact_person">PIC (Contact Person)</label>
              <input type="text" name="contact_person" class="form-control" id="contact_person"
                placeholder="Masukan nama kontak" required>
            </div>
            <div class="form-group">
              <label for="contact_number">No Telp</label>
              <input type="text" name="contact_number" class="form-control" id="contact_number"
                placeholder="Masukan nomor telepon" required>
            </div>
            <button type="submit" class="btn btn-primary me-2 text-white">Simpan</button>
            <a href="{{ route('institutions.index') }}" class="btn btn-light">Batal</a>
          </form>
        </div>
      </div>
    </div>
  </div>
@endsection