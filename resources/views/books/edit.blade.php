@extends('layouts.app')

@section('content')
  <div class="page-stack">
    <section class="page-header">
      <div>
        <div class="breadcrumb"><a href="{{ route('books.index') }}">Katalog Buku</a> / Edit</div>
        <h1>Edit Buku</h1>
        <p>Perbarui informasi buku.</p>
      </div>
      <a class="btn btn-secondary" href="{{ route('books.index') }}">Batal</a>
    </section>

    <section class="panel">
      <form method="post" action="{{ route('books.update', $book->id) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="form-grid">
          <label>Judul
            <input type="text" name="title" value="{{ old('title', $book->title) }}" required>
            @error('title')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
          </label>
          <label>Subtitle
            <input type="text" name="subtitle" value="{{ old('subtitle', $book->subtitle) }}">
          </label>
          <label>Cover
            <input type="file" name="cover_image" accept="image/*">
            @if($book->cover_image)
              <div class="note">Cover saat ini: <img src="{{ $book->cover_image }}" style="height:48px;margin-left:8px"></div>
            @endif
          </label>
          <label>ISBN
            <input type="text" name="isbn" value="{{ old('isbn', $book->isbn) }}">
            @error('isbn')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
          </label>
          <label>Tahun Terbit
            <input type="number" name="publication_year" min="1900" max="2100" value="{{ old('publication_year', $book->publication_year) }}">
          </label>
          <label>Sinopsis
            <textarea name="synopsis">{{ old('synopsis', $book->synopsis) }}</textarea>
          </label>
          <label>Kategori
            <select name="category_id">
              <option value="">Tanpa kategori</option>
              @foreach($categories as $category)
                <option value="{{ $category->id }}" @selected(old('category_id', $book->category_id) == $category->id)>{{ $category->name }}</option>
              @endforeach
            </select>
            @error('category_id')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
          </label>
        </div>
        <div class="form-actions">
          <button class="btn btn-primary" type="submit">Simpan Perubahan</button>
        </div>
      </form>
    </section>
  </div>
@endsection
