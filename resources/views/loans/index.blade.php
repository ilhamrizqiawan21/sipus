@extends('layouts.app')

@section('content')
<div class="d-flex flex-column gap-3">
  <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
    <div>
      <h1 class="h3 mb-1">Peminjaman Buku</h1>
      <p class="text-body-secondary mb-0">Kelola transaksi peminjaman dan pengembalian buku.</p>
    </div>
    <div class="d-flex flex-wrap gap-2">
      <a href="{{ route('loans.borrow') }}" class="btn btn-primary">Pencatatan Peminjaman</a>
      <a href="{{ route('loans.return') }}" class="btn btn-success">Pencatatan Pengembalian</a>
    </div>
  </div>

  @if(session('success'))
    <div class="alert alert-success mb-0">{{ session('success') }}</div>
  @endif

  <div class="card shadow-sm">
    <div class="card-header bg-white">
      <h2 class="h5 mb-0">Daftar Peminjaman</h2>
    </div>
    <div class="table-responsive">
      <table class="table table-hover align-middle mb-0">
        <thead class="table-light">
          <tr>
            <th>Kode</th>
            <th>Anggota</th>
            <th>Tgl Pinjam</th>
            <th>Jatuh Tempo</th>
            <th>Status</th>
            <th class="text-end">Aksi</th>
          </tr>
        </thead>
        <tbody>
          @forelse($loans as $loan)
            @php
              $badge = match($loan->status) {
                'borrowed' => 'primary',
                'returned' => 'success',
                'partially_returned' => 'warning',
                default => 'danger',
              };
            @endphp
            <tr>
              <td class="font-monospace small">{{ $loan->transaction_code }}</td>
              <td>{{ $loan->member->name ?? $loan->member_name_snapshot }}</td>
              <td>{{ $loan->borrow_date->format('d/m/Y') }}</td>
              <td>{{ $loan->due_date->format('d/m/Y') }}</td>
              <td><span class="badge text-bg-{{ $badge }}">{{ str_replace('_', ' ', ucfirst($loan->status)) }}</span></td>
              <td class="text-end"><a href="{{ route('loans.show', $loan->id) }}" class="btn btn-sm btn-outline-primary">Lihat</a></td>
            </tr>
          @empty
            <tr><td colspan="6" class="text-center text-body-secondary py-5">Tidak ada data peminjaman.</td></tr>
          @endforelse
        </tbody>
      </table>
    </div>
    <div class="card-footer d-flex justify-content-end">
      {{ $loans->links() }}
    </div>
  </div>
</div>
@endsection
