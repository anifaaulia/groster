@extends('partials.main.index')
@section('content')
  <div class="row">
    <div class="col-md-12 grid-margin stretch-card">
      <div class="card">
        <div class="card-body">
          <h3 class="card-title">Edit Fasilitas</h3>
          <p class="card-description"> Ubah data instistusi pada form di bawah ini. </p>
          <form action="{{ route('facilities.update', $facility->id) }}" method="POST" class="forms-sample">
            @method('PUT')
            @csrf
            <div class="form-group">
              <label for="name">Nama Fasilitas</label>
              <input type="text" name="name" value="{{ $facility->name }}" class="form-control" id="name" required>
            </div>
            <button type="submit" class="btn btn-primary me-2 text-white">Simpan Perubahan</button>
            <a href="{{ route('facilities.index') }}" class="btn btn-light">Batal</a>
          </form>
        </div>
      </div>
    </div>
  </div>
@endsection