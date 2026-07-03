@extends('layouts.app')

@section('content')
  <div class="page-stack">
    <section class="page-header">
      <div>
        <div class="breadcrumb"><a href="{{ route('books.index') }}">Katalog Buku</a> / Import</div>
        <h1>Import Katalog</h1>
        <p>Unggah file CSV berisi kolom: title,isbn,subtitle,category_id,publication_year,synopsis</p>
      </div>
      <a class="btn btn-secondary" href="{{ route('books.index') }}">Kembali</a>
    </section>

    <section class="panel">
      <form method="post" action="{{ route('books.import') }}" enctype="multipart/form-data">
        @csrf
        <label>Pilih file CSV
          <input type="file" name="file" accept=".csv,text/csv" required>
        </label>
        <div class="form-actions">
          <button class="btn btn-primary" type="submit">Mulai Import</button>
        </div>
      </form>
    </section>
  </div>
@endsection
