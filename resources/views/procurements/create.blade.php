@extends('layouts.app')

@section('content')
  <div class="page-stack">
    <section class="page-header">
      <div>
        <div class="breadcrumb"><a href="{{ route('procurements.index') }}">Pengadaan</a> / Tambah</div>
        <h1>Tambah Pengadaan</h1>
      </div>
      <a class="btn btn-secondary" href="{{ route('procurements.index') }}">Batal</a>
    </section>

    <section class="panel">
      <form method="post" action="{{ route('procurements.store') }}">
        @csrf
        <div class="form-grid">
          <label>Supplier
            <input type="text" name="supplier_name" required>
          </label>
          <label>Tanggal Pesan
            <input type="date" name="order_date" value="{{ date('Y-m-d') }}" required>
          </label>
          <label>Items (satu per baris: judul,qty,harga)
            <textarea name="items" rows="6" placeholder="Contoh: Buku A,2,50000"></textarea>
          </label>
          <label>Catatan
            <textarea name="notes"></textarea>
          </label>
        </div>
        <div class="form-actions">
          <button class="btn btn-primary" type="submit">Simpan</button>
        </div>
      </form>
    </section>
  </div>
@endsection
