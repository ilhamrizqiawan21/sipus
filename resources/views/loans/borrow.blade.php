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
            @php($selectedCopies = collect(old('book_copy_ids', []))->map(fn($id) => (int) $id)->all())
            <div class="table-responsive border rounded">
              <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                  <tr>
                    <th style="width: 64px;">Cek</th>
                    <th>Buku</th>
                    <th>Barcode</th>
                    <th>Lokasi</th>
                    <th>Status</th>
                  </tr>
                </thead>
                <tbody>
                  @forelse($copies as $copy)
                    @php($inputId = 'book_copy_' . $copy->id)
                    <tr>
                      <td>
                        <input id="{{ $inputId }}" class="form-check-input" type="checkbox" name="book_copy_ids[]" value="{{ $copy->id }}" @checked(in_array((int) $copy->id, $selectedCopies, true))>
                      </td>
                      <td>
                        <label class="fw-semibold mb-0" for="{{ $inputId }}">{{ $copy->book?->title ?? 'Tanpa judul' }}</label>
                      </td>
                      <td class="font-monospace small">{{ $copy->barcode ?? 'Belum diisi' }}</td>
                      <td>{{ $copy->location ?? '-' }}</td>
                      <td>
                        <span class="badge text-bg-{{ optional($copy->status)->is_available ? 'success' : 'secondary' }}">
                          {{ optional($copy->status)->name ?? 'Tersedia' }}
                        </span>
                      </td>
                    </tr>
                  @empty
                    <tr>
                      <td colspan="5" class="text-center text-body-secondary py-4">Tidak ada eksemplar tersedia.</td>
                    </tr>
                  @endforelse
                </tbody>
              </table>
            </div>
            @error('book_copy_ids')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
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
