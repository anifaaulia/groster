@extends('partials.main.index')
@section('content')
  <div class="row">
    <div class="col-md-12 grid-margin stretch-card">
      <div class="card">
        <div class="card-body">
          <h3 class="card-title">Edit Institusi</h3>
          <p class="card-description"> Ubah data instistusi pada form di bawah ini. </p>
          <form action="{{ route('institutions.update', $institution->id) }}" method="POST" class="forms-sample">
            @method('PUT')
            @csrf
            <div class="form-group">
              <label for="name">Nama Institusi</label>
              <input type="text" name="name" value="{{ $institution->name }}" class="form-control" id="name" required>
            </div>
            <div class="form-group">
              <label for="address">Alamat</label>
              <input type="text" name="address" value="{{ $institution->address }}" class="form-control" id="address"
                required>
            </div>
            <div class="form-group">
              <label for="contact_person">PIC (Contact Person)</label>
              <input type="text" name="contact_person" value="{{ $institution->contact_person }}" class="form-control"
                id="contact_person" required>
            </div>
            <div class="form-group">
              <label for="contact_number">No Telp</label>
              <input type="text" name="contact_number" value="{{ $institution->contact_phone }}" class="form-control"
                id="contact_number" required>
            </div>
            <button type="submit" class="btn btn-primary me-2 text-white">Simpan Perubahan</button>
            <a href="{{ route('institutions.index') }}" class="btn btn-light">Batal</a>
          </form>
        </div>
      </div>
    </div>
  </div>
@endsection