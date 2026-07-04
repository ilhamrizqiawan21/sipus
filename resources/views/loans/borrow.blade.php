@extends('layouts.app')

@section('content')
<div class="d-flex flex-column gap-3">
  <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
    <div>
      <nav aria-label="breadcrumb">
        <ol class="breadcrumb mb-1">
          <li class="breadcrumb-item"><a href="{{ route('loans.index') }}">Peminjaman</a></li>
          <li class="breadcrumb-item active">Baru</li>
        </ol>
      </nav>
      <h1 class="h3 mb-1">Form Peminjaman Buku</h1>
      <p class="text-body-secondary mb-0">Pilih anggota dan eksemplar yang tersedia.</p>
    </div>
    <a href="{{ route('loans.index') }}" class="btn btn-outline-secondary">Kembali</a>
  </div>

  @if($errors->any())
    <div class="alert alert-danger mb-0">
      <ul class="mb-0">
        @foreach($errors->all() as $error)
          <li>{{ $error }}</li>
        @endforeach
      </ul>
    </div>
  @endif

  <div class="card shadow-sm">
    <div class="card-body">
      <form action="{{ route('loans.store') }}" method="POST">
        @csrf
        <div class="row g-3">
          <div class="col-md-6">
            <label class="form-label" for="member_id">Anggota</label>
            <select id="member_id" name="member_id" class="form-select" required>
              <option value="">Pilih anggota</option>
              @foreach($members as $m)
                <option value="{{ $m->id }}" @selected(old('member_id') == $m->id)>{{ $m->name }} ({{ $m->member_code }})</option>
              @endforeach
            </select>
          </div>
          <div class="col-md-3">
            <label class="form-label" for="borrow_date">Tanggal Peminjaman</label>
            <input id="borrow_date" type="date" name="borrow_date" value="{{ old('borrow_date', date('Y-m-d')) }}" class="form-control" required>
          </div>
          <div class="col-md-3">
            <label class="form-label" for="duration_days">Durasi (Hari)</label>
            <input id="duration_days" type="number" name="duration_days" value="{{ old('duration_days', 14) }}" min="1" class="form-control" required>
          </div>
          <div class="col-12">
            <label class="form-label">Eksemplar</label>
            <div class="list-group border">
              @forelse($copies as $copy)
                <label class="list-group-item d-flex gap-3 align-items-start">
                  <input class="form-check-input mt-1" type="checkbox" name="book_copy_ids[]" value="{{ $copy->id }}" @checked(in_array($copy->id, old('book_copy_ids', [])))>
                  <span>
                    <span class="fw-semibold">{{ $copy->book->title ?? 'Tanpa judul' }}</span>
                    <span class="d-block text-body-secondary small">{{ $copy->barcode ?? 'Barcode belum diisi' }} - {{ $copy->location ?? 'Lokasi belum diisi' }}</span>
                  </span>
                </label>
              @empty
                <div class="list-group-item text-body-secondary">Tidak ada eksemplar tersedia.</div>
              @endforelse
            </div>
          </div>
          <div class="col-12">
            <label class="form-label" for="notes">Catatan</label>
            <textarea id="notes" name="notes" rows="3" class="form-control">{{ old('notes') }}</textarea>
          </div>
        </div>
        <div class="d-flex justify-content-end gap-2 border-top pt-3 mt-4">
          <a href="{{ route('loans.index') }}" class="btn btn-outline-secondary">Batal</a>
          <button type="submit" class="btn btn-primary">Simpan</button>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection
