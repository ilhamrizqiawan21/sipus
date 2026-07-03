@extends('layouts.app')

@section('content')
  <div class="page-stack">
    <section class="page-header">
      <div>
        <div class="breadcrumb"><a href="{{ route('books.index') }}">Katalog Buku</a> / Tambah</div>
        <h1>Tambah Buku</h1>
        <p>Tambahkan judul buku baru ke katalog.</p>
      </div>
      <a class="btn btn-secondary" href="{{ route('books.index') }}">Batal</a>
    </section>

    <section class="panel">
      <form method="post" action="{{ route('books.store') }}" enctype="multipart/form-data">
        @csrf
        <div class="form-grid">
          <label>Judul
            <input type="text" name="title" required>
          </label>
          <label>Subtitle
            <input type="text" name="subtitle">
          </label>
          <label>Cover
            <input type="file" name="cover_image" accept="image/*">
          </label>
          <label>ISBN
            <input type="text" name="isbn">
          </label>
          <label>Tahun Terbit
            <input type="number" name="publication_year" min="1900" max="2100">
          </label>
          <label>Sinopsis
            <textarea name="synopsis"></textarea>
          </label>
          <label>Kode Kategori
            <input type="number" name="category_id">
          </label>
        </div>
        <div class="form-actions">
          <button class="btn btn-primary" type="submit">Simpan</button>
        </div>
      </form>
    </section>
  </div>
@endsection
