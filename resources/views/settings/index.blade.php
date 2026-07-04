@extends('layouts.app')

@section('content')
<div class="d-flex flex-column gap-3">
  <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
    <div>
      <h1 class="h3 mb-1">Pengaturan Sistem</h1>
      <p class="text-body-secondary mb-0">Kelola konfigurasi dan preferensi sistem perpustakaan.</p>
    </div>
    <a href="{{ route('home') }}" class="btn btn-outline-secondary">Kembali</a>
  </div>

  @if(session('success'))
    <div class="alert alert-success mb-0">{{ session('success') }}</div>
  @endif

  <div class="card shadow-sm">
    <div class="card-body">
      <form action="{{ route('settings.update') }}" method="POST">
        @csrf
        <h2 class="h5">Profil Perpustakaan</h2>
        <div class="row g-3 mb-4">
          <div class="col-md-6">
            <label class="form-label" for="library_name">Nama Perpustakaan</label>
            <input id="library_name" type="text" name="library_name" value="{{ $settings['library_name'] ?? '' }}" class="form-control">
          </div>
          <div class="col-md-6">
            <label class="form-label" for="library_email">Email</label>
            <input id="library_email" type="email" name="library_email" value="{{ $settings['library_email'] ?? '' }}" class="form-control">
          </div>
          <div class="col-md-8">
            <label class="form-label" for="library_address">Alamat</label>
            <input id="library_address" type="text" name="library_address" value="{{ $settings['library_address'] ?? '' }}" class="form-control">
          </div>
          <div class="col-md-4">
            <label class="form-label" for="library_phone">Telepon</label>
            <input id="library_phone" type="tel" name="library_phone" value="{{ $settings['library_phone'] ?? '' }}" class="form-control">
          </div>
        </div>

        <h2 class="h5 border-top pt-4">Kebijakan Peminjaman</h2>
        <div class="row g-3">
          <div class="col-md-4">
            <label class="form-label" for="max_borrow_limit">Batas Pinjam (Buku)</label>
            <input id="max_borrow_limit" type="number" name="max_borrow_limit" value="{{ $settings['max_borrow_limit'] ?? 5 }}" min="1" class="form-control">
          </div>
          <div class="col-md-4">
            <label class="form-label" for="borrow_duration_days">Durasi Default (Hari)</label>
            <input id="borrow_duration_days" type="number" name="borrow_duration_days" value="{{ $settings['borrow_duration_days'] ?? 14 }}" min="1" class="form-control">
          </div>
          <div class="col-md-4">
            <label class="form-label" for="late_fine_per_day">Denda per Hari (Rp)</label>
            <input id="late_fine_per_day" type="number" name="late_fine_per_day" value="{{ $settings['late_fine_per_day'] ?? 5000 }}" min="0" class="form-control">
          </div>
        </div>

        <div class="d-flex justify-content-end gap-2 border-top pt-3 mt-4">
          <a href="{{ route('home') }}" class="btn btn-outline-secondary">Batal</a>
          <button type="submit" class="btn btn-primary">Simpan</button>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection
