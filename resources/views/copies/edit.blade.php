@extends('layouts.app')

@section('content')
  <div class="page-stack">
    <section class="page-header">
      <div>
        <div class="breadcrumb"><a href="{{ route('copies.index') }}">Eksamplar</a> / Edit</div>
        <h1>Edit Eksamplar</h1>
      </div>
      <a class="btn btn-secondary" href="{{ route('copies.index') }}">Batal</a>
    </section>

    <section class="panel">
      <form method="post" action="{{ route('copies.update', $copy->id) }}">
        @csrf @method('put')
        <div class="form-grid">
          <label>Buku
            <select name="book_id" required>
              @foreach($books as $b)
                <option value="{{ $b->id }}" {{ $b->id == $copy->book_id ? 'selected' : '' }}>{{ $b->title }}</option>
              @endforeach
            </select>
          </label>
          <label>Barcode
            <input type="text" name="barcode" required value="{{ $copy->barcode }}">
          </label>
          <label>Lokasi
            <input type="text" name="location" value="{{ $copy->location }}">
          </label>
          <label>Status
            <input type="text" name="status" value="{{ $copy->status }}">
          </label>
        </div>
        <div class="form-actions">
          <button class="btn btn-primary" type="submit">Simpan</button>
        </div>
      </form>
    </section>
  </div>
@endsection
