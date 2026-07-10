@extends('layouts.app')

@section('content')
  <div class="page-stack">
    <section class="page-header">
      <div>
        <div class="breadcrumb"><a href="{{ route('books.index') }}">Katalog Buku</a> / Detail</div>
        <h1>{{ $book->title }}</h1>
        <p>ISBN: {{ $book->isbn ?? '-' }}</p>
      </div>
      <a class="btn btn-secondary" href="{{ route('books.index') }}">Kembali</a>
    </section>

    <section class="detail-grid">
      <div class="panel cover-panel">
        <div class="book-cover large">
          @if($book->cover_image)
            <img src="{{ $book->cover_image }}" alt="Cover {{ $book->title }}">
          @else
            <span>{{ strtoupper(mb_substr($book->title, 0, 1)) }}</span>
          @endif
        </div>
      </div>

      <div class="panel">
        <div class="panel-header">
          <h2>Informasi Buku</h2>
        </div>
        <dl class="detail-list">
          <div><dt>Judul</dt><dd>{{ $book->title }}</dd></div>
          <div><dt>ISBN</dt><dd>{{ $book->isbn ?? '-' }}</dd></div>
          <div><dt>Kategori</dt><dd>{{ $book->category?->name ?? 'Tanpa kategori' }}</dd></div>
          <div><dt>Tahun Terbit</dt><dd>{{ $book->publication_year ?? '-' }}</dd></div>
          <div><dt>Nomor Panggil</dt><dd>{{ $book->call_number ?? '-' }}</dd></div>
        </dl>
        <div class="synopsis">
          <h3>Sinopsis</h3>
          <p>{{ $book->synopsis ?: 'Sinopsis belum tersedia.' }}</p>
        </div>
      </div>
    </section>
  </div>
@endsection
