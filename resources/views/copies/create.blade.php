@extends('layouts.app')

@section('content')
  <div class="page-stack">
    <section class="page-header">
      <div>
        <div class="breadcrumb"><a href="{{ route('copies.index') }}">Eksamplar</a> / Tambah</div>
        <h1>Tambah Eksamplar</h1>
      </div>
      <a class="btn btn-secondary" href="{{ route('copies.index') }}">Batal</a>
    </section>

    <section class="panel">
      <form method="post" action="{{ route('copies.store') }}">
        @csrf
        <div class="form-grid">
          <label>Buku
            <select name="book_id" required>
              <option value="">-- pilih buku --</option>
              @foreach($books as $b)
                <option value="{{ $b->id }}">{{ $b->title }}</option>
              @endforeach
            </select>
          </label>
          <label>Barcode
            <input type="text" name="barcode" required>
          </label>
          <label>Lokasi
            <input type="text" name="location">
          </label>
          <label>Status
            <input type="text" name="status" value="available">
          </label>
        </div>
        <div class="form-actions">
          <button class="btn btn-primary" type="submit">Simpan</button>
        </div>
      </form>
    </section>
  </div>
@endsection
