@extends('layouts.app')

@section('content')
<div class="d-flex flex-column gap-3">
  <div>
    <h1 class="h3 mb-1">Laporan Perpustakaan</h1>
    <p class="text-body-secondary mb-0">Ringkasan statistik koleksi, sirkulasi, dan keuangan.</p>
  </div>

  <div class="row g-3">
    <div class="col-md-3">
      <div class="card shadow-sm h-100">
        <div class="card-body">
          <div class="text-body-secondary small">Total Peminjaman</div>
          <div class="fs-3 fw-bold text-primary">{{ $circulationReport['total_borrowed'] ?? 0 }}</div>
        </div>
      </div>
    </div>
    <div class="col-md-3">
      <div class="card shadow-sm h-100">
        <div class="card-body">
          <div class="text-body-secondary small">Peminjaman Aktif</div>
          <div class="fs-3 fw-bold text-warning">{{ $circulationReport['active_loans'] ?? 0 }}</div>
        </div>
      </div>
    </div>
    <div class="col-md-3">
      <div class="card shadow-sm h-100">
        <div class="card-body">
          <div class="text-body-secondary small">Buku Terlambat</div>
          <div class="fs-3 fw-bold text-danger">{{ $overdueReport['total_overdue'] ?? 0 }}</div>
        </div>
      </div>
    </div>
    <div class="col-md-3">
      <div class="card shadow-sm h-100">
        <div class="card-body">
          <div class="text-body-secondary small">Denda Belum Lunas</div>
          <div class="fs-5 fw-bold text-danger">Rp {{ number_format($finesReport['unpaid_fines'] ?? 0, 0, ',', '.') }}</div>
        </div>
      </div>
    </div>
  </div>

  <div class="row g-3">
    <div class="col-lg-6">
      <div class="card shadow-sm h-100">
        <div class="card-header bg-white"><h2 class="h5 mb-0">Statistik Koleksi</h2></div>
        <div class="list-group list-group-flush">
          <div class="list-group-item d-flex justify-content-between"><span>Total Buku</span><strong class="text-primary">{{ $collectionReport['total_books'] ?? 0 }}</strong></div>
          <div class="list-group-item d-flex justify-content-between"><span>Total Eksemplar</span><strong class="text-success">{{ $collectionReport['total_copies'] ?? 0 }}</strong></div>
        </div>
      </div>
    </div>
    <div class="col-lg-6">
      <div class="card shadow-sm h-100">
        <div class="card-header bg-white"><h2 class="h5 mb-0">Top Kategori</h2></div>
        <div class="list-group list-group-flush">
          @forelse(($collectionReport['by_category'] ?? []) as $cat)
            <div class="list-group-item d-flex justify-content-between"><span>{{ $cat->name }}</span><strong>{{ $cat->count }}</strong></div>
          @empty
            <div class="list-group-item text-body-secondary">Belum ada kategori.</div>
          @endforelse
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
