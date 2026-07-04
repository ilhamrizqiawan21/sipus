@extends('layouts.app')

@section('content')
  <div class="page-stack">
    <section class="page-header">
      <div>
        <div class="breadcrumb"><a href="{{ route('books.index') }}">Katalog Buku</a> / Import</div>
        <h1>Import Katalog</h1>
        <p>Unggah file CSV atau XLSX berisi kolom: title,isbn,subtitle,category/kategori,category_id,publication_year,synopsis</p>
      </div>
      <a class="btn btn-secondary" href="{{ route('books.index') }}">Kembali</a>
    </section>

    <section class="panel">
      @if(session('success'))
        <div class="notice success">{{ session('success') }}</div>
      @endif
      @if($errors->any())
        <div class="alert alert-danger">
          <ul class="mb-0">
            @foreach($errors->all() as $error)
              <li>{{ $error }}</li>
            @endforeach
          </ul>
        </div>
      @endif
      <form method="post" action="{{ route('books.import') }}" enctype="multipart/form-data">
        @csrf
        <label>Pilih file CSV atau XLSX
          <input type="file" name="file" accept=".csv,.txt,.xlsx,text/csv,text/plain,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet" required>
          @error('file')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
        </label>
        <div class="form-actions">
          <button class="btn btn-primary" type="submit">Mulai Import</button>
        </div>
      </form>
    </section>
  </div>
@endsection
