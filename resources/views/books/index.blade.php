@extends('layouts.app')

@section('content')
  <div class="page-stack">
    <section class="page-header">
      <div>
        <div class="breadcrumb">SIPUS / Koleksi / Buku</div>
        <h1>Katalog Buku</h1>
        <p>{{ number_format($books->total(), 0, ',', '.') }} judul tersimpan di katalog perpustakaan</p>
      </div>
      <div class="page-actions">
        <a class="btn btn-secondary" href="{{ route('books.import.form') }}">Import</a>
        <a class="btn btn-primary" href="{{ route('books.create') }}">Tambah Buku</a>
      </div>
    </section>

    <section class="panel table-panel">
      @if(session('success'))
        <div class="notice success">{{ session('success') }}</div>
      @endif
      <div class="toolbar">
        <label class="search-field">
          <span>⌕</span>
          <input type="search" placeholder="Cari judul, kode, ISBN...">
        </label>
        <a class="btn btn-secondary" href="{{ route('books.import.form') }}">Import</a>
        <button class="btn btn-secondary" type="button">Filter</button>
      </div>

      <div class="table-responsive">
        <table class="data-table">
          <thead>
            <tr>
              <th>Cover</th>
              <th>Judul</th>
              <th>ISBN</th>
              <th>Kategori</th>
              <th>Tahun</th>
              <th>Status</th>
              <th></th>
            </tr>
          </thead>
          <tbody>
            @forelse($books as $book)
              <tr>
                <td>
                  <div class="book-cover">
                    @if($book->cover_image)
                      <img src="{{ $book->cover_image }}" alt="Cover {{ $book->title }}">
                    @else
                      <span>{{ strtoupper(mb_substr($book->title, 0, 1)) }}</span>
                    @endif
                  </div>
                </td>
                <td>
                  <strong>{{ $book->title }}</strong>
                  <small>{{ $book->subtitle ?? 'Katalog Perpustakaan' }}</small>
                </td>
                <td class="mono">{{ $book->isbn ?? '-' }}</td>
                <td>{{ $book->category_id ? 'Kategori #' . $book->category_id : '-' }}</td>
                <td>{{ $book->publication_year ?? '-' }}</td>
                <td><span class="status success">{{ ($book->is_active ?? true) ? 'Aktif' : 'Nonaktif' }}</span></td>
                <td>
                  <a class="table-action" href="{{ route('books.show', $book->id) }}">Detail</a>
                  <a class="table-action" href="{{ route('books.edit', $book->id) }}">Edit</a>
                  <form method="post" action="{{ route('books.destroy', $book->id) }}" style="display:inline">@csrf @method('DELETE')<button class="table-action danger" type="submit" onclick="return confirm('Hapus buku ini?')">Hapus</button></form>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="7" class="empty-state">Belum ada buku yang tercatat.</td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>

      <div class="panel-footer">
        <span>Menampilkan {{ $books->count() }} dari {{ $books->total() }} data</span>
        <div>{{ $books->links() }}</div>
      </div>
    </section>
  </div>
@endsection
